<form <?= $attributes ?>>
    <?= t("wyf_input_forms_layout.tpl.php", array('elements' => $elements, 'layout' => $layout)) ?>
    <input type="hidden" name="form-sent" value="yes" />
    <div class="form_submit_area">
    <input type="submit" value="<?= $submit_value ?>" <?php if($submit_target != '') echo "target='{$submit_target}'" ?> />
    </div>
</form>