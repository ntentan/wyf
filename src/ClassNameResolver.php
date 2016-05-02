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
use ntentan\nibii\Relationship;

/**
 * Description of ClassNameResolver
 *
 * @author ekow
 */
class ClassNameResolver implements ModelClassResolver
{
    public function getModelClassName($model, $context)
    {
        if($context == Relationship::BELONGS_TO) {
            $model = Text::pluralize($model);
        }
        $namespace = Ntentan::getNamespace();
        $modelParts = explode('.', $model);
        $name = Text::ucamelize(array_pop($modelParts));
        $base = implode('\\', $modelParts);
        return "\\$namespace\\app\\$base\\models\\$name";        
    }
}
