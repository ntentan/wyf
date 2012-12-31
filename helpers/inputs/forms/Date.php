<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

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
        return TemplateEngine::render('wyf_inputs_forms_text.tpl.php', $this->getTemplateVariables());
    }
}