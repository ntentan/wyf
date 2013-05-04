<h2><?= ucfirst($entities) ?> <?= $postfix ?></h2>
<div id="wyf_toolbar">
    <?php if($has_add_operation): ?>
    <a class="wyf_button" id="toolbar_add" href="<?= $wyf_add_url ?>">Add a new <?= $entity ?></a><?php
    if($wyf_import_url != ''):?><a class="wyf_button" id="toolbar_import" href="<?= $wyf_import_url ?>">Import <?= $entities ?></a><?php endif; ?>
    <?php endif; ?>
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
        <?php if($foreign_key != ''): ?>
        wyf.listView.setConditions({
            <?=$foreign_key?> : "<?= $foreign_key_value ?>"
        })
        <?php endif; ?>
        <?php 
        $fields = array();
        foreach($list_fields as $list_field){
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
            <tr><?php foreach($list_fields as $field){
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