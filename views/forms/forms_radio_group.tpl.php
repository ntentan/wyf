<div class="check-group">
    <?php foreach($options as $option): ?>
    <label><input <?= "$attributes {$option['attributes']}" ?> name="<?= $name ?>" type="radio" <?= $option['checked'] ? 'checked="checked"' : '' ?> <?= $option['value'] == $value ? 'checked="checked"' : null ?> value="<?= $option['value'] ?>" /><?= $option['label'] ?></label>
    <?php endforeach; ?>
</div>
