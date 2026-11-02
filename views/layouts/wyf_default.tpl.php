<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?></title>
        <link href="/css/app.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="/js/app.js" ></script>
        <script type="module" src="/js/fzui.js" ></script>
        <script>fzui.initialize()</script>
    </head>
    <body>
        <fz-app-frame>
            <fz-avatar slot="topnav"></fz-avatar>
            <span slot="logo">Wyf App</span>
            <fz-sidebar slot="sidebar"></fz-sidebar>
        </fz-app-frame>
    </body>
</html>
