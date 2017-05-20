<div class="input-wrapper">
    <a onclick="fzui.modal('#<?= $field_name ?>-multi-field-form')"><i class="fa fa-plus-circle"></i> Add new <?= ntentan\utils\Text::singularize($label) ?></a>
</div>
<div class="modal" id="<?= $field_name ?>-multi-field-form">
    <div class="wyf-modal-wrapper">
        <?= t($form_template, $form_template_data) ?>
    </div>
</div>