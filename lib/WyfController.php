<?php
namespace ntentan\plugins\wyf\lib;

use ntentan\controllers\Controller;

class WyfController extends Controller
{
    private $permissions = array();
    
    public function getDescription() 
    {
        
    }
    
    public function addPermission($code, $description)
    {
        $this->permissions[$code] = $description;
    }
    
    public function getPermissions() 
    {
        return $this->permissions;
    }
}
