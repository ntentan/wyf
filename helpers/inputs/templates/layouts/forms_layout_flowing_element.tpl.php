<?php $errors = $element->errors();?><div class="form-element-wrapper">
    <label><?= $element->label() ?></label>
    <?= $element ?>
    <?php if(count($errors)): ?>
    <div class="form-error">
    <ul>
        <?php foreach($errors as $error): ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    <?php ?>
    </ul>
    </div>
    <?php endif; ?>
</div>
