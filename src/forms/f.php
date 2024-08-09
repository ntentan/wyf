<?php
namespace ntentan\wyf\forms;

use ntentan\honam\Templates;
use ntentan\wyf\WyfException;

class f
{
    private static Templates $templates;
    
    public static function init(Templates $templates)
    {
        self::$templates = $templates;
    }
    
    public static function create(string $element, mixed ...$args): Element
    {
        $class = new \ReflectionClass(match($element) {
            'form' => Form::class,
            'text' => TextField::class,
            default => throw new WyfException("Unknown form item type $element")
        });
        $instance = $class->newInstanceArgs($args);
        $instance->setTemplateEngine(self::$templates);
        
        return $instance;
    }
}

