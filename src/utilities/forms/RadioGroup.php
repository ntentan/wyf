<?php

namespace ntentan\wyf\utilities\forms;

class RadioGroup extends Input {

    private $options = array();

    public function addOption($label, $value, $parameters = array()) {
        $this->options[] = array(
            'label' => $label,
            'value' => $value,
            'description' => $parameters['description'] ?? null,
            'attributes' => $parameters['attributes'] ?? null,
            'checked' => $parameters['checked'] ?? null
        );
        return $this;
    }

    public function __toString() {
        unset($this->attributes['value']);
        $this->setTemplateVariable('options', $this->options);
        return parent::__toString();
    }

}
