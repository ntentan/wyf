<table id="wyf_list_table">
    <thead>
        <tr><?php foreach(unescape($list_fields) as $field => $label){
            echo "<th>{$label}</th>";
        }?><th></th></tr>
    </thead>
    <tbody>
        {{#list}}
        <tr><?php 

        // Columns
        foreach($list_fields as $field => $label){
            echo sprintf("<td>{{%s}}</td>", str_replace(".", "_", is_numeric($field) ? $label : $field));
        }?><td class="wyf_list_table_operations"><?php 

        //Operations
        foreach($operations as $operation){
            echo "<a href='{$base_url}{$operation['action']}/{{{$primary_key_field}}}'>{$operation['label']}</a> ";
        }
        ?></td></tr>
        {{/list}}
    </tbody>
</table>
