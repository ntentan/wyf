<div id="menu">
    <?php $subMenu = []; $base = ""; ?>
    <div id="main-menu" class="gray-4-bg">
        <?php foreach($menu as $item): ?>
        <?php if($route_breakdown[0] == $item['route']) {
            $selected = "menu-item-selected";
            $subMenu = $item['children'];
            $baseRoute = $item['route'];
        } else {
            $selected = "";
        }?>
        <div title="<?= $item['label'] ?>" class="menu-item <?= $selected ?>" id="menu-item-<?= $item['route'] ?>">            
        </div>
        <?php endforeach; ?>
    </div>
    <?php if(!empty($subMenu)): ?>
    <div id="sub-menu">
        <ul>            
        <?php foreach($subMenu as $item): ?>
            <li><a href="<?= "/{$baseRoute}/{$item['route']}" ?>"><?= $item["label"] ?></a></li>
        <?php endforeach; ?>
        </ul>            
    </div>
    <?php endif; ?>
</div>