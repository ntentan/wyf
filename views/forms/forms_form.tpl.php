<<?= $tag ?> <?= $attributes->unescape() ?>>
    <?= t("wyf_forms_layout.tpl.php", array('elements' => $elements)) ?>
    <?php if($submit_value !== false): ?>
    <div class="form-submit-area">
        <?= unescape($submit_button) ?>
    </div>
    <?php endif; ?>
</<?= $tag ?>>
