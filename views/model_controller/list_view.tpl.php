<div id="wyf_toolbar">
    <a href="<?= $wyf_add_url ?>">Add</a>
</div>
<div id="wyf_list_view">
    
</div>
<script type="text/javascript">
    $(function(){
        wyf.listView.update("<?= $wyf_api_url ?>");
    })
</script>
<script type="text/html" id="wyf_list_view_template">
    <table id="wyf_list_table">
    <thead>
        <tr><?php foreach($list_fields as $field){
            echo "<th>{$field['label']}</th>";
        }?><th></th></tr>
    </thead>
    <tbody>
        {{#list}}
        <tr><?php 
        // Columns
        foreach($list_fields as $field){
            echo "<td>{{{$field['name']}}}</td>";
        }?><td><?php //Operations
        foreach($operations as $operation){
            echo "<a href='{$operation['link']}/{{{$key_field}}}'>{$operation['label']}</a> ";
        }
        ?></td></tr>
        {{/list}}
    </tbody>
    </table>
</script>
