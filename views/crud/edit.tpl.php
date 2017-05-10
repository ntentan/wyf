<h2>Edit <?= $entity ?></h2>
<div id="form-wrapper">
<?php
$form = new ntentan\wyf\utilities\forms\Form();
$form->setData($model->toArray());
$form->setErrors($model->getInvalidFields());
$form->add(
    (new ntentan\wyf\utilities\forms\HiddenField($primary_key_field))->setValue($model[unescape($primary_key_field)])
);
echo t("wyf_{$package}_form", ['model' => $model, 'form' => $form]) 
?>
</div>
