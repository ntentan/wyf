<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf;

use ntentan\utils\Text;
use ntentan\Context;

/**
 * Description of ClassNameGeneratorTrait
 *
 * @author ekow
 */
trait ClassNameGeneratorTrait
{
    protected function getClassName($wyfPath, $type) 
    {
        $wyfPathParts = explode('.', $wyfPath);
        $name = Text::ucamelize(array_pop($wyfPathParts));
        $base = (count($wyfPathParts) ? '\\' : '') . implode('\\', $wyfPathParts);
        $namespace = Context::getInstance()->getNamespace();
        return "\\$namespace\\app$base\\{$type}\\{$name}";
    }
}
