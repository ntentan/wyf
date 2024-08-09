<?php

namespace ntentan\wyf\controllers;

use ntentan\mvc\Model;

/**
 * Base controller for all WYF application modules you want to appear in the menu.
 */
class WyfController
{
    private array $controllerSpec;
    private string $modelClass;
    private string $namespace;
    private Model $modelInstance;
    
    public function setControllerSpec(array $controllerSpec): void
    {
        $this->controllerSpec = $controllerSpec;
    }
    
    protected function getControllerSpec(): array
    {
        return $this->controllerSpec;
    }
    
    protected function getEntity(): string
    {
        return $this->controllerSpec['controller'];
    }
    
    protected function getNamespace(): string
    {
        if (!isset($this->namespace)) {
            $items = explode("\\", $this->controllerSpec['class_name']);
            array_pop($items);
            $this->namespace = implode('\\', $items);
        }
        return $this->namespace;
    }
    
    protected function getModelInstance(): Model
    {
        if(!isset($this->modelInstance)) {
            $class = substr($this->controllerSpec['class_name'], 0, -10);
            return new $class();
        }
        return $this->modelInstance;
    }
    
    protected function setModelInstance(Model $modelInstance): void
    {
        $this->modelInstance = $modelInstance;
    }
}
