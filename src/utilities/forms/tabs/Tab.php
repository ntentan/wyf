<?php
namespace ntentan\wyf\utilities\forms\tabs;

use ntentan\wyf\utilities\forms\Container;

class Tab extends Container
{   
    private $id;
    private $active;
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
}
