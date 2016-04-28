<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Columns extends Container
{
    public function __construct($numColumns)
    {
        $this->set('num_columns', $numColumns);
    }
}