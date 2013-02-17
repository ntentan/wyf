<h2><?= $report_title ?></h2>
<?php
$f = $helpers->wyf->input();
$columns = $f->create('columns', 2);
$fieldset = $f->create('fieldset', 'Filters');

foreach($report_filters as $filter)
{
    $filter_element = $f->create('text');
    $fieldset->add($filter_element);
}

$columns->add($fieldset);
$columns->add(
    $f->create('fieldset', 'Output Options')->add(
        $f->create('select', 'Format', 'format')
        ->option('Portable Document Format (PDF)', 'pdf')
    )
);

$f->add($columns)
    ->setSubmitValue('Generate')
    ->attribute('target', '_blank')
    ->attribute('action', $action_route);

echo $f;
?>
