<h2>Import <?= $entities ?> <?= $postfix ?></h2>
<?php if($upload_error != ''): ?>
<div><?= $upload_error ?></div>
<?php endif; ?>
<p>
You can supply a file which contains <?= $entities ?> for importing. The same
file could also be used for updating existing <?= $entity ?> data. To help you 
format the file, you can download a <?= $entities ?> data template
file <a href="<?= $import_template ?>">here</a>.
This file is in the CSV format and can be edited in any spreadsheet application.
</p>
<div id="form_wrapper">
    <?php
    $form = $helpers->wyf->input()->attribute('enctype', 'multipart/form-data');
    $form->add($form->create('upload', 'Data file', 'data_file'));
    $form->setSubmitValue('Import');
    echo $form;
    ?>
</div>