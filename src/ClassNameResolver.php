<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf;

use ntentan\Ntentan;
use ntentan\utils\Text;
use ntentan\nibii\interfaces\ModelClassResolverInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
use ntentan\nibii\Relationship;

/**
 * Description of ClassNameResolver
 *
 * @author ekow
 */
class ClassNameResolver implements ModelClassResolverInterface, ControllerClassResolverInterface
{
    private function getWyfClassName($wyfPath, $type)
    {
        $namespace = Ntentan::getNamespace();
        $wyfPathParts = explode('.', $wyfPath);
        $name = Text::ucamelize(array_pop($wyfPathParts));
        $base = (count($wyfPathParts) ? '\\' : '') . implode('\\', $wyfPathParts);
        return "\\$namespace\\app$base\\$type\\$name";          
    }
    
    public function getModelClassName($model, $context)
    {
        if($context == Relationship::BELONGS_TO) {
            $model = Text::pluralize($model);
        }
        return $this->getWyfClassName($model, 'models');
    }

    public function getControllerClassName($name)
    {
        return $this->getWyfClassName($name, 'controllers') . 'Controller';
    }
}
