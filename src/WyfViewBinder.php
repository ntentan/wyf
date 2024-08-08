<?php
namespace ntentan\wyf;

use ntentan\mvc\binders\ViewBinder;

class WyfViewBinder extends ViewBinder
{
    public function bind(array $data) 
    {
        $instance = parent::bind($data);
        $instance->setLayout('wyf_default.tpl.php');
        $className = strtolower($data["route"]["controller"]);
        $action = strtolower($data["route"]["action"]);
        $instance->setTemplate("wyf_{$className}_{$action}.tpl.php");
        return $instance;
    }
}

