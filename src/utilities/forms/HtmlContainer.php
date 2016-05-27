<?php
namespace ntentan\extensions\wyf\helpers\forms;

class Html extends Element
{
    public function __construct($html)
    {
        $this->set('html', $html);
    }
}