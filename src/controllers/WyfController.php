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
abstract class WyfController
{
    /**
     * An instance of the controller spec with which this controller built.
     * @var ControllerSpec
     */
    private ControllerSpec $controllerSpec;

    /**
     * An instance of the default model binder for injecting models.
     * @var ModelBinderInterface
     */
    private ModelBinderInterface $modelBinder;

    /**
     * An instance of the context with which ntentan was running.
     * @var Context
     */
    private Context $context;

    /**
     * An instance of the configuration with which ntentan was running.
     * @var array
     */
    private array $config;

    /**
     * The namespace of the application.
     * @var string
     */
    protected string $namespace {
        get {
            if (!isset($this->namespace)) {
                $items = explode("\\", $this->controllerSpec->getControllerClass());
                array_pop($items);
                $this->namespace = implode('\\', $items);
            }
            return $this->namespace;
        }
    }

    protected string $modelClass {
        get {
            if (!isset($this->modelClass)) {
                $this->modelClass = "{$this->namespace}\\" . Text::ucamelize(Text::singularize($this->controllerSpec->getControllerName()));
            }
            return $this->modelClass;
        }
    }

    public array $breadCrumbTrail {
        get {
            return $this->breadCrumbTrail ??= $this->controllerSpec->getParameter('breadcrumb_trail');
        }
    }

    public array $stylesheets {get => [];}
    
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
    
    protected function setModelInstance(Model $modelInstance): void
    {
        $this->modelInstance = $modelInstance;
    }

    protected function getControllerPath(): string
    {
        return $this->getContext()->getPath($this->controllerSpec->getParameter('controller_path'));
    }

    abstract public function getLabel(): string;
}
