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
use ntentan\nibii\interfaces\TableNameResolverInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
use ntentan\nibii\Relationship;

/**
 * Implements most of the class resolver interfaces required by Ntentan, This
 * allows specific WYF functionality like nested controllers and models to be
 * easily implemented.
 *
 * @author ekow
 */
class ClassNameResolver implements ModelClassResolverInterface, ControllerClassResolverInterface, TableNameResolverInterface {

    private function getWyfClassName($wyfPath, $type) {
        $namespace = Ntentan::getNamespace();
        $wyfPathParts = explode('.', $wyfPath);
        $name = Text::ucamelize(array_pop($wyfPathParts));
        $base = (count($wyfPathParts) ? '\\' : '') . implode('\\', $wyfPathParts);
        return "\\$namespace\\app$base\\$type\\$name";
    }

    /**
     * Return the class name for a model presented as a string. 
     * @param type $model
     * @param type $context
     * @return type
     */
    public function getModelClassName($model, $context) {
        if ($context == Relationship::BELONGS_TO) {
            $model = Text::pluralize($model);
        }
        return $this->getWyfClassName($model, 'models');
    }

    public function getControllerClassName($name) {
        return $this->getWyfClassName($name, 'controllers') . 'Controller';
    }

    /**
     * Returns the name of a database table given the model.
     * @param \ntentan\Model $instance
     */
    public function getTableName($instance) {
        $class = (new \ReflectionClass($instance))->getName();
        preg_match('|zefe\\\\app\\\\(?<base>[a-zA-Z0-9]+)\\\\.*models\\\\(?<model>[a-zA-Z0-9]+)|', $class, $matches);
        $driver = \ntentan\atiaa\Db::getDriver();
        $schema = Text::deCamelize($matches['base']);
        $table = Text::deCamelize($matches['model']);
        return "$schema.$table";
    }

}
