<h2>Add a new <?= $entity ?></h2>
<div id="form-wrapper">
<?php
$form = new ntentan\wyf\utilities\forms\Form();
echo t($form_template, ['model' => $model, 'form' => $form]) 
?>
</div>
