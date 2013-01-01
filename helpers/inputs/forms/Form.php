<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class Form extends Container
{
    public function __construct()
    {
        $this->attribute('method', 'post');
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
                
                default:
                    throw new \Exception("Unknown datatype {$field['type']}");
            }
            
            $this->add($element);
        }
        
        return $this;
    }    
    
    public function __toString() 
    {
        return TemplateEngine::render(
            'wyf_input_forms_form.tpl.php', 
            $this->getTemplateVariables()
        );
    }
}
