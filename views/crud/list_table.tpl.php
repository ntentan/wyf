<table id="wyf_list_table">
    <thead>
        <tr><?php foreach($column_headers as $column_header){
            echo "<th>{$column_header}</th>";
        }?><th></th></tr>
    </thead>
    <tbody>
        {{#list}}
        <tr>
            <?php foreach($list_fields as $field): ?>
                <?= sprintf("<td>{{%s}}</td>", $field) ?>
            <?php endforeach;?>
            <td>
            <?= t('operations', ['base_url' => $base_url, 'operations' => $operations, 'primary_key_field' => $primary_key_field]) ?>
            </td>
        </tr>
        {{/list}}
    </tbody>
</table>
