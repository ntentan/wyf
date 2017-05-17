<?php

namespace ntentan\wyf\utilities\forms;

class Fieldset extends Container {

    public function __construct($legend = '') {
        $this->setTemplateVariable('legend', $legend);
    }

}
