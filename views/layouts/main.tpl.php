<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $wyf_title ?> | <?= $wyf_app_name ?></title>
        <?= 
            $helpers->javascripts
                ->add(get_asset('js/jquery.js'))
                ->add(get_asset('js/wyf.js'))
                ->add(get_asset('js/api.js'))
                ->add(get_asset('js/mustache.js')).
            $helpers->stylesheets
                ->add(get_asset('css/wyf.css'))
                ->add(get_asset('css/menu.css'))
                ->add('vendor/ekowabaka/fzui/dist/fzui.min.css')
        ?>
        
        <?php
        load_asset('images/home.png', get_asset('images/dashboard.png'));
        load_asset('images/system.png', get_asset('images/system.png'));
        load_asset('images/headerbg.gif', get_asset('images/headerbg.gif'));
        load_asset('images/sidemenubg.gif', get_asset('images/sidemenubg.gif'));
        load_asset('images/mainbg.gif', get_asset('images/mainbg.gif'));
        load_asset('images/selectedbg.gif', get_asset('images/selectedbg.gif'));        
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
            <?= t('menu.tpl.php', ['menu' => $menu]) ?>
            <div id="contents">
                <?php echo $contents->unescape(); ?>
            </div>
        </div>
    </body>
</html>
