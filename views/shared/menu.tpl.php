<div id="menu">
    <?php $subMenu = []; $base = ""; ?>
    <div id="main-menu" class="gray-4-bg">
        <?php foreach($menu as $item): ?>
        <?php if($route_breakdown[0] == $item['route']) {
            $selected = "menu-item-selected";
            $subMenu = $item['children'];
            $baseRoute = $item['route'];
            $label = $item['label'];
        } else {
            $selected = "";
        }?>
        <a href="<?= $prefix ?>/<?= $item['route'] ?>" title="<?= $item['label'] ?>" class="menu-item <?= $selected ?>" id="menu-item-<?= $item['route'] ?>"></a>
        <?php endforeach; ?>
    </div>
    <?php if(!empty($subMenu)): ?>
    <div id="sub-menu">
        <h1><?= $label ?></h1>
        <ul>            
        <?php foreach($subMenu as $item): ?>
            <li <?= $item['route'] == $route_breakdown[1] ? "class='menu-item-selected'" : "" ?>>
                <a href="<?= "$prefix/{$baseRoute}/{$item['route']}" ?>"><?= $item["label"] ?></a>
            </li>
        <?php endforeach; ?>
        </ul>            
    </div>
    <?php endif; ?>
</div>