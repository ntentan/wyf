<div class="row" id="wyf_list_header">
    <div class="column grid_10_5">
        <h1><?= ucwords($entities) ?></h1>
    </div>
    <div class="column grid_10_5" style="text-align: right">
        <div id="wyf-operations-button-group" class="button-group">
            <?php if($has_add_operation && isset($add_item_url)): ?>
            <a class="button button-green" href="<?= $add_item_url ?>"><span class="fa fa-plus-circle"></span>&nbsp;&nbsp;<?= $add_item_label ?></a>
            <?php endif; ?>
            <?php if($has_import_operation && isset($import_items_url)): ?>
            <a class="button button-green" href="<?= $import_items_url ?>"><span class="fa fa-upload"></span></a>
            <?php endif; ?>
            <button id="wyf-list-search-button" class="button-yellow"><span class="fa fa-search"></span></button>
        </div>
        <div class="form-element" id="wyf-list-search-wrapper">
            <input type="text" id="wyf-list-search-field" placeholder="Search ..." />
        </div>
    </div>
</div>
<div id="wyf_list_view">
    <div id='wyf_list_banner'>
        <img src='<?= $public_path?>/images/spinner.gif'/>
    </div>
</div>
<div id="wyf_list_view_nav" class="row">
    <div class="button-group button-group-small column grid_10_3">
        <button class="button-outline" onclick="wyf.list.prev()">Prev</button>
        <button class="button-outline" onclick="wyf.list.next()">Next</button>
    </div>
    <div id="wyf_list_view_pagecount" class="column grid_10_7">
        Page <span id="wyf_list_view_page"></span> of <span id="wyf_list_view_size"></span>
    </div>
</div>
<script type="text/javascript">
  $(function(){ wyf.list.apiUrl = "<?= $api_url . $api_parameters?>"; wyf.list.render(); })
</script>
<script type="text/html" id="wyf_list_view_template">
    <?= t(
        "wyf_{$package}_list_table",
        [
            'list_fields' => $list_fields,
            'operations' => $operations,
            'primary_key_field' => $primary_key_field,
            'base_url' => $base_url
        ]
    ) ?>
</script>
<script type="text/html" id="wyf_list_view_empty">
    <div id='wyf_list_banner'>
        <p style='font-size: larger; padding: 50px'>You have no <?= $entities ?>!</p>
        <a class='button button-green button-outline' href="<?= $add_item_url?>">Add a new <?= $entity ?></a>
        <?php if($has_import_operation): ?>
            <a class='button button-green button-outline' href='<?= $import_items_url ?>'>Import <?= $entities ?> from file</a>
        <?php endif; ?>
    </div>
</script>
