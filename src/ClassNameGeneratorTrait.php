<?php

namespace ntentan\wyf;

use ntentan\utils\Text;
use ntentan\Context;

/**
 * Generates wyf class name from dot separated wyf package names.
 *
 * @author ekow
 */
trait ClassNameGeneratorTrait
{
    /**
     * Generates a class name when presented with a dot separated wyf path and an associated class type.
     *
     * @param string $wyfPath
     * @param string $type
     * @return string
     */
    protected function getWyfClassName($wyfPath, $type) 
    {
        $wyfPathParts = explode('.', $wyfPath);
        $name = Text::ucamelize(array_pop($wyfPathParts));
        $base = (count($wyfPathParts) ? '\\' : '') . implode('\\', $wyfPathParts);
        $namespace = Context::getInstance()->getNamespace();
        return "\\$namespace\\app$base\\{$type}\\{$name}";
    }
}
