<div class="form-radio-group">
    <?php foreach($options as $option): ?>
    <div class="form-radio-group-option">
        <label><input <?= "$attributes {$option['attributes']}" ?> type="radio" <?= $option['checked'] ? 'checked="checked"' : '' ?> <?= $option['value'] == $value ? 'checked="checked"' : null ?> value="<?= $option['value'] ?>" /><?= $option['label'] ?></label>
    </div>
    <?php endforeach; ?>
</div>