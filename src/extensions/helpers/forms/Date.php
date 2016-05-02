<?php
namespace ntentan\extensions\wyf\helpers\forms;

use ntentan\views\template_engines\TemplateEngine;

class Date extends Element
{
    public function __construct($label = '', $name = '')
    {
        $this->label($label);
        $this->name($name);
    }
    
    public function __toString() 
    {
        return TemplateEngine::render(
            'wyf_inputs_forms_date.tpl.php', 
            $this->getTemplateVariables()
        );
    }
}