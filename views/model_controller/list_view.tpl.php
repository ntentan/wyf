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
    <table>
    {{#list}}
    <tr><?php foreach($list_fields as $field){
        echo "<td>{{{$field['name']}}}</td>";
    }?></tr>
    {{/list}}
    </table>
</script>
