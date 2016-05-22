<form <?= $attributes ?>>
    <?= t("wyf_forms_layout.tpl.php", array('elements' => $elements, 'layout' => $layout)) ?>
    <div class="form-submit-area">
    <?php if($ajax != ''): ?>
        <button type='button' onclick='<?= $ajax->unescape() ?>'><?= $submit_value ?></button>
    <?php else: ?>
        <input type="hidden" name="form-sent" value="yes" />
        <input type="submit" value="<?= $submit_value ?>" <?= isset($submit_target) ? "target='{$submit_target}'" : '' ?> />
    <?php endif; ?>
    </div>
</form>