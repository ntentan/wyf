<div <?= $attributes ?>>
    <?php foreach($elements as $element):?>
        <?= t(
                "wyf_input_forms_layout_{$layout}_element.tpl.php", 
                array('element' => $element)
        ) ?>
    <?php endforeach; ?>
</div>
