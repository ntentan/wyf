<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class Form extends Container
{
    private $submitValue = 'Save';
    
    public function __construct()
    {
        $this->attribute('method', 'post');
    }
    
    public function setSubmitValue($submitValue)
    {
        $this->submitValue = $submitValue;
    }
    
    public function setup($fields)
    {
        foreach($fields as $field)
        {
            if($field['primary_key'] === true) continue;
            
            switch($field['type'])
            {
                case 'string':
                    $element = new Text(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );
                    break;
                
                case 'datetime':
                    $element = new Date(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );                    
                    break;
                
                case 'boolean':
                    $element = new Checkbox(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );                     
                    break;
                
                case 'text':
                    $element = new Text(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );                    
                    $element->multiline(true);
                    break;
                
                case 'integer':
                case 'double':
                    $element = new Text(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );
                    break;
                                
                case 'date':
                    $element = new Date(
                        Ntentan::toSentence($field['name']),
                        $field['name']
                    );
                    break;
                                
                default:
                    throw new \Exception("Unknown datatype {$field['type']}");
            }
            
            $this->add($element);
        }
        
        return $this;
    }    
    
    public function getTemplateVariables() {
        return array_merge(
            parent::getTemplateVariables(),
            array('submit_value' => $this->submitValue)
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
            'wyf_input_forms_form.tpl.php', 
            $this->getTemplateVariables()
        );
    }
}
