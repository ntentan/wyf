<?php
namespace ntentan\plugins\wyf\lib\modules\system\users;

use ntentan\models\Model;

class UsersBase extends Model
{
    public function __toString()
    {
        return "{$this->firstname} {$this->lastname}";
    }
    
    public function preSaveCallback() 
    {
        $this->password = md5('password');
    }
}

