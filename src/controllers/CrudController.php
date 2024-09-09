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
use Psr\Http\Message\UriInterface;


/**
 * The CRUD controller presents views to users for listing, adding, editing, and deleting records from Models.
 */
class CrudController extends WyfController
{
    /**
     * An array of operations that are executed on individual records.
     * @var array
     */
    private array $operations = [];

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
    private array $listFields;

    /**
     * CRUD controllers for other models that are related to this model.
     * @var array
     */
    private array $subCrudControllers = [];

    /**
     * Cache to store the list filters after they have been built.
     * @var array
     */
    private array $listFilter;

    private bool $hasEditOperation = true;

    private bool $hasDeleteOperation = true;
    
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
    protected function getListFields(): array
    {
        if (!isset($this->listFields)) {
            $primaryKey = $this->getPrimaryKey();
            $fieldNames = array_filter(
                    array_map(fn($x) => $x['name'], $this->getModelDescription()->getFields()),
                    fn($x) => !in_array($x, $primaryKey)
                );
            $listFields = [];
            
            foreach($fieldNames as $fieldName) {
                $listFields[$fieldName] = ucfirst(str_replace("_", " ", $fieldName));
            }
            $this->listFields = $this->processListFields($listFields);
        }
        
        return $this->listFields;
    }

    private function processListFields(array $listFields): array
    {
        $fieldOrder = [];
        $queryFields = [];
        $labels = [];
        foreach($listFields as $fieldName => $fieldDescription) {
            $fieldParts = explode(".", $fieldName);
            $labels[] = $fieldDescription;
            $fieldDetails['name'] = $fieldName;

            if (count($fieldParts) === 2) {
                $fieldDetails['model'] = $fieldParts[0];
                $fieldDetails['field'] = $fieldParts[1];
            } else {
                $queryFields[] = $fieldName;
            }

            $fieldOrder[] = $fieldDetails;
        }
        return [
            'order' => $fieldOrder, 'query' => $queryFields,
            'labels' => $labels, 'names' => array_map(
                fn($x) => str_replace(".", "__", $x),
                array_keys($listFields)
            )
        ];
    }

    /**
     * Set the list of fields to be shown in the listing.
     *
     * Fields must be specified as in a key-value format, where the key represents a descriptive label for the field
     * and the value represents the database name of the field.
     *
     * @param array $listFields
     * @return void
     */
    protected function setListFields(array $listFields): void
    {
        $this->listFields = $this->processListFields($listFields);
    }

    private function getBreadcrumbHierarchy(array $appended): array
    {
        $hierarchy = $this->getControllerSpec()->getParameter('hierarchy') ?? [];
        $breadcrumbs = [];
        $modelPath = "";
        $context = $this->getContext();
        foreach($hierarchy as $model => $id) {
            $modelInstance = Model::load($model);
            $item = $modelInstance->fetchFirstWithId($id);
            $modelPath .= "/{$model}";
            $breadcrumbs[] = ['path' => $context->getPath($modelPath), 'label' => ucfirst(str_replace("_", " ", $model))];
            $breadcrumbs[] = ['path' => $context->getPath("$modelPath/view/$id"), 'label' => (string)$item ];
        }
        return array_merge($breadcrumbs, $appended);
    }
    
    /**
     * The main action lists all items in the model.
     * @param Uri $uri
     * @param View $view
     * @return View
     */
    #[Action]
    public function main(UriInterface $uri, View $view): View
    {
        $context = $this->getContext();
        $fields = $this->getListFields();
        $controllerPath = $this->getControllerSpec()->getParameter('controller_path');

        $view->set([
            "add_path" => "{$context->getPrefix()}{$uri->getPath()}/add",
            "list_data_path" => "{$context->getPrefix()}{$uri->getPath()}/",
            "list_fields" => $fields['names'],
            "list_labels" => $fields['labels'],
            "key_fields" => $this->getPrimaryKey()[0],
            "wyf_crud_mode" => 'list',
            "wyf_entity" => Text::ucamelize($this->getEntity()),
            "wyf_breadcrumbs" => $this->getBreadcrumbHierarchy([
                ['path' => $this->getContext()->getPath($controllerPath), 'label' => $this->getEntityDescription()]
            ])
        ]);
        return $view;
    }

