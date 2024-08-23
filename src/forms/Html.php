<?php

namespace ntentan\wyf\forms;

class Html extends Element
{

    public function __construct($html)
    {
        $this->set('html', $html);
    }

}
