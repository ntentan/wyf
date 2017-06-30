<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?></title>
        <link href="/public/css/bundle.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="/public/js/bundle.js" ></script>
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
