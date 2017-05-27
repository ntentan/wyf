<div class="input-wrapper">
    <a onclick="fzui.modal('#<?= $type ?>-multi-field-form')"><i class="fa fa-plus-circle"></i> Attach <?= $label ?></a>
</div>
<div class="hidden-fields">
    
</div>
<div class="modal" id="<?= $type ?>-multi-field-form">
    <div class="wyf-modal-wrapper">
        <?= t(
                "{$type}_forms_multi_field_form", 
                [
                    'create_form' => $form_template, 'package' => $package, 
                    'type' => $type, 'entity' => $entity,
                    'primary_key' => $primary_key
                ]
            ) ?>
    </div>
</div>