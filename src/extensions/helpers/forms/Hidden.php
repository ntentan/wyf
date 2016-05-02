<?php
namespace ntentan\extensions\wyf\helpers\forms;

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