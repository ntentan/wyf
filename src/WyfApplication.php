<?php

namespace ntentan\wyf;

use ntentan\nibii\interfaces\ModelClassResolverInterface;
use ntentan\nibii\interfaces\TableNameResolverInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
use ntentan\honam\TemplateEngine;
use ntentan\honam\AssetsLoader;
use ntentan\middleware\MVCMiddleware;
use ntentan\middleware\AuthMiddleware;
use ntentan\Application;
use ntentan\wyf\utilities\forms\Element;

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class WyfApplication extends Application {

    private $appName;

    public function getName() {
        return $this->appName;
    }
    
    public function setName($name) {
        $this->appName = $name;
    }
    
    public function getMenu() {
        return [];
    }

    public function setup() {
        
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/layouts'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views'));
        AssetsLoader::appendSourceDir(realpath(__DIR__ . '/../assets'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/shared'));        
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/forms'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/menus'));
        Element::setSharedFormData('base_api_url', $this->context->getUrl('api'));
        
        $container = $this->context->getContainer();
        $container->bind(MVCMiddleware::class)->to(MVCMiddleware::class)->asSingleton();
        $container->resolve(MVCMiddleware::class)->registerLoader('wyf_controller', WyfLoader::class);

        $container->bind(ModelClassResolverInterface::class)->to(ClassNameResolver::class);
        $container->bind(ControllerClassResolverInterface::class)->to(ClassNameResolver::class);
        $container->bind(TableNameResolverInterface::class)->to(ClassNameResolver::class);

        $router = $this->context->getRouter();
        $router->mapRoute(
            'wyf_auth', 
            'auth/{action}', 
            ['default' => ['controller' => controllers\AuthController::class]]
        );
        $router->mapRoute(
            'wyf_api', 
            'api/{*path}', 
            [
                'default' => ['controller' => controllers\ApiController::class, 'action' => 'rest'],
                'pipeline' => [
                    [AuthMiddleware::class, [
                        'auth_method' => 'http_basic', 
                        'users_model' => 'auth.users']
                    ],
                    [MVCMiddleware::class]
                ]
            ]
        );
        
        foreach($this->getMenu() as $item) {
            if(count($item['children'] ?? []) > 0) {
                $router->mapRoute("wyf_{$item['route']}", $item['route'],
                    [
                        'default' => ['wyf_controller' => "{$item['route']}.{$item['children'][0]['route']}"]
                    ]
                ); 
            }
        }        
        
        $router->mapRoute(
            'default', '{*wyf_controller}', ['default' => ['wyf_controller' => 'dashboard']]
        );   
        
        $view = $container->resolve(\ntentan\View::class);
        $this->prependMiddleware(AuthMiddleware::class, [
            'login_route' => 'auth/login',
            'users_model' => 'auth.users'
        ]);
    }

}
