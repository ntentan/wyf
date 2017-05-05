<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf\controllers;

use ntentan\panie\InjectionContainer;
use ntentan\controllers\model_binders\DefaultModelBinder;
use ntentan\Controller;
use ntentan\Context;
use ntentan\nibii\interfaces\ModelClassResolverInterface;

/**
 * Description of CrudModelBinder
 *
 * @author ekow
 */
class CrudModelBinder extends DefaultModelBinder {

    public function bind(Controller $controller, $action, $type, $name) {
        if ($type == 'ntentan\Model') {
            $type = $this->container->singleton(ModelClassResolverInterface::class)
                        ->getModelClassName($controller->getWyfPackage(), null);
        }
        return parent::bind($controller, $action, $type, $name);
    }

}
