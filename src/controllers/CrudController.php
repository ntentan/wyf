<?php

namespace ntentan\wyf\controllers;

use ntentan\interfaces\RenderableInterface;
use ntentan\Redirect;
use ntentan\View;
use ntentan\honam\TemplateEngine;
use ntentan\utils\Text;
use ntentan\Model;
use ntentan\Context;
use ntentan\utils\filesystem\UploadedFile;
use ajumamoro\Queue;
use ntentan\wyf\controllers\crud\ImportTemplateView;
use ntentan\wyf\controllers\crud\ListViewDecorator;
use ntentan\wyf\interfaces\ImportDataJobInterface;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

/**
 * Provides the CRUD interface through which databases are manipulated.
 *
 */
class CrudController extends WyfController
{
    /**
     * An array of operations that this controller can perform on data records.
     *
     * @var array
     */
    private $operations = [];

    /**
     * The fields that are displayed in the list of items.
     *
     * @var array
     */
    protected $listFields;

    /**
     * Fields that are expected to be in the import data file.
     *
     * @var array
     */
    protected $importFields = [];

    /**
     * An instance of the ntentan context
     *
     * @var Context
     */
    private $context;

    private $entity;

    private $entities;

    protected $addItemLabel;

    /**
     * CrudController constructor.
     *
     * @param View $view The singleton view that will eventually be used to render the page.
     */
    public function __construct(View $view)
    {
        parent::__construct($view);
        $this->context = Context::getInstance();
        $this->entities = $this->getWyfName();
        $this->entity = Text::singularize($this->getWyfName());
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/crud'));
        TemplateEngine::appendPath('views/forms');
        TemplateEngine::appendPath('views/lists');

        $wyfPath = $this->getWyfPath();
        $apiUrl = $this->context->getUrl('api');
        $view->set([
            'entities' => $this->entities,
            'entity' => $this->entity,
            'has_add_operation' => true,
            'has_import_operation' => count($this->importFields) ? true : false,
            'package' => str_replace('.', '_', $this->getWyfPackage()),
            'api_url' => "$apiUrl/$wyfPath",
            'base_api_url' => $apiUrl,
            'base_url' => $this->context->getUrl($this->context->getParameter('controller_path'))
        ]);
    }

    /**
     * Return an instance of the model that is wrapped by this CRUD controller.
     *
     * @return Model
     */
    protected function getModel() : Model
    {
        return Model::load($this->getWyfPackage());
    }


    /**
     * The default controller action that lists all items.
     *
     * @param ListViewDecorator $listing
     * @return RenderableInterface
     */
    public function index(ListViewDecorator $listing)
    {
        $this->setTitle(ucwords($this->entities));

        $listing->setFields($this->listFields);
        $listing->setup($this->getWyfPackage(), $this->entities, $this->getActionUrl("/"));
        $listing->addOperation('edit', 'Edit');
        $listing->addOperation('delete', 'Delete');

        return $listing;
    }

    /**
     * The controller action for adding new items.
     * This action is executed during a get request to present the user with the initial form.
     *
     * @param Model $model An instance of a model
     * @param View $view The view to be used for rendering the page.
     * @return View
     */
    public function add(Model $model, View $view)
    {
        $view->set(['model' => $model, 'form_data' => $view->get('form_data') ?? []]);
        $this->setTitle("Add new " . ucwords($this->getWyfName()));
        return $view;
    }

    /**
     * The controller action for adding new items.
     * This action is executed when the contents of a form are submitted with a POST request. The action attempts to
     * save the data. When succesful, the action redirects to the index action. On failure however, it displays the
     * errors on the form with the expectation that the user can rectify them.
     *
     * @ntentan.action add
     * @ntentan.method POST
     * @param Model $model An instance of the model populated with data from the form.
     * @param View $view The view to be used for rendering the page.
     * @return View
     */
    public function store(Model $model, View $view)
    {
        if ($model->save()) {
            $this->notify("Added new {$this->entity}: {$model}");
            return $this->getRedirect();
        }
        $view->set(['model' => $model, 'form_data' => $view->get('form_data') ?? []]);
        $this->setTitle("Add new {$this->getWyfName()}");
        return $view;
    }

