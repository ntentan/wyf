<?php
namespace ntentan\wyf\utilities\forms;

class ColumnsContainer extends Container
{
    public function __construct($numColumns)
    {
        $this->set('num_columns', $numColumns);
    }
}