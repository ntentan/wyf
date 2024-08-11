<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\http\Uri;
use ntentan\utils\Text;
use ntentan\mvc\Action;
use ntentan\http\filters\Method;
use ntentan\http\filters\Header;
use ntentan\http\Redirect;
use ntentan\nibii\ModelDescription;


/**
 * The CRUD controller presents an interface for adding and removing data in models.
 * @author ekow
 */
class CrudController extends WyfController
{
    /**
     * An instance of the model description.
     * @var \ntentan\nibii\ModelDescription
     */
    private ModelDescription $modelDescription;
    
    /**
     * A list of fields for the associated models.
     * @var array
     */
    private array $fields;
    
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
            "wyf_add_link" => "{$uri->getPrefix()}{$uri->getPath()}/add",
            "wyf_list_fields" => array_keys($fields),
            "wyf_list_labels" => array_values($fields),
            "wyf_key_fields" => $this->getPrimaryKey(),
            "wyf_mode" => 'list'
        ]);
        return $view;
    }
    
    #[Action("main")]
    #[Header('accept', 'application/json')]
    public function list(): string
    {
        $model = $this->getModelInstance();
        $items = $model->fetch();
        return json_encode($items->getData());
    }
    
    /**
     * Configure the view and select the correct templates for the current request and entity.
     * 
     * @param View $view
     */
    private function setupView(View $view): void
    {
        $view->setTemplate("wyf_{$this->getEntity()}_crud_add");
        $view->set([
            'wyf_entity' => Text::singularize($this->getEntity()),
            'model' => $this->getModelInstance()
        ]);
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
        $this->setupView($view);
        return $view;
    }
    
    /**
     * Saves any data submitted through a form and presents a pre-populated form if data is invalid and needs to be
     * changed.
     */
    #[Action("add")]
    #[Method("post")]
    public function save(View $view, Redirect $redirect): View|Redirect
    {
        $this->setupView($view);
        $model = $this->getModelInstance();
        $model = $this->getModelBinder()->bind($model);
        if($model->add()) {
            return $redirect->to("/". $this->getControllerSpec()->getControllerName());
        }
        $view->set('errors', $model->getInvalidFields());
        $view->set('data', $model->getData());
        return $view;
    }
}
