<?= $auth_message ?? ""?>
<?php
$form = new \ntentan\wyf\utilities\forms\Form();
$form->add(
    $form->create('text_field', 'username', 'Username'),
    $form->create('text_field', 'password', 'Password')
        ->setMasked(true)
)->setSubmitValue('Login');
echo $form;