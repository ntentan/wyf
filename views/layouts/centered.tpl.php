<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?></title>
        <link href="/public/css/bundle.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <div class="login-box"><?= $contents->u() ?></div>
    </body>
</html>