<?php
namespace ntentan\extensions\wyf\helpers\forms;

use ntentan\honam\TemplateEngine;
use ntentan\Ntentan;

class Form extends Container
{
    private $submitValue = 'Save';
    
    public function __construct()
    {
        $this->setAttribute('method', 'post');
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
    
    public function setErrors($errors = false)
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
                $element->setData($this->data);
                $element->setErrors($this->errors);
            }
            else
            {
                $name = $element->getName();
                $element->setData($this->data[$name]);
                $element->setErrors($this->errors[$name]);
            }
        }        
        
        return TemplateEngine::render(
            'wyf_forms_form', 
            $this->getTemplateVariables()
        );
    }
}
