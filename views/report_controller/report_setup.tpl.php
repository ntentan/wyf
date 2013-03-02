<h2><?= $report_title ?></h2>
<?php 
$helpers->form->getRendererInstance()->noWrap = true;
?>
<?= $helpers->form->open()->action($action_route)->attribute("target", "_blank") ?>
<div id="wyf-report-wrapper">
    <div id="wyr-report-options">
        <h3>Filters</h3>
        <div id="wyf-report-filters">
        
        </div>
        <a href="#" onclick="wyf.reports.addFilter()">Add</a>
    </div>
    <div id="wyf-report-output">
        <?= $helpers->form->get_selection_list('Output', 'output')->option('Portable Document Format', 'pdf') ?>
    </div>
</div>
<?= $helpers->form->close("Generate") ?>
<script type="text/javascript">
var reportColumnDataTypes = {};
<?php foreach($report_filters as $title => $filter):?>
reportColumnDataTypes["<?= $title ?>"] = "<?= $filter['type'] ?>";
<?php endforeach;?>
</script>
<script type="text/html" id="wyf-report-filter-template">
    <div id="filter_{{id}}_wrapper">
    <?php 
    $options = $helpers->form->get_selection_list('', 'filter_{{id}}_column')->id('filter_{{id}}');
    $options->attribute('onchange', "wyf.reports.filterUpdated(this)");
    foreach($report_filters as $title => $filter)
    {
        $options->option($title, '');
    }
    echo $options;
    ?>   
    <span id="filter_{{id}}_operators"></span>
    <span id="filter_{{id}}_operands"></span>
    <span onclick="wyf.reports.removeFilter({{id}})">Close</span>
    </div> 
</script>