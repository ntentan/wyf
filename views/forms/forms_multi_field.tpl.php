<div class="input-wrapper">
    <a onclick="fzui.modal('#<?= $field_name ?>-multi-field-form')"><i class="fa fa-plus-circle"></i> Attach <?= $label ?></a>
</div>
<div class="hidden-fields">
    
</div>
<div class="modal" id="<?= $field_name ?>-multi-field-form">
    <div class="wyf-modal-wrapper">
        <?= t(
                "{$type}_forms_multi_field_form", 
                ['create_form' => $form_template, 'package' => $package, 'type' => $type]
            ) ?>
    </div>
</div>