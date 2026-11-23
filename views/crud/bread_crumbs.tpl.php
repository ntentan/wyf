<fz-breadcrumb chevron color="red">
    <?php foreach($breadcrumbs as $breadcrumb): ?>
        <fz-breadcrumb-item><a href="/<?= $breadcrumb['url'] ?>"><?= $breadcrumb['label'] ?></a></fz-breadcrumb-item>
    <?php endforeach; ?>
</fz-breadcrumb>
