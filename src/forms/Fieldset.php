<?php

namespace ntentan\wyf\forms;

class Fieldset extends Container
{
    public function __construct($legend = '')
    {
        $this->set('legend', $legend);
    }
}
