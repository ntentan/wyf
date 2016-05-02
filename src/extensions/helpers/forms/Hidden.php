<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Hidden extends Element
{
    public function __construct($name) 
    {
        $this->name($name);
    }
    
    public function label($label = false)
    {
        
    }
}