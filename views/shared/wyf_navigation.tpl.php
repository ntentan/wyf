<?php foreach($navigation as $item): ?>
    <fz-sidebar-item<?= !empty($item['selected']) ? ' selected' : '' ?><?= !empty($item['expanded']) ? ' expanded' : '' ?>>
        <span class="material-symbols-outlined" slot="icon">
            <?= $item['icon'] ?>
        </span>
        <?php if(!isset($item['children'])): ?>
            <a href="<?= "{$parent}{$item['module']}" ?>"><?= $item['label'] ?></a>
        <?php else: ?>
            <?= $item['label'] ?>
        <?php endif; ?>
        <?php if (isset($item["children"])): ?>
        <fz-sidebar>
            <?= $this->partial('wyf_navigation', ['navigation' => $item['children'], 'parent' => "{$parent}{$item['module']}/"]) ?>
        </fz-sidebar>
        <?php endif; ?>
    </fz-sidebar-item>
<?php endforeach; ?>
