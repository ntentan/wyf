<?php
namespace ntentan\extensions\wyf\helpers\forms;

class Columns extends Container
{
    public function __construct($numColumns)
    {
        $this->set('num_columns', $numColumns);
    }
}