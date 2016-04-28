<?php 
$elements = $elements->unescape();
$elements_per_column = ceil(count($elements) / $num_columns);
?>
<table width='100%'>
    <tr>
        <?php for ($i = 0; $i < $num_columns; $i++){
        $columnElements = array_slice($elements, $i * $elements_per_column, $elements_per_column);
        echo "<td>" . t("wyf_input_forms_layout.tpl.php", array('elements' => $columnElements, 'layout' => $layout)) . "</td>";
        } ?>
    </tr>
</table>
