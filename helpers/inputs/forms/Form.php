<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

use ntentan\views\template_engines\TemplateEngine;

class Form extends Container
{
    public function __construct()
    {
        $this->attribute('method', 'post');
    }
    
    public function __toString() 
    {
        return TemplateEngine::render(
            'wyf_input_forms_form.tpl.php', 
            $this->getTemplateVariables()
        );
    }
}
