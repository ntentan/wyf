<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class RadioGroup extends Element
{
    private $options = array();
    
    public function option($label, $value, $parameters = array())
    {
        $this->options[] = array(
            'label' => $label,
            'value' => $value,
            'description' => $parameters['description'],
            'attributes' => $parameters['attributes']
        );
        return $this;
    }
    
    public function __toString() 
    {
        unset($this->attributes['value']);
        $this->set('options', $this->options);
        return parent::__toString();
    }
}
