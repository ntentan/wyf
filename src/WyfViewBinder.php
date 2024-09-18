<?php
namespace ntentan\wyf;

use ntentan\mvc\binders\ViewBinder;
use ntentan\utils\Text;

class WyfViewBinder extends ViewBinder
{
    public function bind(mixed $instance, string $name): mixed
    {
        $controlerSpec = $this->getControllerSpec();
        $instance = parent::bind($instance, $name);
        $instance->setLayout('wyf_default.tpl.php');
        $className = strtolower($controlerSpec->getControllerName());
        $action = strtolower($controlerSpec->getControllerAction());
        $instance->setTemplate("wyf_{$className}_{$action}.tpl.php");
        $instance->set('wyf_entity', Text::singularize($controlerSpec->getControllerName()));

        return $instance;
    }
}

