<?php

namespace ntentan\wyf;

use ntentan\nibii\interfaces\ModelFactoryInterface;
use ntentan\Context;
use ntentan\utils\Text;
use ntentan\nibii\Relationship;

class WyfModelFactory implements ModelFactoryInterface
{

    use ClassNameGeneratorTrait;
    
    public function getClassName($model)
    {
        return $this->getWyfClassName($model, 'models');
    }

    /**
     * Creates an instance of a model presented as a dot separated string.
     *  
     * @param type $model
     * @param type $context
     * @return type
     */
    public function createModel($model, $context)
    {
        if ($context == Relationship::BELONGS_TO) {
            $model = Text::pluralize($model);
        }
        $class = $this->getWyfClassName($model, 'models');
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

    public function getJunctionClassName($classA, $classB)
    {
        $classBParts = explode('\\', substr(nibii\Nibii::getClassName($classB), 1));
        $classAParts = explode('\\', $classA);
        $joinerParts = [];

        foreach ($classAParts as $i => $part) {
            if ($part == $classBParts[$i]) {
                $joinerParts[] = $part;
            } else {
                break;
            }
        }

        $class = [end($classAParts), end($classBParts)];
        sort($class);
        $joinerParts[] = implode('', $class);

        return implode('\\', $joinerParts);
    }    
}
