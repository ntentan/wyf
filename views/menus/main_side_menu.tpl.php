<?php $route_breakdown = $route_breakdown->unescape() ?>
<?php foreach($side_menus->unescape() as $menu): ?>
<a title="<?= $menu['label'] ?>" class="main-menu-side-button <?= $menu['path'] == $route_breakdown[0] ? 'selected' : '' ?>" id="<?=$menu['path']?>-icon" href="<?= u($menu['path']) ?>"></a>
<!--<div class="main-menu-side-popup" id="<?=$menu['path']?>-label"><?= $menu['label'] ?></div>-->
<?php endforeach; ?>
