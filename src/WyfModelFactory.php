<?php

namespace ntentan\wyf;

use ntentan\nibii\interfaces\ModelFactoryInterface;
use ntentan\Context;
use ntentan\utils\Text;
use ntentan\nibii\Relationship;

class WyfModelFactory implements ModelFactoryInterface
{

    use ClassNameGeneratorTrait;

    /**
     * Return the class name for a model presented as a string. 
     * @param type $model
     * @param type $context
     * @return type
     */
    public function createModel($model, $context)
    {
        if ($context == Relationship::BELONGS_TO) {
            $model = Text::pluralize($model);
        }
        $class = $this->getClassName($model, 'models');
        return new $class();
    }

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
