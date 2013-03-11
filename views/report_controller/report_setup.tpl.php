<h2><?= $report_title ?></h2>
<?php 
$helpers->form->getRendererInstance()->noWrap = true;
?>
<?= $helpers->form->open()->action($action_route)->attribute("target", "_blank") ?>
<div id="wyf-report-wrapper">
<div class="row">
    <div class="column grid_20_15">
        <div id="wyr-report-options">
            <h3>Filters</h3>
            <div id="wyf-report-filters" class="form-element-wrapper">
            
            </div>
            <a href="#" onclick="wyf.reports.addFilter()">Add a filter</a>
        </div>
    </div>
    
    <div class="column grid_20_5">
        <div id="wyf-report-output" class="form-element-wrapper">
            <h3>Output</h3>
            <?= $helpers->form->get_selection_list('Format', 'output')->option('Portable Document Format', 'pdf') ?>
        </div>
    </div>
</div>    
</div>
<?= $helpers->form->close("Generate") ?>
<script type="text/javascript">
var filterMetaData = {};

<?php foreach($report_filters as $title => $filter):?>
    <?php if($filter['backend']) continue; ?>
    filterMetaData["<?= $title ?>"] = {
    	    type : "<?= isset($filter['value']) ? $filter['value']['type'] : $filter['type'] ?>"
    	<?php if(isset($filter['filter_values'])): ?>,
        values : <?= json_encode($filter['filter_values']) ?>
        <?php endif; ?>
        };
<?php endforeach;?>

</script>
<script type="text/html" id="wyf-report-filter-template">
    <div id="filter_{{id}}_wrapper" class="filter-wrapper">
    <?php 
    $options = $helpers->form->get_selection_list('', 'filter_{{id}}_column')->id('filter_{{id}}');
    $options->attribute('onchange', "wyf.reports.filterUpdated(this)");
    foreach($report_filters as $title => $filter)
    {
        if($filter['backend']) continue;
        $options->option($title, '');
    }
    echo $options;
    ?>   
    <span id="filter_{{id}}_operators"></span>
    <span id="filter_{{id}}_operands"></span>
    <span onclick="wyf.reports.removeFilter('#filter_{{id}}_wrapper')" title="Remove Filter"><img src="<?= u(p('wyf/assets/images/minus.png')) ?>" /></span>
    </div> 
</script>