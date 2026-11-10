<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?></title>
        <link href="/css/app.css" type="text/css" rel="stylesheet" />
        <script src="/js/app.js" ></script>
    </head>
    <body>
        <fz-app-frame>
            <fz-avatar slot="topnav"></fz-avatar>
            <span slot="logo">Wyf App</span>
            <fz-sidebar slot="sidebar">
                <fz-sidebar-item href="/dashboard">Dashboard</fz-sidebar-item>
                <fz-sidebar-item href="/user_profile">User Profile</fz-sidebar-item>
            </fz-sidebar>
            <div slot="search">Search</div>
            <div slot="topnav">
                Username | Logout
            </div>
        </fz-app-frame>
    </body>
</html>
