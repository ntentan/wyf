<h2><?= $report_title ?></h2>
<?php
$f = $helpers->wyf->input();
$columns = $f->create('columns', 2);
$fieldset = $f->create('fieldset', 'Filters');

$columns->add($fieldset);

$columns->add(
    $f->create('fieldset', 'Grouping')
);

$columns->add(
        $f->create('fieldset', 'Sorting')
);

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
