<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?></title>
        <link href="/css/wyf.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="/js/wyf.js" ></script>
    </head>
    <body <?= isset($wyf_crud_mode) ? "wyf-mode='{$wyf_crud_mode}'" : ""?>>
        <div id="wrapper">
            <header>
                <section><?= $wyf_app_name ?></section>
                <section id="breadcrumb-trail">
                    <?= $this->partial('wyf_breadcrumbs', ['wyf_bread_crumbs' => $wyf_breadcrumbs]) ?>
                </section>
            </header>  
            <nav id="side-menu">
                <?= $this->partial('wyf_menu.tpl.php', ['menu' => $wyf_menu, 'prefix' => $ntentan_uri_prefix]) ?>
            </nav>
            <div id="contents"><article><?= $contents->unescape(); ?></article></div>
        </div>
    </body>
</html>