    /**
     * Add an operation to the CRUD controller.
     * The operation calls an action method within the controller.
     * @param string $path
     * @param string $label
     * @return void
     */
    protected function addOperation(string $path, string $label): void
    {
        $this->operations[] = ['path' => $path, 'label' => $label];
    }

    /**
     * Add an action method for calling a sub CRUD controller.
     * @param string $label
     * @param string $path
     * @param string $crudControllerClass
     * @return void
     */
    protected function addSubCrudOperation(string $label, string $path, string $crudControllerClass): void
    {
        $this->addOperation($path, $label);
        $this->subCrudControllers[] = $crudControllerClass;
    }

    protected function getOperations(Model $item): array
    {
        $primaryKey = $this->getPrimaryKey();
        $operations = [];
        foreach($this->operations as $operation) {
            $operations[] = [
                'path' => "{$operation['path']}/" . implode(',', array_map(fn($x) => $item[$x], $primaryKey)),
                'label' => $operation['label']
            ];
        }
        return $operations;
    }

    /**
     * Filters the listing displayed.
     * @return array
     */
    protected function getListFilter(): array
    {
        $hierarchy = $this->getControllerSpec()->getParameter('hierarchy');
        $filter = [];
        if ($hierarchy) {
            $key = array_key_last($hierarchy);
            $value = $hierarchy[$key];
            $filter[Text::singularize($key) . '_id'] = $value;
        }
        return $filter;
    }

    private function setupOperations()
    {
        if ($this->hasEditOperation) {
            $this->addOperation("edit", "Edit");
        }
        if ($this->hasDeleteOperation) {
            $this->addOperation("delete", "Delete");
        }
    }
    
    #[Action("main")]
    #[Header('accept', 'application/json')]
    public function list(StringStream $output, ResponseInterface $response, Uri $uri): ResponseInterface
    {
        $this->setupOperations();
        $model = $this->getModelInstance();
        $listFields = $this->getListFields();
        $primaryKeys = $this->getPrimaryKey();
        $model = $model->fields(array_merge($listFields['query'], $primaryKeys));

        foreach($primaryKeys as $primaryKey) {
            $model->{"sortDescBy" . Text::ucamelize($primaryKey)}();
        }

        $items = $model->fetch($this->getListFilter());
        // Initialize the operations with the list's path
        $this->operations = array_map(
            function($operation) {
                $operation['path'] = "{$this->getContext()->getPrefix()}"
                    . "{$this->getControllerSpec()->getParameter('controller_path')}/{$operation['path']}";
                return $operation;
            },
            $this->operations
        );

        $finalList = [];
        foreach($items as $i => $item) {
            $row = [];
            foreach($listFields['order'] as $j => $field) {
                $row[$listFields['names'][$j]] = isset($field['model']) ? $item->{$field['model']}->{$field['field']} : $item->{$field['name']};
            }
            $row['operations'] = $this->getOperations($item);
            $finalList[] = $row;
        }
        $output->write(json_encode($finalList));
        return $response->withBody($output)->withHeader("content-type", "application/json");
    }
    
    /**
     * Configure the view and select the correct templates for the current request and entity.
     * 
     * @param View $view
     */
    private function setupView(View $view, string $action): void
    {
        $view->set([
            'wyf_entity' => Text::singularize($this->getEntity()),
            'model' => $this->getModelInstance(),
            'filter' => $this->getListFilter()
        ]);
    }
    
