<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Text extends Element
{
    private  $multiline;
    
    public function multiline($multiline)
    {
        $this->multiline = $multiline;
        $this->set('multiline', true);
    }
}