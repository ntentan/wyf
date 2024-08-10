<?php
namespace ntentan\wyf\controllers;


use ntentan\mvc\View;
use ntentan\http\Uri;
use ntentan\utils\Text;
use ntentan\mvc\attributes\Action;
use ntentan\mvc\attributes\Method;
use ntentan\http\Redirect;


/**
 * The CRUD controller presents an interface for adding and removing data in models.
 * @author ekow
 */
class CrudController extends WyfController
{
    /**
     * The main action lists all items in the model.
     * 
     * @param Uri $uri
     * @param View $view
     * @return View
     */
    public function main(Uri $uri, View $view): View
    {
        $view->setTemplate("wyf_{$this->getEntity()}_crud_main");
        $view->set(["wyf_add_link" => "{$uri->getPrefix()}{$uri->getPath()}/add"]);
        return $view;
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
