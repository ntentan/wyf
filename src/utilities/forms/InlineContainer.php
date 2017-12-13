<?php

namespace ntentan\wyf\utilities\forms;


class InlineContainer extends Container
{
    public function __construct()
    {
        $this->addCssClass('elements-inline', self::CSS_CLASS_TARGET_WRAPPER);
    }
}