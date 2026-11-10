<fz-card corner="curved" layer="3">
    <h2>Login</h2>
    <?php if(isset($error)):?>
        <div><?= $error ?></div>
    <?php endif ?>
    <?= $helpers->form->open()
            ->setAttribute('class', 'form--large')
            ->setAttribute('id', 'wyf-login-form')
            ->text('Username', 'username')->setValue($username ?? '')
            ->password('Password', 'password')->setValue($password ?? '')
            ->close('Login') ?>
</fz-card>
