<?php $errors = $element->errors();?><div class="form_element_wrapper">
    <label><?= $element->label() ?></label>
    <?= $element ?>
    <?php if(count($errors)): ?>
    <ul>
        <?php foreach($errors as $error): ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    <?php ?>
    </ul>
    <?php endif; ?>
</div>
