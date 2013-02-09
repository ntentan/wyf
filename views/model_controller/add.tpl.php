<h2>Add a new <?= $entity ?> <?= $postfix ?></h2>
<?php 
$f = $helpers->wyf->input(); 
$f->data($form_data);
$f->errors($form_errors);
if(count($form_errors) > 0)
{
    ?>
    <div class="form_error">There were errors on your form</div>
    <?php
}
echo t($form_template, 
    array(
        'form' => $f, 
        'model' => $model_description,
        'params' => $params
    )
) 
?>