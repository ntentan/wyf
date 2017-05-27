<h2>Add a new <?= $entity ?></h2>
<?php
$form = new ntentan\wyf\utilities\forms\Form();
$modelField = $form->create('model_field', unescape($package), unescape($create_form));
$form->setTag('div')
    ->add($modelField, $form->create('html', "<div id='{$type}_add_form_view'></div>"))
    ->setAttribute('class', '')
    ->getSubmitButton()
        ->setAttribute('onclick', "wyf.forms.addMultiFields('{$modelField->getName()}','$package', '$primary_key',  '$type')");
echo $form; ?>