<h2><?= ucfirst($entities) ?></h2>
<?php if($has_add_operation): ?>
<a class="button-green" href="<?= $add_item_url ?>"><span class="fa fa-plus-circle"></span> <?= isset($add_button_label) ? $add_button_label : "Add a new $entity" ?></a>
<?php endif; ?>
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
$(function(){ wyf.list.render("<?= $api_url ?>", 1) })
</script>
<script type="text/html" id="wyf_list_view_template">
    <?= t(
            "wyf_crud_" . str_replace(" ", "_", $entities) . "_list", 
            [
                'list_fields' => $list_fields, 
                'operations' => $operations, 
                'primary_key_field' => $primary_key_field,
                'base_url' => $base_url
            ]
        ) ?>
</script>
<?php
    load_asset('images/add.png');
    load_asset('images/addbg.gif');
    load_asset('images/import.png');
?>
