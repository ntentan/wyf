<?php
namespace ntentan\plugins\wyf\lib\modules\system\roles;

use ntentan\models\Model;

class RolesBase extends Model
{
    public function __toString()
    {
        return $this->name;
    }
    
    public function getPermission($permission)
    {
        $permission = Model::load('system.permissions')->getFirst(
            array(
                'conditions' => array(
                    'role_id' => $this->id,
                    'permission' => $permission
                )
            )
        );
        if($permission->count() == 0)
        {
            return false;
        }
        else
        {
            return $permission->access;
        }
    }
}
