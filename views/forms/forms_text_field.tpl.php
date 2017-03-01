<?php if(isset($multiline)): ?>
    <textarea class="<?= $extra_css_classes ?>" <?= unescape($attributes) ?>><?= $value ?></textarea>
<?php else: ?>
    <input class="<?= $extra_css_classes ?>" type="<?= $masked ? 'password' : 'text' ?>" <?= unescape($attributes) ?>/>
<?php endif; ?>