<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $wyf_title ?> | <?= $wyf_app_name ?></title>
        <?= 
            $helpers->javascripts
                ->add('components/jquery/jquery.js')
                ->add(get_asset('js/wyf.js'))
                ->add(get_asset('js/api.js'))
                ->add('components/handlebars/handlebars.js')
                ->add('vendor/ekowabaka/fzui/dist/fzui.min.js')
                ->add(get_asset('js/app.js')).
            $helpers->stylesheets
                ->add('vendor/fortawesome/font-awesome/css/font-awesome.css')
                ->add('vendor/ekowabaka/fzui/dist/fzui.min.css')
                ->add(get_asset('css/wyf.css'))
                ->add(get_asset('css/menu.css'))
                ->add(get_asset('css/app.css'))
        ?>
        
        <?php              
        load_asset('fonts/fontawesome-webfont.woff', 'vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.woff');
        load_asset('fonts/fontawesome-webfont.woff2', 'vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.woff2');
        load_asset('fonts/fontawesome-webfont.svg', 'vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.svg');
        load_asset('fonts/fontawesome-webfont.ttf', 'vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.ttf');
        ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="header" class="blue-bg">
                <div class="row">
                    <div class="column grid_10_5">
                        <div id="title-box">
                            <h1><?= $wyf_app_name ?></h1>
                        </div>
                    </div>
                    <div class="column grid_10_5">
                        <div id="profile-box">
                        <?php if($_SESSION['user']['username'] != ''): ?>
                            Logged in as <b><?= $_SESSION['user']['username'] ?></b> | <a href="<?= $wyf_logout_url ?>">Logout</a>&nbsp;&nbsp;&nbsp;
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="notification"></div>
            <?= t('menu.tpl.php', ['menu' => $menu, 'route_breakdown' => $route_breakdown]) ?>
            <div id="contents">
                <?php echo $contents->unescape(); ?>
            </div>
        </div>
    </body>
</html>
