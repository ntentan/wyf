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

/**
 * Description of newPHPClass
 *
 * @author ekow
 */
class Wyf
{
    public static function init()
    {
        InjectionContainer::bind(ModelClassResolver::class)->to(ClassNameResolver::class);
        InjectionContainer::bind(ControllerClassResolver::class)->to(ClassNameResolver::class);
    }
}
