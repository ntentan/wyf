<?= t("wyf_forms_select_field", ['value'=>$value, 'name' => $name, 'extra_css_classes' => $extra_css_classes, 'attributes' => $attributes, 'options'=>$options, 'label'=>$label]) ?>
<?php if($has_add): ?>
<div class="modal" id="<?= "{$package}_add_modal" ?>">
    <div class="wyf-modal-wrapper">
        <h2>Add a new <?= $entity ?></h2>
        <div class="form-wrapper">
            <?php
            $form = new ntentan\wyf\utilities\forms\Form();
            $form->setAttribute('id', "{$package}_add_form");
            $form->setTag('div')->setSubmitValue(false);
            echo t($form_template, ['model' => $model, 'form' => $form])
            ?>
            <div style="text-align:right">
                <button class="button-blue" onclick="wyf.saveInputs('<?= $package ?>', '<?= $api_url ?>', '<?= $name ?>', wyf.forms.addToListCallback)">
                    Save
                </button>
            </div>            
        </div>  
    </div>
</div>
<?php endif; ?>
