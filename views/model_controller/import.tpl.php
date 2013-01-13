<h2>Import <?= $entities ?></h2>
<p>
You can supply a list of <?= $entities ?> for importing and also updating
existing <?= $entity ?> data.You can download a <?= $entities ?> data template
file <a href="<?= $import_template ?>">here</a> to use as a guide in preparing your upload file.
File is in CSV format and can be edited in any spreadsheet application.
</p>
<?php
$form = $helpers->wyf->input();
$form->add($form->create('upload', 'Data file', 'data_file'));
echo $form;
?>
