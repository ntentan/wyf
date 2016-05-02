<?php if(isset($multiline)): ?>
    <textarea class="<?= $extra_css_classes ?>" <?= $attributes ?>><?= $value ?></textarea>
<?php else: ?>
    <input class="<?= $extra_css_classes ?>" type="text" <?= $attributes ?>/>
<?php endif; ?>