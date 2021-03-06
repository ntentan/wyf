<?php

namespace ntentan\wyf;

use ntentan\honam\TemplateEngine;
use ntentan\Application;
use ntentan\wyf\utilities\forms\Element;
use ntentan\Context;
use ntentan\wyf\controllers\AuthController;

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class WyfApplication extends Application
{

    protected function setName($name)
    {
        Context::getInstance()->setParameter('wyf.app_name', $name);
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

        $this->router->appendRoute(
            'wyf_auth', 'auth/{action}', ['default' => ['controller' => AuthController::class]]
        );
        $this->router->appendRoute(
            'wyf_api', 'api/{*path}', [
                'default' => ['controller' => controllers\ApiController::class, 'action' => 'rest']
            ]
        );

        foreach ($this->getMenu() as $item) {
            if (count($item['children'] ?? []) > 0) {
                $this->router->appendRoute("wyf_{$item['route']}", $item['route'], [
                    'default' => ['wyf_controller' => "{$item['route']}.{$item['children'][0]['route']}"]
                    ]
                );
            }
        }

        $this->router->appendRoute('default', '{*wyf_controller}', ['default' => ['wyf_controller' => 'dashboard']]);
    }

}
