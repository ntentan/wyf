<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;

class Checkbox extends Element
{
    public function __construct($label = '', $name = '')
    {
        $this->setLabel($label);
        $this->setName($name);
    }
    
    public function __toString() 
    {
        return TemplateEngine::render('wyf_inputs_forms_checkbox.tpl.php', $this->getTemplateVariables());
    }
}