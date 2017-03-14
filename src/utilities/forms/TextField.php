<?php

namespace ntentan\wyf\utilities\forms;

class TextField extends Element {

    private $multiline;
    private $masked;

    public function setMultiline($multiline) {
        $this->multiline = $multiline;
        $this->set('multiline', $multiline);
        return $this;
    }

    public function setMasked($masked) {
        $this->masked = $masked;
        $this->set('masked', $masked);
        return $this;
    }

}
