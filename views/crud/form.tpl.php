<?php 
use ntentan\wyf\forms\f;

echo f::create('form')->forModel($model->unescape());
