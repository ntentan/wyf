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

/**
 * Description of CrudModelBinder
 *
 * @author ekow
 */
class CrudModelBinder extends DefaultModelBinder
{
    public function bind(Controller $controller, $type, $name)
    {
        if($type == 'ntentan\Model') {
            $type = InjectionContainer::singleton(\ntentan\nibii\interfaces\ModelClassResolverInterface::class)
                ->getModelClassName($controller->getWyfPackage(), null);
        }
        return parent::bind($controller, $type, $name);
    }
}
