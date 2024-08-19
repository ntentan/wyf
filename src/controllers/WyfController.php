<?php

namespace ntentan\wyf\controllers;

use ntentan\Context;
use ntentan\mvc\Model;
use ntentan\mvc\binders\ModelBinderInterface;
use ntentan\mvc\ControllerSpec;

/**
 * Base controller for all WYF application modules.
 */
class WyfController
{
    private ControllerSpec $controllerSpec;
    private string $modelClass;
    private string $namespace;
    private Model $modelInstance;
    private ModelBinderInterface $modelBinder;
    private Context $context;
    
    public function setup(ControllerSpec $controllerSpec, ModelBinderInterface $modelBinder, Context $context): void
    {
        $this->controllerSpec = $controllerSpec;
        $this->modelBinder = $modelBinder;
        $this->context = $context;
    }
    
    protected function getModelBinder(): ModelBinderInterface
    {
        return $this->modelBinder;
    }
    
    protected function getControllerSpec(): ControllerSpec
    {
        return $this->controllerSpec;
    }

    protected function getContext(): Context
    {
        return $this->context;
    }
    
    protected function getEntity(): string
    {
        return $this->controllerSpec->getControllerName();
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
