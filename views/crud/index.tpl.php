<h2><?= ucfirst($entities) ?></h2>
<?php if($has_add_operation): ?>
<a class="button-green" href="<?= $add_item_url ?>"><?= isset($add_button_label) ? $add_button_label : "Add a new $entity" ?></a>
<?php endif; ?>
<div id="wyf_list_view"></div>
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
        api.get("<?= $api_url ?>", {},
            function(data, xhr){
                var template = Handlebars.compile($('#wyf_list_view_template').html());
                $('#wyf_list_view').html(template({list:data}));
                $('#wyf_list_view_size').html(Math.ceil(xhr.getResponseHeader('X-Item-Count') / 10));
            }
        );
    })
</script>
<script type="text/html" id="wyf_list_view_template">
    <?= t("wyf_crud_" . str_replace(" ", "_", $entities) . "_list", ['list_fields' => $list_fields, 'operations' => $operations, 'primary_key_field' => $primary_key_field]) ?>
</script>
<?php
    load_asset('images/add.png');
    load_asset('images/addbg.gif');
    load_asset('images/import.png');
?>
