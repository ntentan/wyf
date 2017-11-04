<table id="wyf_list_table">
    <thead>
        <tr><?php foreach(unescape($list_fields) as $field => $label){
            echo "<th>{$label}</th>";
        }?><th></th></tr>
    </thead>
    <tbody>
        {{#list}}
        <tr>
            <?php foreach($list_fields as $field => $label): ?>
                <?= sprintf("<td>{{%s}}</td>", str_replace(".", "_", is_numeric($field) ? $label : $field)) ?>
            <?php endforeach;?>
            <td>
            <?= t('operations', ['base_url' => $base_url, 'operations' => $operations, 'primary_key_field' => $primary_key_field]) ?>
            </td>
        </tr>
        {{/list}}
    </tbody>
</table>
