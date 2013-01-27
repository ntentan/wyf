<select class="<?= $extra_css_classes ?>" <?= $attributes ?>>
    <option></option>
    <?php foreach($options as $value => $label): ?>
    <option value="<?= $value ?>"><?= $label ?></option>
    <?php endforeach; ?>
</select>