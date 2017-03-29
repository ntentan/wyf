<select name="<?= $name ?>" class="<?= $extra_css_classes ?>" <?= $attributes->unescape() ?>>
    <option></option>
    <?php foreach($options as $option_value => $label): ?>
    <option <?= $option_value == $value ? 'selected="selected"' : '' ?> value="<?= $option_value ?>"><?= $label ?></option>
    <?php endforeach; ?>
</select>