<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class RadioGroup extends Element
{
    private $options = array();
    
    public function option($label, $value, $description = null)
    {
        $this->options[] = array(
            'label' => $label,
            'value' => $value,
            'description' => $description
        );
        return $this;
    }
    
    public function __toString() 
    {
        $this->set('options', $this->options);
        return parent::__toString();
    }
    
    public function label($label = false)
    {
        if($label == false)
        {
            return null;
        }
        else
        {
            $this->label = $label;
        }
    }
}
