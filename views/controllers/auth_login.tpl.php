<fz-card corner="curved" layer="3" id="wyf-login-card">
    <h2>Login</h2>
    <?php if(isset($error)):?>
        <fz-callout error><?= $error ?></fz-callout>
    <?php endif ?>
    <?= $helpers->form->open()
            ->setAttribute('class', 'form--large')
            ->setAttribute('id', 'wyf-login-form')
            ->text('Username', 'username')->setValue($username ?? '')
            ->password('Password', 'password')->setValue($password ?? '')
            ->close('Login') ?>
</fz-card>
