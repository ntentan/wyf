<?php
namespace ntentan\wyf\utilities\forms;

class Html extends Element
{
    public function __construct($html)
    {
        $this->set('html', $html);
    }
}