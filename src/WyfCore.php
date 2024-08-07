<?php
namespace ntentan\wyf;

use ntentan\mvc\View;
use ntentan\mvc\binders\DefaultModelBinder;
use ntentan\mvc\binders\ModelBinderRegistry;
use ntentan\panie\Container;

class WyfCore
{
    public static function getWiring(): array
    {
        return [
            ModelBinderRegistry::class => [
                function(Container $container) {
                    // Register model binders
                    $registry = new ModelBinderRegistry();
                    $registry->setDefaultBinderClass(DefaultModelBinder::class);
                    $registry->register(View::class, WyfViewBinder::class);
                    return $registry;
                },
                'singleton' => true
            ],
        ];
    }
}

