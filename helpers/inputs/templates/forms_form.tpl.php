<form <?= $attributes ?>>
    <?php foreach($elements as $element):?>
        <?= t(
                "wyf_input_forms_layout_{$layout}_element.tpl.php", 
                array('element' => $element)
        ) ?>
    <?php endforeach; ?>
    <input type="hidden" name="form-sent" value="yes" />
    <div class="form_submit_area">
    <input type="submit" value="<?= $submit_value ?>" />
    </div>
</form>