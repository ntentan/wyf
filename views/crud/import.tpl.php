<h2>Import <?= $entities ?></h2>
<p>
You can supply a file which contains <?= $entities ?> for importing. The same
file could also be used for updating existing <?= $entity ?> data. To help you 
format the file, you can download a <?= $entities ?> data template
file <a href="<?= $import_template_url ?>">here</a>.
This file is in the CSV format and can be edited in any spreadsheet application.
</p>
<p>
    <a href="<?= $import_template_url ?>" class="button-green"><span class="fa fa-download"></span> Download Template</a>
    <button onclick="wyf.list.uploadData('<?= $base_url ?>import')" class="button-blue"><span class="fa fa-upload"></span> Upload Data</button>
</p>

<?php if($upload_error ?? false): ?>
    <div class="form-error"><?= $upload_error ?></div>
<?php endif; ?>

<?php if(count($errors ?? []) > 0): ?>
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
                    <b><?= s($field) ?></b>
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
    <?php ?>
</div>