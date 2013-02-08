<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class Element
{
    protected $label;
    protected $name;
    protected $attributes = array();
    protected $data;
    protected $errors;
    protected $variables = array();
    protected $renderWithType;
    
    public function __construct($label = '', $name = '')
    {
        $this->label($label);
        $this->name($name);
    }
    
    public function __toString() 
    {
        $type = $this->renderWithType == '' ? Ntentan::deCamelize($this->getType()) : $this->renderWithType;
        
        return TemplateEngine::render(
            "wyf_inputs_forms_{$type}.tpl.php", 
            $this->getTemplateVariables()
        );
    }
    
    public function getType()
    {
        $class = new \ReflectionClass($this);
        $array = explode('\\', $class->getName());
        return end($array);
    }
    
    public function attribute($attribute, $value = false)
    {
        if($value !== false)
        {
            $this->attributes[$attribute] = $value;
            return $this;
        }
        else
        {
            return $this->attributes[$attribute];
        }
    }
    
    public function value($value = false)
    {
        return $this->data($value);
    }
    
    public function data($data = false)
    {
        if($data === false)
        {
            return $this->data;
        }
        else
        {
            if($this->data == '' && $data != '')
            {
                $this->data = $data;
                $this->attribute('value', $data);
                $this->set('field_value', $data); 
            }
            return $this;
        }
    }
    
    public function errors($errors = false)
    {
        if($errors === false)
            return $this->errors;
        else
        {
            $this->errors = $errors;        
        }
    }
    
    public function label($label = false)
    {
        if($label === false)
        {
            return $this->label;
        }
        else
        {
            $this->label = $label;
            return $this;
        }
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
            return $this;
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
        return array_merge(
            $this->variables,
            array(
                'label' => $this->label,
                'name' => $this->name,
                'attributes' => $this->renderAttributes(),
                'extra_css_classes' => count($this->errors()) > 0 ? 'form_error' : '',
                'value' => $this->data
            )
        );
    }
    
    protected function set($key, $value)
    {
        $this->variables[$key] = $value;
    }
    
    public function create()
    {
        $args = func_get_args();
        $type = array_shift($args);
        $typeClass = new \ReflectionClass(
            'ntentan\\plugins\\wyf\\helpers\\inputs\forms\\' . 
            \ntentan\Ntentan::camelize($type)
        );
        return $typeClass->newInstanceArgs($args);
    }
}
