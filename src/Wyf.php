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

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class Wyf
{
    public static function init($parameters = [])
    {
        Router::mapRoute(
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
        );
        
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/layouts'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views'));
        AssetsLoader::appendSourceDir(realpath(__DIR__ . '/../assets'));
        
        View::set('wyf_app_name', $parameters['short_name']);
        
        InjectionContainer::bind(ModelClassResolver::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(ControllerClassResolver::class)->to(ClassNameResolver::class);
    }
    
    private static function getController($routeArray, $basePath, $namespace, $controllerPath = "")
    {
        $path = array_shift($routeArray);
        $controllerClass = Text::ucamelize($path) . 'Controller';
        $controllerFile = "$basePath/controllers/{$controllerClass}.php";
        
        if($path == "" && !empty($routeArray)) {
            return self::getController($routeArray, $basePath, $namespace, $controllerPath);
        } else if(is_dir("$basePath/$path") && !empty($routeArray)) {
            // enter directories to find nested controllers
            return self::getController($routeArray, "$basePath/$path", "$namespace\\$path", "$controllerPath/$path");
        } else if(file_exists($controllerFile)) {
            // return controller info
            return [
                'controller' => "$namespace\\controllers\\$controllerClass",
                'action' => array_shift($routeArray),
                'id' => implode('/', $routeArray),
                'controller_path' => substr("$controllerPath/$path", 1)
            ];
        } else {
            return false;
        }
    }
}
