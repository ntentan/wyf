<select name="<?= $name ?>" class="<?= $extra_css_classes ?>" <?= $attributes->unescape() ?>>
    <option></option>
    <?php foreach($options as $option): ?>
    <option <?= unescape($option[0]) == unescape($value) ? 'selected="selected"' : "" ?> value="<?= $option[0] ?>"><?= $option[1] ?></option>
    <?php endforeach; ?>
</select>