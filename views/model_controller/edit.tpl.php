<h2>Edit <?= $entity ?> <?= $item ?> <?= $postfix ?></h2>
<?php 
$form = $helpers->wyf->input($description['fields']); 
$form->data($form_data);
$form->errors($form_errors);
echo t($form_template, 
    array_merge(
        array(
            'form' => $form, 
            'f' => $form,
            'model' => $model_description, 
            'params' => $params,
            'data' => $form_data
        ),
        $form_variables->unescape()
    )
) 
?>
