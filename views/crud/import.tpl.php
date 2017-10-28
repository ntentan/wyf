<h2>Import <?= ucwords($entities) ?></h2>

<div id="import-message">
    <?php if($job_status === null): ?>
    <div id="new-import-message">
        <p>
            You can supply a file which contains <?= $entities ?> for importing. The same
            file could also be used for updating existing <?= $entity ?> data. To help you
            format the file, you can download a <?= $entities ?> data template
            file <a href="<?= $import_template_url ?>">here</a>.
        </p>
        <p>
            <a href="<?= $import_template_url ?>" class="button-green button-outline"><span class="fa fa-download"></span> Download Template</a>
            <button onclick="wyf.list.uploadData('<?= $base_url ?>import')" class="button-blue button-outline"><span class="fa fa-upload"></span> Upload Data</button>
        </p>
    </div>
    <?php endif; ?>

    <?php
    if($job_status['status'] == 'queued') {
        echo t('import_message', [
            'title' => 'Please wait ...',
            'message' => "Your $entities import is currently queued."]
        ) .
        '<script type="text/javascript"> setTimeout(wyf.list.checkImportStatus, 300) </script>';
    } else if ($job_status['status'] == 'running') {
        echo t('import_message', [
            'title' => 'Importing ...',
            'message' => "<i class=\"fa fa-spinner fa-pulse fa-fw\"></i>  Your $entities are currently being imported ..."]
        ) .
        '<script type="text/javascript"> setTimeout(wyf.list.checkImportStatus, 300) </script>';
    } else if($job_status['status'] == 'finished') {
        $response = json_decode(unescape($job_status['response']), true);
        if(empty($response['errors'])) {
            echo(t('import_success', ['count' => $response['count'], 'entities' => ucwords($entities), 'base_url' => $base_url]));
        } else {
            echo(t('import_failure', ['errors' => $response['errors']]));
        }
    }
    ?>
</div>
<script id='import-errors-template' type='text/x-handlebars'><?php include("import_failure.mustache") ?></script>
<script id='import-success-template' type="text/x-handlebars"><?php include("import_success.mustache") ?></script>
<script id='import-message-template' type="text/x-handlebars"><?php include("import_message.mustache") ?></script>
<script type="text/javascript">
<?php if($job_id): ?>
  wyf.list.importJobUrl = "<?= $base_url ?>import_status/<?= $job_id ?>";
<?php endif; ?>
  wyf.list.importParameters = JSON.parse('<?= json_encode(['entities' => ucwords(unescape($entities))]) ?>');
</script>