    /**
     * This action saves the uploaded file and enqueues the job that performs the import.
     *
     * @ntentan.action import
     * @ntentan.method POST
     *
     * @param UploadedFile $data
     * @param Model $model
     * @param Queue $queue
     * @param ImportDataJobInterface $job
     * @return string
     */
    public function importData(UploadedFile $data, Model $model, Queue $queue, ImportDataJobInterface $job, KeyValueStoreInterface $keyValueStore)
    {
        $destination = ($this->context->getConfig()->get('app.temp_dir') ?? "uploads/") . basename($data->getClientName());
        $data->copyTo($destination);
        $job->setParameters($destination, $model, $this->importFields);
        $job->setAttributes(['file' => $destination]);
        $jobId = $queue->add($job);
        $keyValueStore->put($this->getImportJobIdKey(), $jobId);
        return json_encode($jobId);
    }

    /**
     * Generates an import template and outputs it as a CSV file.
     *
     * @param ImportTemplateView $view
     * @return View
     */
    public function importTemplate(ImportTemplateView $view)
    {
        $view->setModel($this->getModel(), $this->importFields, $this->entities);
        return $view;
    }

    /**
     * Returns the status of an import session that is running in the background.
     *
     * @param View $view
     * @param Queue $queue
     * @param $id
     * @return View
     */
    public function importStatus(View $view, Queue $queue, $id)
    {
        $status = $queue->getJobStatus($id);
        $view->setTemplate('plain');
        $view->setLayout('api');
        $view->set('response', $status);
        header('Content-Type: application/json');
        return $view;
    }

    /**
     * Presents a set of import options.
     *
     * @param View $view
     * @param KeyValueStoreInterface $keyValueStore
     * @param Queue $queue
     * @return View
     */
    public function import(View $view, KeyValueStoreInterface $keyValueStore, Queue $queue)
    {
        $jobId = $keyValueStore->get($this->getImportJobIdKey());
        $view->set('job_status', $queue->getJobStatus($jobId));
        $view->set('job_id',$jobId);
        $view->set('import_template_url', $this->getActionUrl('import_template'));
        $this->setTitle("Import " . ucwords($this->getWyfName()));
        return $view;
    }

    /**
     * Resets the import key so fresh new imports can be started.
     *
     * @param KeyValueStoreInterface $keyValueStore
     * @return Redirect
     */
    public function resetImports(KeyValueStoreInterface $keyValueStore)
    {
        $keyValueStore->put($this->getImportJobIdKey(), null);
        return $this->getRedirect()->toAction('import');
    }

    /**
     * Return the key used to save the import job id for a given model
     *
     * @return string
     */
    private function getImportJobIdKey() : string
    {
        return "{$this->getWyfPackage()}:import_job_id";
    }

    public function edit(View $view, $id)
    {
        $model = $this->getModel();
        $view->set('model', $this->getModel()->fetchFirst($id));
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $view->set('primary_key_field', $primaryKey);
        $this->setTitle("Edit {$this->getWyfName()}");
        return $view;
    }

    /**
     * Saves changes to the model collected with the edit form.
     * When save is successful redirect to the list. Whe save fails show the user a form highlighting errors.
     *
     * @ntentan.action edit
     * @ntentan.method POST
     * @param Model $model
     * @param View $view
     * @return View
     */
    public function update(Model $model, View $view)
    {
        if ($model->save()) {
            $this->notify("Updated {$this->entity}: {$model}");
            return $this->getRedirect();
        }
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $view->set('primary_key_field', $primaryKey);
        $view->set('model', $model);
        $this->setTitle("Edit {$this->entity}: {$model}");
        return $view;
    }

    /**
     * @param View $view
     * @param $id
     * @param null $confirm
     * @return View
     */
    public function delete(View $view, $id, $confirm = null)
    {
        $model = $this->getModel();
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $item = $model->fetchFirst([$primaryKey => $id]);
        if ($confirm == 'yes') {
            $item->delete($id);
            $this->notify("Deleted {$this->entity}: {$item}");
            return $this->getRedirect();
        }
        $view->set('item', $item);
        $view->set('delete_yes_url', $this->getActionUrl("delete/$id?confirm=yes"));
        $view->set('delete_no_url', $this->getActionUrl(''));
        $this->setTitle("Delete {$this->entity}: {$item}");
        return $view;
    }
}
