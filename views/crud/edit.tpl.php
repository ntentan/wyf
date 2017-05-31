<h2>Edit <?= $entity ?>: <?= $model ?></h2>
<div id="form-wrapper">
<?php
use ntentan\wyf\utilities\forms\Form;
use ntentan\wyf\utilities\forms\Element;
use ntentan\wyf\utilities\forms\HiddenField;

Element::setSharedFormData([
    'api_url' => $api_url,
    'base_api_url' => $base_api_url
]);
$form = new Form();
$form->setData($model->toArray(1));
$form->setErrors($model->getInvalidFields());
$form->add(
    (new HiddenField($primary_key_field))->setValue($model[unescape($primary_key_field)])
);
echo t("wyf_{$package}_form", ['model' => $model, 'form' => $form]) 
?>
</div>
