<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?></title>
        <link href="<?= $route_prefix ?? "" ?>/css/app.css" type="text/css" rel="stylesheet" />
        <?= $this->partial('wyf_stylesheets', ['stylesheets' => $stylesheets ?? []]) ?>
        <script src="/js/app.js"></script>
    </head>
    <body>
        <div class="wyf-centered">
            <?= $contents->u() ?>
        </div>
    </body>
</html>