<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf;

use ntentan\panie\InjectionContainer;
use ntentan\nibii\interfaces\ClassResolverInterface as ModelClassResolver;
use ntentan\controllers\interfaces\ClassResolverInterface as ControllerClassResolver;
use ntentan\Router;
use ntentan\honam\TemplateEngine;
use ntentan\honam\AssetsLoader;
use ntentan\View;
use ntentan\utils\Text;
use ntentan\controllers\ModelBinders;
use ntentan\Model;
use ntentan\controllers\DefaultModelBinder;
use ntentan\interfaces\RouterInterface;

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class Wyf
{
    public static function init($parameters = [])
    {
        /*Router::mapRoute(
            'wyf_auth', 'auth/{action}', 
            ['default' => ['controller' => controllers\AuthController::class]]
        );
        
        Router::mapRoute(
            'wyf_api', 'api/{*path}', 
            ['default' => ['controller' => controllers\ApiController::class, 'action' => 'rest']]
        );
        
        Router::mapRoute('wyf_main', 
            function($route){
                $routeArray = explode('/', $route);
                $routeDetails = self::getController(
                    $routeArray, 
                    realpath(__DIR__ . '/../../../../src/app/'),
                    \ntentan\Ntentan::getNamespace() . '\app'
                );
                return $routeDetails;
            },
            ['default' => ['action' => 'index']]
        );*/
        
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/layouts'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views'));
        AssetsLoader::appendSourceDir(realpath(__DIR__ . '/../assets'));
        
        View::set('wyf_app_name', $parameters['short_name'] ?? 'WYF Application');
        
        InjectionContainer::bind(ModelClassResolver::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(ControllerClassResolver::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(RouterInterface::class)->to(WyfRouter::class);
    }
}
