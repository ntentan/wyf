<?php if(isset($multiline)): ?>
    <textarea class="<?= $extra_css_classes ?>" name="<?= $name ?>" <?= $attributes->u() ?>><?= $value ?></textarea>
<?php else: ?>
    <input class="<?= $extra_css_classes ?>" name="<?= $name ?>"  value="<?= $value ?>" type="<?= isset($masked) ? 'password' : 'text' ?>" <?= $attributes->u() ?>/>
<?php endif; ?>
