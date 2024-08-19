<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\Model;
use ntentan\mvc\View;
use ntentan\http\Uri;
use ntentan\mvc\Action;
use ntentan\nibii\ModelDescription;
use ntentan\utils\Text;
use ntentan\http\StringStream;
use ntentan\http\filters\Header;
use ntentan\http\filters\Method;
use ntentan\http\Redirect;

use Psr\Http\Message\ResponseInterface;


/**
 * The CRUD controller presents views for listing, adding, editing, and deleting records from Models.
 */
class CrudController extends WyfController
{
    /**
     * An array of operations, which can be executed on individual records.
     * @var array
     */
    protected array $operations = [];

    /**
     * An instance of the description of the model wrapped by the CRUD controller.`
     * @var \ntentan\nibii\ModelDescription
     */
    private ModelDescription $modelDescription;
    
    /**
     * A list of fields to fetch for each record.
     * If left empty, all fields except for thos in the primary key are returned.
     * @var array
     */
    private array $fields;

    /**
     * CRUD controllers for other models that are related to this model.
     * @var array
     */
    private array $subCrudControllers = [];
    
    /**
     * Get an instance of the description for the model attached to this CRUD controller.
     * @return ModelDescription
     */
    private function getModelDescription(): ModelDescription
    {
        if (!isset($this->modelDescription)) {
            $this->modelDescription = $this->getModelInstance()->getDescription();
        }
        return $this->modelDescription;
    }
    
    /**
     * Get an list of fields that act as primary keys for the backing model.
     * @return array
     */
    protected function getPrimaryKey(): array
    {
        return $this->getModelDescription()->getPrimaryKey();
    }
    
    /**
     * Get a list of the fields for this model to be displayed in lists.
     * @return array
     */
    protected function getFields(): array
    {
        if (!isset($this->fields)) {
            $primaryKey = $this->getPrimaryKey();
            $fieldNames = array_filter(
                array_map(fn($x) => $x['name'], $this->getModelDescription()->getFields()),
                fn($x) => !in_array($x, $primaryKey)
                );
            $this->fields = [];
            
            foreach($fieldNames as $fieldName) {
                $this->fields[$fieldName] = ucfirst(str_replace("_", " ", $fieldName));
            }
        }
        
        return $this->fields;
    }
    
    /**
     * The main action lists all items in the model.
     * 
     * @param Uri $uri
     * @param View $view
     * @return View
     */
    #[Action]
    public function main(Uri $uri, View $view): View
    {   
        $fields = $this->getFields();
        $view->setTemplate("wyf_{$this->getEntity()}_crud_main");
        $view->set([
            "add_path" => "{$uri->getPrefix()}{$uri->getPath()}/add",
            "list_data_path" => "{$uri->getPrefix()}{$uri->getPath()}/",
            "list_fields" => array_keys($fields),
            "list_labels" => array_values($fields),
            "key_fields" => $this->getPrimaryKey()[0],
            "wyf_crud_mode" => 'list',
            "wyf_entity" => Text::ucamelize($this->getEntity())
        ]);
        return $view;
    }

    protected function addOperation(string $path, string $label): void
    {
        $this->operations[] = ['path' => $path, 'label' => $label];
    }

    protected function addSubCrudOperation(string $path, string $label, string $crudControllerClass): void
    {
        $this->operations[] = ['path' => $path, 'label' => $label];
        $this->subCrudControllers[] = $crudControllerClass;
    }

    protected function getOperations(array $item): array
    {
        $primaryKey = $this->getPrimaryKey()[0];
        $operations = [];
        foreach($this->operations as $operation) {
            $operations[] = [
                'path' => "{$operation['path']}/{$item[$primaryKey]}",
                'label' => $operation['label']
            ];
        }
        return $operations;
    }
    
    #[Action("main")]
    #[Header('accept', 'application/json')]
    public function list(StringStream $output, ResponseInterface $response, Uri $uri): ResponseInterface
    {
        $this->addOperation("{$uri->getPrefix()}{$uri->getPath()}edit", "Edit");
        $this->addOperation("{$uri->getPrefix()}{$uri->getPath()}delete", "Delete");  
        
        $model = $this->getModelInstance();
        $items = $model->sortDescById()->fetch()->getData();
        foreach($items as $i => $item) {
            $items[$i]['operations'] = $this->getOperations($item);
        }
        $output->write(json_encode($items));
        return $response->withBody($output)->withHeader("content-type", "application/json");
    }
    
    /**
     * Configure the view and select the correct templates for the current request and entity.
     * 
     * @param View $view
     */
    private function setupView(View $view, string $action): void
    {
        $view->setTemplate("wyf_{$this->getEntity()}_crud_{$action}");
        $view->set([
            'wyf_entity' => Text::singularize($this->getEntity()),
            'model' => $this->getModelInstance()
        ]);
    }
    
    private function saveData(View $view, ResponseInterface $redirect, string $operation): View | ResponseInterface
    {
        $model = $this->getModelInstance();
        $model = $this->getModelBinder()->bind($model, $this->getEntity());
        if($model->save()) {
            return $redirect
                ->withHeader("Location",
                    $this->getContext()->getPath("/{$this->getControllerSpec()->getControllerName()}"))
                ->withStatus(302);
        }
        $this->setupView($view, $operation);
        $view->set('errors', $model->getInvalidFields());
        $view->set('data', $model->getData());
        return $view;
    }

    /**
     * Presents a form for adding new items to the model.
     * @param View $view
     * @return View
     */
    #[Action]
    public function add(View $view): View
    {
        $this->setupView($view, 'add');
        return $view;
    }
    
    /**
     * Saves any data submitted through a form and presents a pre-populated form if data is invalid and needs to be
     * changed.
     */
    #[Action("add")]
    #[Method("post")]
    public function save(View $view, ResponseInterface $response): View|ResponseInterface
    {
        return $this->saveData($view, $response, 'add');
    }
    
    #[Action]
    public function edit(View $view, string $id): View
    {
        $this->setupView($view, 'edit');
        $item = $this->getModelInstance()->fetchFirstWithId($id)->toArray();
        if ($item) {
            $view->set('data', $item);
        }
        return $view;
    }
    
    #[Action('edit')]
    #[Method('post')]
    public function update(View $view, Redirect $redirect): View|Redirect
    {
        return $this->saveData($view, $redirect, 'edit');
    }

    #[Action]
    public function delete(View $view, string $id): View
    {
        $this->setupView($view, 'delete');
        $view->set([
            'entity' => $this->getEntity(),
            'item' => $this->getModelInstance()->fetchFirstWithId($id),
            'id' => $id
        ]);
        return $view;
    }

    #[Action("delete")]
    #[Method("post")]
    public function remove(View $view, Redirect $redirect, string $id): Redirect|View
    {
        $instance = $this->getModelInstance()->fetchFirstWithId($id);
        if ($instance) {
            $instance->delete();
            return $redirect->to("/{$this->getControllerSpec()->getControllerName()}");
        }
        $view->set('errors', "Cannot find item to delete.");
        return $view;
    }
}
