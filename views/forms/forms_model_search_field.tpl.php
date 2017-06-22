<input class="<?= $extra_css_classes ?>" type="text" <?= unescape($attributes) ?> />
<div id="<?= $name ?>_response_list" class="model-search-field-list">
    
</div>
<input type="hidden" name="<?= $name ?>" />
<script type="text/handlebars" id="<?= $name ?>_preview_template">
    <?= unescape($template) ?>
</script>