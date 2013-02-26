<h2><?= $report_title ?></h2>
<?= $helpers->form->open()->action($action_route)->attribute("target", "_blank") ?>
<div id="wyf-report-wrapper">
    <div id="wyr-report-options">
        <h3>Filters</h3>
        <?php foreach($report_filters as $title => $filter): ?>
        <div>
        <span><?= $title ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="wyf-report-output">
    <?=
    $helpers->form->get_selection_list('Output')->option('Portable Document Format', 'pdf')
    ?>
    </div>
</div>
<?= $helpers->form->close("Generate") ?>
