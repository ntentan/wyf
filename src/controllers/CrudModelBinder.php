<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf\controllers;

use ntentan\panie\InjectionContainer;

/**
 * Description of CrudModelBinder
 *
 * @author ekow
 */
class CrudModelBinder extends \ntentan\controllers\DefaultModelBinder
{
    public function bind(\ntentan\Controller $controller, $type)
    {
        if($type == 'ntentan\Model') {
            $type = InjectionContainer::singleton(\ntentan\nibii\interfaces\ClassResolverInterface::class)
                ->getModelClassName($controller->getWyfPackage(), null);
        }
        return parent::bind($controller, $type);
    }
}
