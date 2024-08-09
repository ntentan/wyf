<form <?= $attributes->unescape() ?>>
    <?= $this->partial("wyf_forms_layout.tpl.php", array('elements' => $elements)) ?>
    <?php if($submit_value !== false): ?>
    <div class="form-submit-area">
        <?= $submit_button->u() ?>
    </div>
    <?php endif; ?>
</form>
