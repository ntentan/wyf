<?php
namespace ntentan\extensions\wyf\helpers\forms;

class Text extends Element
{
    private  $multiline;
    
    public function multiline($multiline)
    {
        $this->multiline = $multiline;
        $this->set('multiline', $multiline);
        return $this;
    }
}