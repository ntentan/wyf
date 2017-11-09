<?php

namespace ntentan\wyf\utilities\forms;

class HiddenField extends Input
{

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function setLabel($label = false)
    {

    }

}
