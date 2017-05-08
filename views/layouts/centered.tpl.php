<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $wyf_title ?></title>
        <?= 
        $helpers->stylesheets
            ->add(get_asset('css/wyf.css'))
            ->add(get_asset('css/menu.css'))
            ->add('vendor/ekowabaka/fzui/dist/fzui.min.css')
        ?>
    </head>
    <body>
        <div class="login-box"><?= unescape($contents) ?></div>
    </body>
</html>