<?php
$headers = array();
foreach($model_description['fields'] as $field)
{
    if($field['primary_key']) continue;
    $headers[] = s($field['name']);
}

$output = fopen("php://output", 'w');
fputcsv($output, $headers);
