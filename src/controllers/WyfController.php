<?php

namespace ntentan\wyf\controllers;

use ntentan\Context;
use ntentan\mvc\Model;
use ntentan\mvc\binders\ModelBinderInterface;
use ntentan\mvc\ControllerSpec;
use ntentan\utils\Text;

/**
 * Base controller for all WYF application classes.
 * Classes that inherit the WyfController class gain access to information about the context within which WYF is
 * executing.
 */
class WyfController
{
    /**
     * An instance of the controller spec.
     * @var ControllerSpec
     */
    private ControllerSpec $controllerSpec;

    /**
     * The namespace of the application.
     * @var string
     */
    private string $namespace;
    private Model $modelInstance;
    private ModelBinderInterface $modelBinder;
    private Context $context;
    private array $config;
    private string $path;
    
    public function setup(ControllerSpec $controllerSpec, ModelBinderInterface $modelBinder, Context $context): void
    {
        $this->controllerSpec = $controllerSpec;
        $this->modelBinder = $modelBinder;
        $this->context = $context;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
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

    protected function getEntityDescription(): string
    {
        return ucfirst(str_replace('_', ' ', Text::deCamelize($this->getEntity())));
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
            $this->modelInstance = Model::load($this->getControllerSpec()->getControllerName());
        }
        return $this->modelInstance;
    }
    
    protected function setModelInstance(Model $modelInstance): void
    {
        $this->modelInstance = $modelInstance;
    }

    protected function getControllerPath(): string
    {
        return $this->getContext()->getPath($this->controllerSpec->getParameter('controller_path'));
    }
}
