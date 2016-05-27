<?php
namespace ntentan\extensions\wyf\helpers\forms;

class ColumnsContainer extends Container
{
    public function __construct($numColumns)
    {
        $this->set('num_columns', $numColumns);
    }
}