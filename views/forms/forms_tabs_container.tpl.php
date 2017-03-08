<ul class="tabs nav">
    <?php foreach($tabs as $i => $tab): ?>
    <li <?= $i == 0 ? 'class="active"' : "" ?>><a href="#<?= $tab->getId() ?>"><?= $tab->getLabel() ?></a></li>
    <?php endforeach; ?>
</ul>
<?php foreach($tabs as $i => $tab): ?> 
<div class="nav-panel" id="<?= $tab->getId() ?>">
    <?= $tab->unescape() ?>
</div>
<?php endforeach; ?>
