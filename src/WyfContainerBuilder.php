<?php

namespace ntentan\wyf;

use ntentan\Application;
use ntentan\ContainerBuilder;
use ntentan\interfaces\ControllerFactoryInterface;
use ntentan\nibii\interfaces\ModelFactoryInterface;
use ntentan\View;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

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
            KeyValueStoreInterface::class => DatabaseKeyValueStore::class,
            Application::class => ['calls' => ['initializeKeyValueStore']],
            ControllerFactoryInterface::class => WyfControllerFactory::class,
            ModelFactoryInterface::class => WyfModelFactory::class,
            View::class => function() {
                $context = Context::getInstance();
                $view = new View();
                $view->set([
                    'css_url' => $context->getUrl('/public/css/bundle.css'),
                    'js_url' => $context->getUrl('/public/js/bundle.js'),
                    'images_url' => $context->getUrl('/public/images'),
                    'user' => Session::get('user'),
                    'user' => Session::get('user'),
                    'prefix' => $context->getPrefix(),
                    'hide_submenu' => false
                ]);
            }
        ]);
        return $container;
    }
}
