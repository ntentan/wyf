<?php

namespace ntentan\wyf;

use ntentan\MvcModelFactory;
use ntentan\Context;
use ntentan\utils\Text;

class WyfModelFactory extends MvcModelFactory
{
    public function getModelTable($instance)
    {
        $class = (new \ReflectionClass($instance))->getName();
        $namespace = addslashes(Context::getInstance()->getNamespace());
        preg_match("|{$namespace}\\\\app\\\\(?<base>[a-zA-Z0-9_]+)\\\\.*models\\\\(?<model>[a-zA-Z0-9]+)|", $class, $matches);
        $schema = Text::deCamelize($matches['base'] ?? '');
        $table = Text::deCamelize($matches['model']);
        return ['schema' => $schema, 'table' => $table];
    }
}