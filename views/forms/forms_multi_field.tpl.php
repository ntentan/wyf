<div class="input-wrapper">
    <a onclick="fzui.modal('#<?= $type ?>-multi-field-form')"><i class="fa fa-plus-circle"></i> Attach <?= $label ?></a>
    <script type="text/javascript">
        wyf.forms.multiFieldValues['<?= $type ?>'] = <?= json_encode(
            [
                'values' => unescape($value), 
                'model' => (string)$package,
                'primaryKey' => (string)$primary_key
            ]) ?>;
    </script>
</div>
<script type="text/x-handlebars" id="<?= $type ?>-multi-field-preview">
    <?= t("{$type}_forms_multi_field_preview") ?>
</script>
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