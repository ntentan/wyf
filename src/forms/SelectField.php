<?php

namespace ntentan\wyf\utilities\forms;

class SelectField extends Input
{

    protected $options = array();

    public function addOption($label, $value)
    {
        $this->options[$value] = $label;
        return $this;
    }

    public function __toString()
    {
        $this->set('options', $this->options);
        return parent::__toString();
    }

}
