<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\http\Uri;
use ntentan\utils\Text;


/**
 * A controller for the CRUD.
 * @author ekow
 */
class CrudController extends WyfController
{
    public function main(Uri $uri, View $view): View
    {
        $view->setTemplate("wyf_{$this->getEntity()}_crud_main");
        $view->set(["wyf_add_link" => "{$uri->getPrefix()}{$uri->getPath()}/add"]);
        return $view;
    }

    public function add(View $view): View
    {
        $view->setTemplate("wyf_{$this->getEntity()}_crud_add");
        $view->set([
            'wyf_entity' => Text::singularize($this->getEntity()),
            'model' => $this->getModelInstance()
        ]);
        return $view;
    }
}
