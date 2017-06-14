<div class="row" id="wyf_list_header">
    <div class="column grid_10_7">
        <h2><?= ucwords($entities) ?></h2>
    </div>
    <div class="column grid_10_3" style="text-align: right">
        <?php if($has_add_operation): ?>
        <a id="wyf_list_add_button" class="button-green" href="<?= $add_item_url ?>"><span class="fa fa-plus-circle"></span> <?= isset($add_button_label) ? $add_button_label : "Add a new $entity" ?></a>
        <?php endif; ?>
        <?php if($has_import_operation): ?>
        <a id="wyf_list_add_button" class="button" href="<?= $import_items_url ?>"><span class="fa fa-plus-circle"></span> <?= isset($add_button_label) ? $add_button_label : "Import $entities" ?></a>
        <?php endif; ?>    
    </div>
</div>
<div id="wyf_list_view"></div>
<div id="wyf_list_view_nav">
    <div class="button-group button-group-mini">
        <button onclick="wyf.list.prev()">Prev</button>
        <button onclick="wyf.list.next()">Next</button>
    </div>
    <div id="wyf_list_view_pagecount">
        Page <span id="wyf_list_view_page">1</span> of <span id="wyf_list_view_size">100</span>
    </div>
</div>
<script type="text/javascript">
$(function(){ wyf.list.render("<?= $api_url . $api_parameters?>", 1) })
</script>
<script type="text/html" id="wyf_list_view_template">
    <?= t(
            "wyf_{$package}_list", 
            [
                'list_fields' => $list_fields, 
                'operations' => $operations, 
                'primary_key_field' => $primary_key_field,
                'base_url' => $base_url
            ]
        ) ?>
</script>
