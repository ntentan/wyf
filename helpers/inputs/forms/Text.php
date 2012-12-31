<?php

namespace ntentan\plugins\wyf\helpers\inputs\forms;

use ntentan\views\template_engines\TemplateEngine;

class Text extends Element
{
    public function __toString() 
    {
        TemplateEngine::render('wyf_inputs_forms_text.tpl.php', $this);
    }
}