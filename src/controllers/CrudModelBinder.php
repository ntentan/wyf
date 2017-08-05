<?php

namespace ntentan\wyf\controllers;

use ntentan\Controller;
use ntentan\panie\Container;
use ntentan\nibii\interfaces\ModelClassResolverInterface;
use ntentan\controllers\ModelBinderInterface;

/**
 * Description of CrudModelBinder
 *
 * @author ekow
 */
class CrudModelBinder implements ModelBinderInterface
{
    private $wraped;
    private $container;
    
    public function __construct(Container $container)
    {
        $this->wraped = $container->resolve(WrappedModelBinder::class);
        var_dump($this->wraped);
        $this->container = $container;
    }
    
    public function bind(Controller $controller, $action, $type, $name)
    {
        if ($type == 'ntentan\Model') {
            $type = $this->container->singleton(ModelClassResolverInterface::class)
                    ->getModelClassName($controller->getWyfPackage(), null);
        }
        return $this->wraped->bind($controller, $action, $type, $name);
    }

    public function getBound()
    {
        return $this->wraped->getBound();
    }
}
