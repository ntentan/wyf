<?php
namespace ntentan\wyf;

use ntentan\mvc\binders\ViewBinder;

class WyfViewBinder extends ViewBinder
{
    public function bind(mixed $instance, string $name): mixed
    {
        $instance = parent::bind($instance, $name);
        $instance->setLayout('wyf_default.tpl.php');
        $className = strtolower($this->getControllerSpec()->getControllerName());
        $action = strtolower($this->getControllerSpec()->getControllerAction());
        $instance->setTemplate("wyf_{$className}_{$action}.tpl.php");
        return $instance;
    }
}

