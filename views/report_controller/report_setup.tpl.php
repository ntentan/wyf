<h2><?= $report_title ?></h2>
<?php 
$helpers->form->getRendererInstance()->noWrap = true;
?>
<?= $helpers->form->open()->action($action_route)->attribute("target", "_blank") ?>
<div id="wyf-report-wrapper">
    <div id="wyr-report-options">
        <h3>Filters</h3>
        <?php foreach($report_filters as $title => $filter): ?>
        <div>
            <span><?= $title ?></span>
            <?php 
            switch($filter['type'])
            {
                case DataSource::TYPE_FLOAT:
                case DataSource::TYPE_INTEGER:
                    echo $helpers->form->get_selection_list('')
                        ->option("is equal to")
                        ->option("is greater than")
                        ->option("is less than")
                        ->option("is between")
                        ->option("is not equal to")
                        ->option("is not between");
                    break;
                case DataSource::TYPE_TEXT:
                    echo $helpers->form->get_selection_list('')
                        ->option("matches")
                        ->option("contains")
                        ->option("is less than")
                        ->option("is between");                    
                    break;
            }
            ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="wyf-report-output">
    <?=
    $helpers->form->get_selection_list('Output', 'output')->option('Portable Document Format', 'pdf')
    ?>
    </div>
</div>
<?= $helpers->form->close("Generate") ?>
