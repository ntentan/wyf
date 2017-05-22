<?= t("wyf_forms_select_field", ['value'=>$value, 'name' => $name, 'extra_css_classes' => $extra_css_classes, 'attributes' => $attributes, 'options'=>$options, 'label'=>$label]) ?>
<?php if($has_add): ?>
<div class="hidden-fields">
    <?php foreach($hidden_fields as $key => $value): ?>
    <input type='hidden' name='<?= $key ?>' value='<?= $value ?>' />
    <?php endforeach; ?>
</div>
<div class="modal" id="<?= "{$entity}_add_modal" ?>">
    <div class="wyf-modal-wrapper">
        <h2>Add a new <?= $label ?></h2>
        <div class="form-wrapper">
            <?php
            $form = new ntentan\wyf\utilities\forms\Form();
            $form->setAttribute('id', "{$entity}_add_form")
                ->setTag('div')
                ->getSubmitButton()
                    ->setAttribute('onclick', "wyf.forms.validateInputs('$entity', '$api_url', '$name', wyf.forms.addToListCallback)")
                    ->setValue('Add');
            echo t($form_template, ['model' => $model, 'form' => $form])
            ?>
        </div>  
    </div>
</div>
<?php endif; ?>