    private function saveData(View $view, ResponseInterface $redirect, string $operation): View | ResponseInterface
    {
        $model = $this->getModelInstance();
        $model = $this->getModelBinder()->bind($model, $this->getEntity());
        if($model->save()) {
            return $redirect
                ->withHeader("Location", $this->getControllerPath())
                ->withStatus(302);
        }
        $this->setupView($view, $operation);
        $view->set('errors', $model->getInvalidFields());
        $view->set('data', $model->getData());
        return $view;
    }

    /**
     * Presents a form for adding new items to the model.
     *
     * @param View $view
     * @return View
     */
    #[Action]
    public function add(View $view): View
    {
        $this->setupView($view, 'add');
        $controllerPath = $this->getControllerSpec()->getParameter('controller_path');
        $view->set([
            'wyf_breadcrumbs' => $this->getBreadcrumbHierarchy([
                ['path' => $this->getContext()->getPath("{$controllerPath}"), 'label' => $this->getEntityDescription()],
                ['path' => $this->getContext()->getPath("{$controllerPath}/add"), 'label' => 'Add']
            ])
        ]);
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

    private function getItem(string $ids) {
        $primaryKey = $this->getPrimaryKey();
        $filter = [];
        if (count($primaryKey) > 1) {
            $ids = explode(',', $ids);
            foreach($ids as $i => $id) {
                $filter[$primaryKey[$i]] = $id;
            }
        } else {
            $filter[$primaryKey[0]] = $ids;
        }

        return $this->getModelInstance()->fetchFirst($filter);
    }
    
    #[Action]
    public function edit(View $view, string $id): View
    {
        $this->setupView($view, 'edit');
        $ids = explode(",", $id);
        $item = $this->getItem($id);
        if ($item) {
            $view->set('data', $item->toArray());
        }
        $controllerPath = $this->getControllerSpec()->getParameter('controller_path');
        $view->set(['wyf_breadcrumbs' => $this->getBreadcrumbHierarchy([
            ['path' => $this->getContext()->getPath("/$controllerPath"), 'label' => $this->getEntityDescription()],
            ['path' => $this->getContext()->getPath("/$controllerPath/view/{$id}"), 'label' => $item],
            ['path' => $this->getContext()->getPath("/$controllerPath/edit/{$id}"), 'label' => 'Edit']
        ])]);
        return $view;
    }
    
    #[Action('edit')]
    #[Method('post')]
    public function update(View $view, ResponseInterface $redirect): View|ResponseInterface
    {
        return $this->saveData($view, $redirect, 'edit');
    }

    #[Action]
    public function delete(View $view, string $id): View
    {
        $this->setupView($view, 'delete');
        $item = $this->getItem($id);
        $controllerPath = $this->getControllerSpec()->getParameter('controller_path');
        $view->set([
            'entity' => $this->getEntity(),
            'item' => $item,
            'id' => $id,
            'wyf_breadcrumbs' => $this->getBreadcrumbHierarchy([
                ['path' => $this->getContext()->getPath("/$controllerPath"), 'label' => $this->getEntityDescription()],
                ['path' => $this->getContext()->getPath("/$controllerPath/view/{$id}"), 'label' => $item],
                ['path' => $this->getContext()->getPath("/$controllerPath/delete/{$id}"), 'label' => 'Delete']
            ])
        ]);
        return $view;
    }

    /**
     * Perform the actual deletion of an item.
     * @param View $view
     * @param ResponseInterface $redirect
     * @param string $id
     * @return ResponseInterface|View
     */
    #[Action("delete")]
    #[Method("post")]
    public function remove(View $view, ResponseInterface $redirect, string $id): ResponseInterface|View
    {
        $item = $this->getItem($id);
        if ($item) {
            $item->delete();
            return $redirect->withHeader("Location", $this->getControllerPath());
        }
        $this->setupView($view, 'delete');
        $view->set('errors', "Cannot find item to delete.");
        return $view;
    }
}

