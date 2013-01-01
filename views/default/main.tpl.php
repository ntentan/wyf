<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $wyf_title ?></title>
        <?= $helpers->javascript
            ->add(n('assets/js/jquery.js'))
            ->add(p('wyf/assets/js/wyf.js'))
            ->add(p('wyf/assets/js/jquery.pjax.js'))
            ->add(p('wyf/assets/js/mustache.js'))
        ?>
        
        <?= $helpers->javascript->ntentan() ?>
        
        <?= $helpers->stylesheet
            ->add(p('wyf/assets/css/wyf.css'))
            ->add(p('wyf/assets/css/forms.css'))
        ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="header"><h1>ntentan.wyf</h1></div>
            <div id="top_menu"></div>
            <div id="bread_crumb_trail"></div>
            <div id="side_menu"></div>
            <div id="contents"><?php echo $contents ?></div>
        </div>
    </body>
</html>
