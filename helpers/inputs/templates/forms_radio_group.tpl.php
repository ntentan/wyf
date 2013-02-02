<div class="form_radio_group">
    <?php foreach($options as $option): ?>
    <div class="form_radio_group_option">
    <label><input type="radio" name="<?= $name ?>" <?= $option['value'] == $value ? 'checked="checked"' : null ?> value="<?= $option['value'] ?>" /><?= $option['label'] ?></label>
    </div>
    <?php endforeach; ?>
</div>
