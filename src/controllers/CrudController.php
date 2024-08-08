<?php

namespace ntentan\wyf\controllers;

use ntentan\mvc\View;

class CrudController extends WyfController
{
    public function main(View $view): View
    {
        $controllerSpec = $this->getControllerSpec();
        $view->setTemplate("wyf_{$controllerSpec['controller']}_crud_main");
        return $view;
    }
}
