<?php

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
    protected function getWyfClassName($wyfPath, $type) 
    {
        $wyfPathParts = explode('.', $wyfPath);
        $name = Text::ucamelize(array_pop($wyfPathParts));
        $base = (count($wyfPathParts) ? '\\' : '') . implode('\\', $wyfPathParts);
        $namespace = Context::getInstance()->getNamespace();
        return "\\$namespace\\app$base\\{$type}\\{$name}";
    }
}
