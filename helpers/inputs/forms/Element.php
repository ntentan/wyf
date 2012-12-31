<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

abstract class Element
{
    protected $label;
    protected $name;
    protected $attributes = array();
    
    abstract public function __toString();
    
    public function attribute($attribute, $value = false)
    {
        if($value !== false)
        {
            $this->attributes[$attribute] = $value;
        }
        else
        {
            return $this->attributes[$attribute];
        }
    }
    
    public function value($value = false)
    {
        if($value === false)
        {
            return $this->value;
        }
        else
        {
            $this->value = $value;
            $this->attribute('value', $value);
        }
    }
    
    public function label($label = false)
    {
        if($label === false)
            return $this->label;
        else
            $this->label = $label;
    }
    
    public function name($name = false)
    {
        if($name === false)
        {
            return $this->name;
        }
        else
        {
            $this->name = $name;
            $this->attribute('name', $name);
        }
    }
    
    public function renderAttributes()
    {
        foreach($this->attributes as $attribute => $value)
        {
            $return .= "$attribute = '$value' ";
        }
        return $return;
    }
    
    public function getTemplateVariables()
    {
        return array(
            'label' => $this->label,
            'attributes' => $this->renderAttributes()
        );
    }
}
