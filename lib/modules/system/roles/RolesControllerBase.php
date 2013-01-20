<?php
namespace ntentan\plugins\wyf\lib\modules\system\roles;

use ntentan\plugins\wyf\lib\WyfController;
use ntentan\Ntentan;
use ntentan\controllers\Controller;
use ntentan\views\template_engines\TemplateEngine;

class RolesControllerBase extends WyfController
{
    public function init()
    {
        $this->addComponent('wyf.model_controller');
        $wyf = $this->wyfModelControllerComponent;
        $wyf->addOperation('Set Permissions', 'set_permissions');
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/system_module'));       
    }
    
    public function setPermissions()
    {
        $arguments = func_get_args();
        $id = array_shift($arguments);
        $role = $this->model->getFirstWithId($id);
        $permissionItems = array();
        
        $baseRoute = implode('/', $arguments) . (count($arguments) > 0 ? '/' : '');
        
        $baseDirectory = Ntentan::$namespace . "/modules/$baseRoute";
        $dir = dir($baseDirectory);
        
        while (false !== ($entry = $dir->read())) 
        {
            if($entry == '.' || $entry == '..') continue;
            $path = getcwd() . "/{$baseDirectory}{$entry}";
            $class = Ntentan::camelize($entry) . 'Controller';
            if(file_exists("$path/$class.php"))
            {
                $controller = Controller::load("{$baseRoute}{$entry}", true);
                
                if(is_a($controller, "\\ntentan\\plugins\\wyf\\lib\\WyfController"))
                {
                    $permissionItems[] = array(
                        'type' => 'permission',
                        'label' => Ntentan::toSentence($entry),
                        'permissions' => $controller->getPermissions()
                    );
                }
                
                continue;
            }
            
            $class = Ntentan::camelize($entry);
            if(file_exists("$path/$class.php")) continue;
            
            if(is_dir($path))
            {
                $permissionItems[] = array(
                    'type' => 'link',
                    'label' => Ntentan::toSentence($entry),
                    'link' => Ntentan::getUrl("{$this->route}/set_permissions/{$id}/$entry")
                );
            }
        }
        
        $this->set('permission_items', $permissionItems);
        $this->set('role', (string)$role);
    }
}
