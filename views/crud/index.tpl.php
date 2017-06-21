<div class="row" id="wyf_list_header">
    <div class="column grid_10_5">
        <h2><?= ucwords($entities) ?></h2>
    </div>
    <div class="column grid_10_5" style="text-align: right">
        <div id="wyf-operations-button-group" class="button-group">
            <?php if($has_add_operation): ?>
            <a class="button button-green" href="<?= $add_item_url ?>"><span class="fa fa-plus-circle"></span>&nbsp;&nbsp;<?= isset($add_button_label) ? $add_button_label : "Add a new $entity" ?></a>
            <?php endif; ?>
            <?php if($has_import_operation): ?>
            <div class="dropdown">
                <button id="wyf-add-menu-button" class="button-green"></button>
                <ul id="wyf-add-menu" class="dropdown-contents dropdown-right menu">
                    <li><a href="<?= $import_items_url ?>"><span class="fa fa-upload"></span> <?= isset($add_button_label) ? $add_button_label : "Import $entities from file" ?></a></li>
                    <li><a href="<?= $import_items_url ?>_template"><span class="fa fa-download"></span> <?= isset($add_button_label) ? $add_button_label : "Download $entities template file" ?></a></li>
                </ul>
            </div>
            <?php endif; ?>
            <div class="dropdown">
                <button id="wyf-list-search-button" class="button-yellow"><span class="fa fa-search"></span></button>
                <div id="wyf-list-search-wrapper" class="dropdown-contents dropdown-right menu">
                    <input id="wyf-list-search-field" type="text" placeholder="Search..." />
                </div>
            </div>
        </div>
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
$(function(){ wyf.list.apiUrl = "<?= $api_url . $api_parameters?>"; wyf.list.render(); })
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
