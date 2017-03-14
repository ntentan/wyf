<?php

namespace ntentan\wyf\utilities\forms;

class RadioGroup extends Element {

    private $options = array();

    public function addOption($label, $value, $parameters = array()) {
        $this->options[] = array(
            'label' => $label,
            'value' => $value,
            'description' => $parameters['description'],
            'attributes' => $parameters['attributes'],
            'checked' => $parameters['checked']
        );
        return $this;
    }

    public function __toString() {
        unset($this->attributes['value']);
        $this->set('options', $this->options);
        return parent::__toString();
    }

}
