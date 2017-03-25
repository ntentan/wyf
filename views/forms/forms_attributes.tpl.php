<?php 
foreach($attributes as $attribute => $value)
{
    if($value == '') continue;
    printf('%s = "%s" ', $attribute, htmlentities($value));
}
