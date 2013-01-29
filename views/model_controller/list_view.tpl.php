<h2><?= ucfirst($entities) ?></h2>
<div id="wyf_toolbar">
    <a id="toolbar_add" href="<?= $wyf_add_url ?>">Add a new <?= $entity ?></a>
    <a id="toolbar_import" href="<?= $wyf_import_url ?>">Import <?= $entities ?></a>
</div>
<div id="wyf_list_view">
    
</div>
<div id="wyf_list_view_control">
    <span onclick="wyf.listView.prevPage()">&lt; Prev</span> 
    <span onclick="wyf.listView.nextPage()">Next &gt;</span>
    Page <span id="wyf_list_view_page">1</span> of <span id="wyf_list_view_size">100</span>
</div>
<script type="text/javascript">
    $(function(){
        wyf.listView.api = "<?= $wyf_api_url ?>";
        wyf.listView.init();
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
            }?><td><?php 
            
            //Operations
            foreach($operations as $operation){
                echo "<a href='{$operation['link']}/{{{$key_field}}}'>{$operation['label']}</a> ";
            }
            ?></td></tr>
            {{/list}}
        </tbody>
    </table>
</script>
<?php
load_asset('images/add.png', p('wyf/assets/images/add.png'));
?>