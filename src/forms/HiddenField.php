<?php

namespace ntentan\wyf\forms;

class HiddenField extends Input
{
    public function __construct($name)
    {
        parent::__construct($name);
    }

    #[\Override]
    public function setLabel($label = false): Element
    {
        $this->label = '';
        return $this;
    }
}
