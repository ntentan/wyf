<h2>Import <?= $entities ?></h2>
<p>
You can supply a file which contains <?= $entities ?> for importing. The same
file could also be used for updating existing <?= $entity ?> data. To help you 
format the file, you can download a <?= $entities ?> data template
file <a href="<?= $import_template_url ?>">here</a>.
This file is in the CSV format and can be edited in any spreadsheet application.
</p>
<div id="import-message">
</div>
<p id="import-actions">
    <a href="<?= $import_template_url ?>" class="button-green button-outline"><span class="fa fa-download"></span> Download Template</a>
    <button onclick="wyf.list.uploadData('<?= $base_url ?>import')" class="button-blue button-outline"><span class="fa fa-upload"></span> Upload Data</button>
</p>
<p id="import-loader">
    Importing ...
</p>
<script id='import-errors-template' type='text/x-handlebars'>
    <h3 class="red"><span class="fa fa-exclamation-circle"></span> Data Import Failed</h3>
    <p class="red">Some records failed to save. Please refer to the table below for details</p>
    <table class="import-error-table">
        <thead>
            <tr><th>Line</th><th>Errors</th></tr>
        </thead>
        <tbody>
            {{#errors}}
            <tr><td>{{line}}</td>
                <td><dl>
                {{#each errors}}
                <dt>{{@key}}</dt>
                <dd><ul>{{#each this}}<li>{{this}}</li>{{/each}}</ul></dd>
                {{/each}}
                </dl></td>
            </tr>
            {{/errors}}
        </tbody>
    </table>
</script>
<script id="import-success-template" type="text/x-handlebars">
    <h3 class="green"><span class="fa fa-check-circle"></span> Data Import Successful</h3>
    <p class="green">Successfully uploaded {{count}} <?= $entities ?>.</p>
    <a href="<?= $base_url ?>" class="button">Back to <?=$entities ?></a>
</script>
