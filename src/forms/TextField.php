<?php
namespace ntentan\wyf\forms;

class TextField extends Input
{
    public function setMultiline($multiline)
    {
        $this->set('multiline', $multiline);
        return $this;
    }

    public function setMasked($masked)
    {
        $this->set('masked', $masked);
        return $this;
    }
}
