<?= $helpers->form->open()
    ->text('Username', 'username')->setValue($username ?? '')
    ->password('Password', 'password')->setValue($password ?? '')
->close('Login') ?>
