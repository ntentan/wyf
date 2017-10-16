<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\honam\TemplateEngine;
use ntentan\utils\Text;
use ntentan\Model;
use ntentan\Context;
use ntentan\utils\filesystem\UploadedFile;
use ajumamoro\Queue;
use ntentan\wyf\ImportDataJobInterface;
use ntentan\wyf\jobs\ImportDataJob;

/**
 * CrudController 
 *
 */
class CrudController extends WyfController
{
    /**
     * An array of operations that this controller can perform on data records.
     * @var array
     */
    private $operations = [];

    /**
     * The fields that are displayed in the list of items.
     * @var array
     */
    protected $listFields = [];

    /**
     * Fields that are expected to be in the import data file.
     * @var array
     */
    protected $importFields = [];

    /**
     * An instance of the ntenan context
     * @var Context
     */
    private $context;

    /**
     * CrudController constructor.
     * @param View $view The singleton view that will eventually be used to render the page.
     */
    public function __construct(View $view)
    {
        parent::__construct($view);
        $this->context = Context::getInstance();
        $this->addOperation('edit', 'Edit');
        $this->addOperation('delete', 'Delete');
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/crud'));
        TemplateEngine::appendPath('views/forms');
        TemplateEngine::appendPath('views/lists');

        $wyfPath = $this->getWyfPath();
        $apiUrl = $this->context->getUrl('api');
        $view->set([
            'entities' => $this->getWyfName(),
            'entity' => Text::singularize($this->getWyfName()),
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
     * @return \ntentan\Model
     */
    protected function getModel() : Model
    {
        return Model::load($this->getWyfPackage());
    }

    /**
     * An array that contains a list of the fields to display when listing items.
     * @param array $listFields
     */
    protected function setListFields(array $listFields)
    {
        foreach ($listFields as $label => $name) {
            $this->listFields[] = [
                'name' => $name,
                'label' => is_numeric($label) ? $name : $label
            ];
        }
    }

    /**
     * The default controller action that lists all items.
     * @param View $view
     * @return View
     */
    public function index(View $view)
    {
        $model = $this->getModel();

        $description = $model->getDescription();
        $primaryKey = $description->getPrimaryKey()[0];
        if (empty($this->listFields)) {
            $fields = $description->getFields();
            foreach ($fields as $field) {
                if ($field['name'] == $primaryKey) {
                    continue;
                }
                $this->listFields[$field['name']] = ucwords(str_replace('_', ' ', $field['name']));
            }
        }

        $this->setTitle(ucwords($this->getWyfName()));
        // Prevent this from repeating
        $fields = [$primaryKey];
        foreach ($this->listFields as $field => $label) {
            $fields[] = is_numeric($field) ? $label : $field;
        }
        $fields = implode(',', $fields);
        $view->set([
            'add_item_url' => $this->getActionUrl('add'),
            'import_items_url' => $this->getActionUrl('import'),
            'public_path' => $this->context->getUrl('public'),
            'api_parameters' => "?fields=$fields",
            'list_fields' => $this->listFields,
            'operations' => $this->operations,
            'primary_key_field' => $primaryKey,
            'foreign_key' => false
        ]);

        return $view;
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
        $view->set('model', $model);
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
            return $this->getRedirect();
        }
        $view->set('model', $model);
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
    public function importData(UploadedFile $data, Model $model, Queue $queue, ImportDataJobInterface $job)
    {
        $destination = ($this->context->getConfig()->get('app.temp_dir') ?? "uploads/") . basename($data->getClientName());
        $data->copyTo($destination);
        $job->setParameters($destination, $model, $this->importFields);
        $job->setAttributes(['file' => $destination]);
        $jobId = $queue->add($job);
        return json_encode($jobId);
    }

    /**
     * Generates an import template and outputs it as a CSV file.
     *
     * @param View $view
     * @return View
     */
    public function importTemplate(View $view)
    {
        $view->setLayout('plain');
        $view->setTemplate('import_csv');
        $headers = array();
        $modelDescription = $this->getModel()->getDescription();
        $fields = array_keys($modelDescription->getFields());
        $relationshipDetails = $modelDescription->getRelationships();
        $relationships = array_keys($relationshipDetails);

        foreach ($this->importFields as $key => $field) {
            if (is_numeric($key)) {
                if (is_array($field) && in_array($field[0], $relationships)) {
                    $label = $relationshipDetails[$field[0]]->getModelInstance()->getName();
                    $headers[] = Text::singularize(ucwords(str_replace('_', ' ', Text::deCamelize($label))));
                } else if (in_array($field, $fields)) {
                    $headers[] = ucwords(str_replace('_', ' ', $field));
                }
            } else {
                $headers[] = $key;
            }
        }

        $view->set('headers', $headers);
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$this->getWyfName()}.csv");
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

    public function import(View $view)
    {
        $view->set('import_template_url', $this->getActionUrl('import_template'));
        $this->setTitle("Import " . ucwords($this->getWyfName()));
        return $view;
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
     * @ntentan.action edit
     * @ntentan.method POST
     * @param Model $model
     * @param View $view
     * @return type
     */
    public function update(Model $model, View $view)
    {
        if ($model->save()) {
            return $this->getRedirect();
        }
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $view->set('primary_key_field', $primaryKey);
        $view->set('model', $model);
        $this->setTitle("Edit {$this->getWyfName()}");
        return $view;
    }

    public function delete(View $view, $id, $confirm = null)
    {
        $model = $this->getModel();
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $item = $model->fetchFirst([$primaryKey => $id]);
        if ($confirm == 'yes') {
            $item->delete($id);
            return $this->getRedirect();
        }
        $view->set('item', $item);
        $view->set('delete_yes_url', $this->getActionUrl("delete/$id?confirm=yes"));
        $view->set('delete_no_url', $this->getActionUrl(''));
        return $view;
    }

    protected function addOperation($action, $label = null)
    {
        $this->operations[] = [
            'label' => $label == null ? $action : $label,
            'action' => $action
        ];
    }

}
