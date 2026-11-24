<?php

namespace ntentan\wyf;

use ntentan\mvc\ControllerSpec;

class WyfControllerSpec extends ControllerSpec
{
    public array $breadCrumbTrail {
        get {
            return $this->breadCrumbTrail;
        }
        set {
            $this->breadCrumbTrail = $value;
        }
    }

    public array $stylesheets {
        get {
            return $this->stylesheets ?? [];
        }

        set {
            $this->stylesheets = $value;
        }
    }

}