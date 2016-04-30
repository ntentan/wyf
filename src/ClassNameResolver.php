<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf;

use ntentan\Ntentan;
use ntentan\utils\Text;
use ntentan\nibii\interfaces\ClassResolverInterface as ModelClassResolver;
use ntentan\controllers\interfaces\ClassResolverInterface as ControllerClassResolver;

/**
 * Description of ClassNameResolver
 *
 * @author ekow
 */
class ClassNameResolver implements ModelClassResolver, ControllerClassResolver
{
    public function getControllerClassName($name)
    {
        return sprintf(
            '\%s\app\controllers\%sController', 
            Ntentan::getNamespace(), 
            Text::ucamelize($name)
        );        
    }

    public function getModelClassName($model, $context)
    {
        if($context == nibii\Relationship::BELONGS_TO) {
            $model = Text::pluralize($model);
        }
        $namespace = Ntentan::getNamespace();
        return "\\$namespace\\app\\models\\" . Text::ucamelize(explode('.', $model)[0]);        
    }
}
