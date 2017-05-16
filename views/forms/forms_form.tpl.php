<<?= $tag ?> <?= unescape($attributes) ?>>
    <?= t("wyf_forms_layout.tpl.php", array('elements' => $elements)) ?>
    <?php if($submit_value !== false): ?>
    <div class="form-submit-area">
        <input type="submit" value="<?= $submit_value ?>" <?= isset($submit_target) ? "target='{$submit_target}'" : '' ?> />
    </div>
    <?php endif; ?>
</<?= $tag ?>>
