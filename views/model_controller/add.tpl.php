<h2>Add a new <?= $entity ?></h2>
<?php 
$f = $helpers->wyf->input(); 
$f->data($form_data);
$f->errors($form_errors);
echo t($form_template, array('form' => $f, 'model' => $model_description)) 
?>
