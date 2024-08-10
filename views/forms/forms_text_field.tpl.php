<?php if(isset($multiline)): ?>
    <textarea name="<?= $name ?>" <?= $attributes->u() ?>><?= $value ?></textarea>
<?php else: ?>
    <input name="<?= $name ?>"  value="<?= $value ?>" type="<?= isset($masked) ? 'password' : 'text' ?>" <?= $attributes->u() ?>/>
<?php endif; ?>
