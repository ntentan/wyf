<?php
namespace ntentan\wyf\utilities\forms;

class TextField extends Element
{
    private $multiline;
    
    public function setMultiline($multiline)
    {
        $this->multiline = $multiline;
        $this->set('multiline', $multiline);
        return $this;
    }
}