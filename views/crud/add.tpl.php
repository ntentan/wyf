<h2>Add a new <?= $entity ?></h2>
<div id="form-wrapper">
<?php
use ntentan\wyf\utilities\forms\Form;
use ntentan\wyf\utilities\forms\Element;

Element::setSharedFormData([
    'api_url' => $api_url,
    'base_api_url' => $base_api_url
]);

$form = new Form();
$form->setData($model->getData()->toArray());
$form->setErrors($model->getInvalidFields());
echo t("wyf_{$package}_form", ['model' => $model, 'form' => $form])
?>
</div>
