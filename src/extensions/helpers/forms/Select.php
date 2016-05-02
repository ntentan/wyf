<?php
namespace ntentan\extensions\wyf\helpers\forms;

class Select extends Element
{
    private $options = array();
    
    public function option($label, $value)
    {
        $this->options[$value] = $label;
        return $this;
    }
    
    public function __toString() 
    {
        $this->set('options', $this->options);
        return parent::__toString();
    }
}
