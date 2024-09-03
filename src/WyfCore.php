<?php
namespace ntentan\wyf;

use ntentan\Context;
use ntentan\mvc\Router;
use ntentan\mvc\ServiceContainerBuilder;
use ntentan\mvc\View;
use ntentan\mvc\binders\DefaultModelBinder;
use ntentan\mvc\binders\ModelBinderRegistry;
use ntentan\panie\Container;
use ntentan\mvc\MvcCore;

class WyfCore
{
    public static function configure(Container $container, string $namespace): array 
    {
        return [
            WyfMiddleware::class => [
                function(Container $container) use ($namespace) {
                    $instance = new WyfMiddleware(
                        $container->get(Router::class),
                        $container->get(ServiceContainerBuilder::class),
                        $container->get(Context::class)
                        );
                    $instance->setNamespace($namespace);
                    MvcCore::initializeDatabase();
                    return $instance;
                },
                'singleton' => true
            ],
            WyfClassNameGenerator::class => [DefaultClassNameGenerator::class, 'singleton' => true],
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

