<?php
namespace ntentan\plugins\wyf\helpers\inputs;

use ntentan\Ntentan;
use ntentan\plugins\wyf\helpers\inputs\forms\Form;
use ntentan\views\template_engines\TemplateEngine;

class InputsHelper extends \ntentan\views\helpers\Helper
{
    public function __construct() 
    {
        TemplateEngine::appendPath(p('wyf/helpers/inputs/views'));
        TemplateEngine::appendPath(p('wyf/helpers/inputs/views/layouts'));
    }
    
    public function help($fields)
    {
        $form = new Form();
        foreach($fields as $field)
        {
            if($field['primary_key'] === true) continue;
            
            switch($field['type'])
            {
                case 'string':
                    $element = new forms\Text(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );
                    break;
                
                case 'datetime':
                    $element = new forms\Date(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );                    
                    break;
                
                case 'boolean':
                    $element = new forms\Checkbox(
                        Ntentan::toSentence($field['name']), 
                        $field['name']
                    );                     
                    break;
                
                default:
                    throw new \Exception("Unknown datatype {$field['type']}");
            }
            
            $form->add($element);
        }
        
        return $form;
    }
}
