<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Html extends Element
{
    public function __construct($html)
    {
        $this->set('html', $html);
    }
}