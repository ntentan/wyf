<?php
namespace ntentan\plugins\wyf\lib\modules\system\users;

use ntentan\plugins\wyf\lib\WyfController;
use ntentan\Ntentan;
use ntentan\views\template_engines\TemplateEngine;

use pos\modules\system\user_roles\UserRoles;

class UsersControllerBase extends WyfController
{
    public function init()
    {
        $this->addComponent('wyf.model_controller');
        $wyf = $this->wyfModelControllerComponent;
        $wyf->listFields = array(
            'firstname',
            'lastname',
            'username'
        );
        $wyf->addOperation('Assign Roles', 'assign_roles');
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/system_module'));
    }
    
    public function assignRoles($id)
    {
        if(count($_POST) > 0)
        {
            $roles = UserRoles::getJustWithUserId($id);
            foreach($roles as $role)
            {
                $role->delete();
            }
            
            foreach ($_POST as $roleId)
            {
                $role = UserRoles::getNew();
                $role->user_id = $id;
                $role->role_id = $roleId;
                $role->save();
            }
            
            Ntentan::redirect(Ntentan::getUrl($this->route));
        }
        $item = $this->model->getJustFirstWithId($id);
        $roles = \pos\modules\system\roles\Roles::getAll();
        $assignedRoles = UserRoles::getJustWithUserId($id, array('fields'=>array('role_id')))->toArray();
        $structuredAssignedRoles = array();
        
        foreach($assignedRoles as $assignedRole)
        {
            $structuredAssignedRoles[$assignedRole['role_id']] = true;
        }
        
        $this->set('roles', $roles);
        $this->set('assigned_roles', $structuredAssignedRoles);
        $this->set('item', (string)$item);
    }
}
