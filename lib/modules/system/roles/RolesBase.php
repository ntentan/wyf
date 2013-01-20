<?php
namespace ntentan\plugins\wyf\lib\modules\system\roles;

use ntentan\models\Model;

class RolesBase extends Model
{
    public function __toString()
    {
        return $this->name;
    }
}
