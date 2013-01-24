<h2 id="sub_menu_header"><?= $header ?></h2>
<ul id="sub_menu_list">
    <?php foreach($side_menus as $menu): ?>
    <li <?= $menu['path'] == $route_breakdown[1] ? 'class="selected"' : '' ?> >
        <a href="<?= u("{$route_breakdown[0]}/{$menu['path']}") ?>"><?= $menu['label'] ?></a>
    </li>
    <?php endforeach; ?>
</ul>
