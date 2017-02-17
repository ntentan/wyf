<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;
use ntentan\Ntentan;
use ntentan\utils\Text;

class Element
{
    protected $label;
    protected $name;
    protected $attributes = array();
    protected $data;
    protected $errors;
    protected $variables = array();
    protected $renderWithType;
    protected $description;
    
    public function __construct($name = '', $label = null)
    {
        $this->setLabel($label == null ? ucfirst(str_replace('_', ' ', $name)) : $label);
        $this->setName($name);
    }
    
    public function __toString() 
    {
        $type = $this->renderWithType == '' ? \ntentan\utils\Text::deCamelize($this->getType()) : $this->renderWithType;
        
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
    
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }
    
    public function getData($value = false)
    {
        return $this->data;
    }
    
    public function setData($data = false)
    {
        if($this->data == '' && $data != '')
        {
            $this->data = $data;
            $this->setAttribute('value', $data);
            $this->set('field_value', $data); 
        }
        return $this;
    }
    
    public function setErrors($errors)
    {
        $this->errors = $errors;        
        return $this;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function setLabel($label = false)
    {
        $this->label = $label;
        return $this;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setDescription($description = false)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setName($name = false)
    {
        $this->name = $name;
        $this->setAttribute('name', $name);
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    private function renderAttributes()
    {
        $return = '';
        foreach($this->attributes as $attribute => $value)
        {
            if($value == '') continue;
            $return .= sprintf('%s = "%s" ', $attribute, htmlentities($value));
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
                'extra_css_classes' => count($this->getErrors()) > 0 ? 'form-error' : '',
                'value' => $this->data
            )
        );
    }
    
    protected function set($key, $value)
    {
        $this->variables[$key] = $value;
    }
    
    public static function create()
    {
        $args = func_get_args();
        $type = array_shift($args);
        $typeClass = new \ReflectionClass(
            'ntentan\\wyf\\utilities\\forms\\' . 
            Text::ucamelize($type)
        );
        return $typeClass->newInstanceArgs($args);
    }
}
