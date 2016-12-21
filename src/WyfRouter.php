<?php
namespace ntentan\wyf;

use ntentan\DefaultRouter;


class WyfRouter extends DefaultRouter
{   
    public function __construct()
    {
        $this->mapRoute(
            'wyf_auth', 'auth/{action}', 
            ['default' => ['controller' => controllers\AuthController::class]]
        );
        
        $this->mapRoute(
            'wyf_api', 'api/{*path}', 
            ['default' => ['controller' => controllers\ApiController::class, 'action' => 'rest']]
        );
    }
    
    public function execute($route) {
        return parent::execute($route);
    }
    
    private function getController($routeArray, $basePath, $namespace, $controllerPath = "")
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
