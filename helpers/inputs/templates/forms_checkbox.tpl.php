<label>
    <input type="hidden" value="0" name="<?= $name ?>" />
    <input type="checkbox" value="1" <?= $value == '1' ? 'checked="checked"' : '' ?> <?= $attributes ?>/> <?= $label ?>
</label>