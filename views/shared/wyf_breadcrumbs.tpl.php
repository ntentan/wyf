<ul>
    <?php foreach($wyf_bread_crumbs as $breadcrumb): ?>
        <li><a href="<?= $breadcrumb['path'] ?>"><?= $breadcrumb['label'] ?></a></li>
    <?php endforeach; ?>
</ul>