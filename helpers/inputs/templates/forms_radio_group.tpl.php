<fieldset class="form_radio_group">
    <legend><?= $label ?></legend>
    <?php foreach($options as $option): ?>
    <div class="form_radio_group_option">
    <label><input type="radio" name="<?= $name ?>" value="<?= $option['value'] ?>" /><?= $option['label'] ?></label>
    </div>
    <?php endforeach; ?>
</fieldset>
