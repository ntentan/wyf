<?php
namespace ntentan\plugins\wyf\lib\modules\dashboard;

use ntentan\plugins\wyf\lib\WyfController;

class DashboardControllerBase extends WyfController
{
    public function init()
    {
        parent::init();
        $this->addPermission('can_access_dashboard', "Can log into dashboard");
    }
    
    public function run()
    {
        
    }
    
    public function package($package)
    {
        $subPaths = array_keys($_SESSION['menu']['sub'][$package]);
        \ntentan\Ntentan::redirect("$package/{$subPaths[0]}");
    }
}
