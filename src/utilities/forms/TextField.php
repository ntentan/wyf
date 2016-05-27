<?php
namespace ntentan\extensions\wyf\helpers\forms;

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