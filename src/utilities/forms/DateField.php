<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;

class DateField extends Input
{

    public function __toString()
    {
        $this->setAttribute('class', 'pikaday');
        return TemplateEngine::render('wyf_inputs_forms_date.tpl.php', $this->getTemplateVariables());
    }

}
