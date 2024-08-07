<?php
namespace ntentan\wyf\forms;

class TextField extends Input
{
    private $multiline;
    private $masked;

    public function setMultiline($multiline)
    {
        $this->multiline = $multiline;
        $this->set('multiline', $multiline);
        return $this;
    }

    public function setMasked($masked)
    {
        $this->masked = $masked;
        $this->set('masked', $masked);
        return $this;
    }
}
