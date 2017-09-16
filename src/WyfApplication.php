<?php

namespace ntentan\wyf;

use ntentan\honam\TemplateEngine;
use ntentan\middleware\MvcMiddleware;
use ntentan\middleware\AuthMiddleware;
use ntentan\Application;
use ntentan\wyf\utilities\forms\Element;
use ntentan\Context;

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class WyfApplication extends Application
{

    protected function setName($name)
    {
        $context = Context::getInstance()->setParameter('wyf.app_name', $name);
    }

    protected function getMenu()
    {
        return [];
    }

    protected function setup(): void
    {
        $context = Context::getInstance();
        $context->setParameter('wyf.menu', $this->getMenu());
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/layouts'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/shared'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/forms'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/menus'));
        Element::setSharedFormData('base_api_url', $context->getUrl('api'));

        $this->router->mapRoute(
            'wyf_auth', 'auth/{action}', ['default' => ['controller' => controllers\AuthController::class]]
        );
        $this->router->mapRoute(
            'wyf_api', 'api/{*path}', [
            'default' => ['controller' => controllers\ApiController::class, 'action' => 'rest'],
            'pipeline' => [
                [AuthMiddleware::class, [
                    'auth_method' => 'http_basic',
                    'users_model' => 'auth.users']
                ],
                [MvcMiddleware::class]
            ]
            ]
        );

        foreach ($this->getMenu() as $item) {
            if (count($item['children'] ?? []) > 0) {
                $this->router->mapRoute("wyf_{$item['route']}", $item['route'], [
                    'default' => ['wyf_controller' => "{$item['route']}.{$item['children'][0]['route']}"]
                    ]
                );
            }
        }

        $this->router->mapRoute(
            'default', '{*wyf_controller}', ['default' => ['wyf_controller' => 'dashboard']]
        );
    }

}
