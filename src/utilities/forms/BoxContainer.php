<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;

class BoxContainer extends Container {
    
    public function __toString() {
        return TemplateEngine::render(
            'wyf_forms_box_container', $this->getTemplateVariables()
        );
    }
}
