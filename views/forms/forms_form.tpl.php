<form <?= unescape($attributes) ?>>
    <?= t("wyf_forms_layout.tpl.php", array('elements' => $elements, 'layout' => $layout)) ?>
    <div class="form-submit-area">
        <input type="submit" value="<?= $submit_value ?>" <?= isset($submit_target) ? "target='{$submit_target}'" : '' ?> />
    </div>
</form>