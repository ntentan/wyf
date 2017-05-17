<?php

namespace ntentan\wyf\utilities\forms;

class TextField extends Input {

    private $multiline;
    private $masked;

    public function setMultiline($multiline) {
        $this->multiline = $multiline;
        $this->setTemplateVariable('multiline', $multiline);
        return $this;
    }

    public function setMasked($masked) {
        $this->masked = $masked;
        $this->setTemplateVariable('masked', $masked);
        return $this;
    }

}
