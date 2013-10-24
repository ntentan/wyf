<?php 
$errors = $element->errors();
$label = $element->label();
if($element->getType() == 'Box')
{
    $hideWrapper = true;
}
?>
<?php if(!$hideWrapper): ?><div class="form-element-wrapper" id="form-element-<?= $element->name() ?>"><?php endif; ?>
<?php if($label != ''): ?><label><?= $label ?></label><?php endif; ?>
<?= $element ?>
<?php if($element->description() != ''): ?>
    <div class="form-element-description"><?= $element->description() ?></div>
<?php endif; ?>
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
<?php if(!$hideWrapper): ?></div><?php endif; ?>
