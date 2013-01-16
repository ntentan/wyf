<h2>Import <?= $entities ?></h2>
<?php if($upload_error != ''): ?>
<div><?= $upload_error ?></div>
<?php endif; ?>
<p>
You can supply a list of <?= $entities ?> for importing and also updating
existing <?= $entity ?> data.You can download a <?= $entities ?> data template
file <a href="<?= $import_template ?>">here</a> to use as a guide in preparing your upload file.
File is in CSV format and can be edited in any spreadsheet application.
</p>
<div id="form_wrapper">
    <?php
    $form = $helpers->wyf->input()->attribute('enctype', 'multipart/form-data');
    $form->add($form->create('upload', 'Data file', 'data_file'));
    $form->setSubmitValue('Import');
    echo $form;
    ?>
</div>