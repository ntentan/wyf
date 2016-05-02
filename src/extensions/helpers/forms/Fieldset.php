<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Fieldset extends Container
{    
    public function __construct($legend = '') 
    {
        $this->set('legend', $legend);
    }
}
