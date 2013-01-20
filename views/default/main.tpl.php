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
        
        <?php
        load_asset('images/dashboard.png', p('wyf/assets/images/dashboard.png'));
        load_asset('images/system.png', p('wyf/assets/images/system.png'));
        ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="header"><h1>ntentan.wyf</h1></div>
            <div id="top_menu"></div>
            <div id="bread_crumb_trail"></div>
            <?php if(is_array($_SESSION['menu']['main'])): ?>
            <div id="side_menu">
                <?= t(
                        'main_side_menu.tpl.php', 
                        array(
                            'side_menus' => $_SESSION['menu']['main'],
                            'route_breakdown' => $route_breakdown
                        )
                    ) 
                ?>
            </div>
                <?php if(is_array($_SESSION['menu']['sub'][$route_breakdown[0]])): ?>
                <div id="sub_side_menu">
                <?= t(
                        'sub_side_menu.tpl.php', 
                        array(
                            'side_menus' => $_SESSION['menu']['sub'][$route_breakdown[0]],
                            'route_breakdown' => $route_breakdown,
                            'header' => $route_breakdown[0]
                        )
                    ) 
                ?>                    
                </div>
                <?php $sub_menu_active = true; endif; ?>
            <?php endif; ?>
            <div <?= $sub_menu_active ? "class='sub_menu_active'" :'' ?>id="contents"><?php echo $contents ?></div>
        </div>
    </body>
</html>
