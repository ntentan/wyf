<?php if(isset($error)):?>
<div><?= $error ?></div>
<?php endif ?>
<?= $helpers->form->open()
    ->text('Username', 'username')->setValue($username ?? '')
    ->password('Password', 'password')->setValue($password ?? '')
->close('Login') ?>
