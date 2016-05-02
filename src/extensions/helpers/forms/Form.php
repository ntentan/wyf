<?php
namespace ntentan\extensions\wyf\helpers\forms;

use ntentan\honam\TemplateEngine;
use ntentan\Ntentan;

class Form extends Container
{
    private $submitValue = 'Save';
    private $ajax = false;
    
    public function __construct()
    {
        $this->attribute('method', 'post');
    }
    
    public function ajax($ajax)
    {
        if($ajax != '' || $ajax != false)
        {
            $this->ajax = $ajax;
        }
        else 
        {
            $this->ajax = false;            
        }
        return $this;            
    }
    
    public function setSubmitValue($submitValue)
    {
        $this->submitValue = $submitValue;
        return $this;
    }
    
    public function getTemplateVariables() {
        return array_merge(
            parent::getTemplateVariables(),
            array(
                'submit_value' => $this->submitValue,
                'ajax' => $this->ajax
            )
        );
    }
    
    public function errors($errors = false)
    {
        if($errors === false) return;
        $this->errors = $errors;
    }    
    
    public function __toString() 
    {
        foreach($this->elements as $element)
        {
            if(is_a($element, "\\ntentan\\plugins\\wyf\\helpers\\inputs\\forms\\Container"))
            {
                $element->data($this->data);
                $element->errors($this->errors);
            }
            else
            {
                $name = $element->name();
                $element->data($this->data[$name]);
                $element->errors($this->errors[$name]);
            }
        }        
        
        return TemplateEngine::render(
            'wyf_forms_form', 
            $this->getTemplateVariables()
        );
    }
}
