<?php

namespace ntentan\wyf\controllers;

/**
 * Base controller for all WYF application modules you want to appear in the menu.
 */
class WyfController
{
    private array $controllerSpec;
    
    public function setControllerSpec(array $controllerSpec): void
    {
        $this->controllerSpec = $controllerSpec;
    }
    
    protected function getControllerSpec(): array
    {
        return $this->controllerSpec;
    }
}
