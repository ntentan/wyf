<?php

namespace ntentan\wyf;

use ntentan\panie\InjectionContainer;
use ntentan\nibii\interfaces\ModelClassResolverInterface;
use ntentan\nibii\interfaces\TableNameResolverInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
use ntentan\honam\TemplateEngine;
use ntentan\honam\AssetsLoader;
use ntentan\View;
use ntentan\Ntentan;

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class Wyf
{
    public static function init($parameters = [])
    {        
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/layouts'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views'));
        AssetsLoader::appendSourceDir(realpath(__DIR__ . '/../assets'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/shared'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/forms'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/menus'));
        
        View::set('wyf_app_name', $parameters['name'] ?? 'WYF Application');
        
        InjectionContainer::bind(ModelClassResolverInterface::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(ControllerClassResolverInterface::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(TableNameResolverInterface::class)->to(ClassNameResolver::class);
        
        $router = Ntentan::getRouter();
        $router->registerLoader('wyf_controller', WyfLoader::class);
        $router->mapRoute(
            'wyf_auth', 'auth/{action}', 
            ['default' => ['controller' => controllers\AuthController::class]]
        );
        $router->mapRoute(
            'wyf_api', 'api/{*path}', 
            ['default' => ['controller' => controllers\ApiController::class, 'action' => 'rest']]
        );
        $router->mapRoute(
            'default', '{*wyf_controller}', 
            ['default' => ['wyf_controller' => 'dashboard']]
        );
    }
}
