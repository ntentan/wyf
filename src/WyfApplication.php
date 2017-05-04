<?php

namespace ntentan\wyf;

use ntentan\nibii\interfaces\ModelClassResolverInterface;
use ntentan\nibii\interfaces\TableNameResolverInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
use ntentan\honam\TemplateEngine;
use ntentan\honam\AssetsLoader;
use ntentan\middleware\MVCMiddleware;
use ntentan\middleware\AuthMiddleware;

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class WyfApplication extends \ntentan\Application {

    private $appName;

    public static function getName() {
        return $this->appName;
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
        
        $container = $this->context->getContainer();
        $container->bind(MVCMiddleware::class)->to(MVCMiddleware::class)->asSingleton();
        $container->resolve(MVCMiddleware::class)->registerLoader('wyf_controller', WyfLoader::class);

        //self::$appName = $parameters['name'] ?? 'WYF Application';
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
            ['default' => 
                ['controller' => 
                    controllers\ApiController::class, 'action' => 'rest'
                ]
            ]
        );
        $router->mapRoute(
            'default', '{*wyf_controller}', ['default' => ['wyf_controller' => 'dashboard']]
        );
        
        $view = $container->resolve(\ntentan\View::class);
        $this->prependMiddleware(AuthMiddleware::with([
                'login_route' => 'auth/login',
                'users_model' => 'auth.users'
            ])
        );
    }

}
