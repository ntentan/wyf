<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\http\Uri;
use ntentan\utils\Text;
use ntentan\mvc\attributes\Action;
use ntentan\mvc\attributes\Method;
use ntentan\mvc\attributes\Header;
use ntentan\http\Redirect;
use ntentan\nibii\ModelDescription;


/**
 * The CRUD controller presents an interface for adding and removing data in models.
 * @author ekow
 */
class CrudController extends WyfController
{
    private ModelDescription $modelDescription;
    
    private function getModelDescription(): ModelDescription
    {
        if (!isset($this->modelDescription)) {
            $this->modelDescription = $this->getModelDescription()->getDescription();
        }
        return $this->modelDescription;
    }
    
    protected function getPrimaryKey(): string
    {
        
    }
    
    protected function getFields(): array
    {
        return array_filter(array_map(fn($x) => $x['name'], $this->getModelDescription()->getFields()), fn($x) => );
    }
    
    /**
     * The main action lists all items in the model.
     * 
     * @param Uri $uri
     * @param View $view
     * @return View
     */
    public function main(Uri $uri, View $view): View
    {
        $description = $this->getModelInstance()->getDescription();
        $fields = $description->getFields();
        $primaryKey = $description->getPrimaryKey();
        
        $view->setTemplate("wyf_{$this->getEntity()}_crud_main");
        $view->set([
            "wyf_add_link" => "{$uri->getPrefix()}{$uri->getPath()}/add",
            "wyf_fields" => $fields,
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
     * Present a form for adding new items to the model.
     * 
     * @param View $view
     * @return View
     */
    public function add(View $view): View
    {
        $this->setupView($view);
        return $view;
    }
    
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
