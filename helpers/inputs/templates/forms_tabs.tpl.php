<div class="form-tabs-container" <?= $attributes ?>>
    <div class="form-tabs-tab-list">
        <ul>
            <?php foreach($tabs as $i => $tab): ?>
            <li onclick="wyf.tabs.show('<?= $id ?>', '<?= $i ?>')" id="tab_selector_<?= $id ?>_<?= $i ?>" class="form-tabs-tab-selector"><?= $tab->label() ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="form-tabs-tabs">
        <?php foreach($tabs as $i => $tab)
        { 
            if($id != '') 
            {
                $tab->attribute('id', "{$id}_{$i}");
            }
            echo $tab->attribute('class', 'form-tabs-tab'); 
        } ?>
    </div>
</div>
<div style="clear:both"></div>
<script type="text/javascript">
    $(function(){
        wyf.tabs.init('<?= $id ?>');
    });
</script>

