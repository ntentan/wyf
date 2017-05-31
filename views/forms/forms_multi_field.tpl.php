<div class="input-wrapper">
    <a onclick="fzui.modal('#<?= $type ?>-multi-field-form')"><i class="fa fa-plus-circle"></i> Attach <?= $label ?></a>
    <script type="text/javascript">
        wyf.forms.multiFieldValues['<?= $type ?>'] = <?= json_encode(unescape($value)) ?>;
    </script>
</div>
<div class="hidden-fields">
    <?php foreach($value as $item): ?>
    <input type="hidden" name="<?= $package ?>.<?= $primary_key ?>[]" value="<?= $item[unescape($primary_key)] ?>"/>
    <?php endforeach; ?>
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