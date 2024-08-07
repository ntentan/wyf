<?php

namespace ntentan\wyf\controllers;

/**
 * Base controller for all WYF application modules you want to appear in the menu.
 */
class WyfController
{
    protected string $label;
    
    public function getLabel(): string
    {
        return $this->label;
    }
}
