<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf;

use ntentan\panie\InjectionContainer;
use ntentan\nibii\interfaces\ModelClassResolverInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
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
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views/layouts'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../views'));
        AssetsLoader::appendSourceDir(realpath(__DIR__ . '/../assets'));
        
        View::set('wyf_app_name', $parameters['short_name'] ?? 'WYF Application');
        
        InjectionContainer::bind(ModelClassResolverInterface::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(ControllerClassResolverInterface::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(RouterInterface::class)->to(WyfRouter::class);
    }
}
