<h2>Add a new <?= $entity ?></h2>
<div id="form-wrapper">
<?php
$form = new ntentan\wyf\utilities\forms\Form();
$form->setData($model->getData()->toArray());
$form->setErrors($model->getInvalidFields());
echo t("wyf_{$package}_form", ['model' => $model, 'form' => $form])
?>
</div>
