<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

abstract class Element
{
    abstract public function __toString();
    
    public function getTemplateVariables()
    {
        return array();
    }
}
