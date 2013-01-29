<h2><?= ucfirst($entities) ?></h2>
<div id="wyf_toolbar">
    <a class="wyf_button" id="toolbar_add" href="<?= $wyf_add_url ?>">Add a new <?= $entity ?></a><a class="wyf_button" id="toolbar_import" href="<?= $wyf_import_url ?>">Import <?= $entities ?></a>
</div>
<div id="wyf_list_view">
    
</div>
<div id="wyf_list_view_control">
    <div class="row">
        <div class="column grid_10_3">
            <span class="wyf_button nav_button" onclick="wyf.listView.prevPage()">&lt; Prev</span><span class="wyf_button nav_button" onclick="wyf.listView.nextPage()">Next &gt;</span>
        </div>
        <div class="column grid_10_7">
            <div id="wyf_list_view_pagecount">
                Page <span id="wyf_list_view_page">1</span> of <span id="wyf_list_view_size">100</span>
            </div>
        </div>
    </div>
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
            }?><td class="wyf_list_table_operations"><?php 
            
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
load_asset('images/import.png', p('wyf/assets/images/import.png'));
?>