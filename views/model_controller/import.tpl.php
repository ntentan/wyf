<h2>Import <?= $entities ?> <?= $postfix ?></h2>
<p>
You can supply a file which contains <?= $entities ?> for importing. The same
file could also be used for updating existing <?= $entity ?> data. To help you 
format the file, you can download a <?= $entities ?> data template
file <a href="<?= $import_template ?>">here</a>.
This file is in the CSV format and can be edited in any spreadsheet application.
</p>

<?php if($upload_error != ''): ?>
<div class="form-error"><?= $upload_error ?></div>
<?php endif; ?>

<?php if(count($errors) > 0): ?>
<table class="import-error-table">
    <thead>
        <tr><th>Line</th><th>Error</th></tr>
    </thead>
    <tbody>
        <?php foreach($errors as $error): ?>
        <tr>
            <td><?= $error['line'] ?></td>
            <td>
                <?php foreach($error['errors'] as $field => $field_errors): ?>
                    <b><?= $field ?></b>
                    <ul>
                    <?php foreach($field_errors as $field_error): ?>
                    <li><?= $field_error ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>


<div id="form_wrapper">
    <?php
    $form = $helpers->wyf->input()->attribute('enctype', 'multipart/form-data');
    $form->add($form->create('upload', 'Data file', 'data_file'));
    $form->setSubmitValue('Import');
    echo $form;
    ?>
</div>