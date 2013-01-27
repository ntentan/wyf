<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

abstract class Container extends Element
{
    protected $elements = array();
    
    public function add($element)
    {
        $element->data($this->data[$element->name()]);
        $element->errors($this->errors[$element->name()]);
        $this->elements []= $element;
        return $this;
    }
    
    public function getTemplateVariables()
    {
        $variables = array(
            'elements' => $this->elements,
            'layout' => 'flowing'
        );
        
        return array_merge($variables, parent::getTemplateVariables());
    }
    
    public function data($data = false)
    {
        $this->data = $data;
        foreach($this->elements as $element)
        {
            $element->data($data[$element->name()]);
        }
    }
    
    public function errors($errors = false)
    {
        if($errors === false) return;
        $this->errors = $errors;
        foreach($this->elements as $element)
        {
            $element->errors($errors[$element->name()]);
        }
    }    
}
