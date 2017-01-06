<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;

class DateField extends Element
{
    public function __construct($label = '', $name = '')
    {
        $this->setLabel($label);
        $this->setName($name);
    }
    
    public function __toString() 
    {
        return TemplateEngine::render(
            'wyf_inputs_forms_date.tpl.php', 
            $this->getTemplateVariables()
        );
    }
}