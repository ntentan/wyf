<h2>Add a new <?= $entity ?></h2>
<?php 
$form = $helpers->wyf->input($description['fields']); 
$form->data($form_data);
$form->errors($form_errors);
?>
<div id="form_wrapper">
<?= t($form_template, array('form' => $form, 'model' => $model_description)) ?>
</div>