<form <?= $attributes ?>>
    <?php foreach($elements as $element):?>
        <?= t(
                "wyf_input_forms_layout_{$layout}_element.tpl.php", 
                array('element' => $element)
        ) ?>
    <?php endforeach; ?>
    <input type="hidden" name="form-sent" value="yes" />
    <input type="submit" value="Save" />
</form>