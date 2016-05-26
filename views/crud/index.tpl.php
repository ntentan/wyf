<h2><?= ucfirst($entities) ?></h2>
<div id="wyf_toolbar">
    <?php if($has_add_operation): ?>
    <span class="wyf_button" id="toolbar_add"><a href="<?= $add_item_url ?>"><?= isset($add_button_label) ? $add_button_label : "Add a new $entity" ?></a></span><?php
    if($import_items_url != ''):?><a class="wyf_button" id="toolbar_import" href="<?= $import_items_url ?>">Import <?= $entities ?></a><?php endif; ?>
    <?php endif; ?>
</div>
<div id="wyf_list_view">
    
</div>
<div id="wyf_list_view_control">
    <div class="row">
        <div class="column grid_10_3">
            <span id="wyf_left_nav" class="nav_button nav_left nav_button_active" onclick="wyf.listView.prevPage()">&lt; Prev
            </span><span id="wyf_right_nav" class="nav_button nav_right nav_button_active" onclick="wyf.listView.nextPage()">Next &gt;</span>
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
        wyf.listView.api = "<?= unescape($api_url) ?>";
        <?php if($foreign_key != ''): ?>
        wyf.listView.setConditions({
            <?=$foreign_key?> : "<?= $foreign_key_value ?>"
        })
        <?php endif; ?>
        <?php 
        $fields = array();
        foreach(unescape($list_fields) as $list_field){
            $fields[] = $list_field['name'];
        }
        ?>
        wyf.listView.setFields(<?= json_encode($fields) ?>);
        wyf.listView.init();
    })
</script>
<script type="text/html" id="wyf_list_view_template">
    <table id="wyf_list_table">
        <thead>
            <tr><?php foreach(unescape($list_fields) as $field){
                echo "<th>{$field['label']}</th>";
            }?><th></th></tr>
        </thead>
        <tbody>
            {{#list}}
            <tr><?php 
            
            // Columns
            foreach($list_fields as $field){
                echo sprintf("<td>{{%s}}</td>", str_replace(".", "_", $field['name']));
            }?><td class="wyf_list_table_operations"><?php 
            
            //Operations
            foreach($operations as $operation){
                echo "<a href='{$base_url}{$operation['action']}/{{{$primary_key_field}}}'>{$operation['label']}</a> ";
            }
            ?></td></tr>
            {{/list}}
        </tbody>
    </table>
</script>
<?php
    load_asset('images/add.png');
    load_asset('images/addbg.gif');
    load_asset('images/import.png');
?>