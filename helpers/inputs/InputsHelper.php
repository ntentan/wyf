<?php
namespace ntentan\plugins\wyf\helpers\inputs;

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
            switch($field['type'])
            {
                case 'text':
                    $element = new forms\Text();
            }
            $form->add($element);
        }
        
        return $form;
    }
}
