<?php

namespace ntentan\wyf;

use ntentan\ContainerBuilder;
use ntentan\interfaces\ControllerFactoryInterface;
use ntentan\nibii\interfaces\ModelFactoryInterface;

/**
 * Augments the ntentan container with WYF specific bindings.
 *
 * @author ekow
 */
class WyfContainerBuilder extends ContainerBuilder
{
    public function getContainer()
    {
        $container = parent::getContainer();
        $container->setup([
            ControllerFactoryInterface::class => WyfControllerFactory::class,
            ModelFactoryInterface::class => WyfModelFactory::class
        ]);
        return $container;
    }
}
