<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= wyf_title ?></title>
        <?= $helpers->javascript
            ->add(n('assets/js/jquery.js'))
            ->add(p('wyf/assets/js/wyf.js'))
            ->add(p('wyf/assets/js/jquery.pjax.js'))
        ?>
    </head>
    <body>
        <div id="wrapper">
            <?php echo $contents ?>
        </div>
    </body>
</html>
