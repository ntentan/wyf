<?php if(isset($multiline)): ?>
    <textarea class="<?= $extra_css_classes ?>" name="<?= $name ?>" <?= unescape($attributes) ?>><?= $value ?></textarea>
<?php else: ?>
    <input class="<?= $extra_css_classes ?>" name="<?= $name ?>"  value="<?= $value ?>" type="<?= isset($masked) ? 'password' : 'text' ?>" <?= unescape($attributes) ?>/>
<?php endif; ?>
