<h2>Edit <?= $entity ?></h2>
<div id="form-wrapper">
<?php
$form = new ntentan\wyf\utilities\forms\Form();
$form->add(
    (new ntentan\wyf\utilities\forms\HiddenField($primary_key_field))->setData($model[unescape($primary_key_field)])
);
echo t($form_template, ['model' => $model, 'form' => $form]) 
?>
</div>
