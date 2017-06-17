<h2>Import <?= $entities ?></h2>
<p>
You can supply a file which contains <?= $entities ?> for importing. The same
file could also be used for updating existing <?= $entity ?> data. To help you 
format the file, you can download a <?= $entities ?> data template
file <a href="<?= $import_template_url ?>">here</a>.
This file is in the CSV format and can be edited in any spreadsheet application.
</p>
<p id="import-actions">
    <a href="<?= $import_template_url ?>" class="button-green"><span class="fa fa-download"></span> Download Template</a>
    <button onclick="wyf.list.uploadData('<?= $base_url ?>import')" class="button-blue"><span class="fa fa-upload"></span> Upload Data</button>
</p>
<div id="import-errors">
    
</div>
<script id='import-errors-template' type='text/x-handlebars'>
    <table>
        <thead>
            <tr><th>Line</th><th>Errors</th></tr>
        </thead>
        <tbody>
            {{#errors}}
            <tr>
                <td>{{line}}</td>
                <td>
                    <dl>
                    {{#each errors}}
                        <dt>{{@key}}</dt>
                        <dd>
                            <ul>
                            {{#each this}}
                                <li>{{this}}</li>
                             {{/each}}
                            </ul>
                        </dd>
                    {{/each}}
                    </dl>
                </td>
            </tr>
            {{/errors}}
        </tbody>
    </table>
</script>
