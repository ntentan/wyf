<input class="<?= $extra_css_classes ?>" type="text" <?= unescape($attributes) ?> />
<div id="<?= $name ?>_response_list" class="model-search-field-list">
    
</div>
<input type="hidden" name="<?= $name ?>" />
<script type="text/handlebars" id="<?= $name ?>_preview_template">
    <div value='{{id}}' label='<?= $value_template ?>' class='model-search-field-list-item' onclick="wyf.forms.selectModelSearchItem(this, '<?= $name ?>')">
    <?= unescape($list_template) ?>
    </div>
</script>