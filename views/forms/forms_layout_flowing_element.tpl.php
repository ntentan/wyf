<?php 
$errors = $element->getErrors();
$label = $element->getLabel();
$hideWrapper = in_array($element->getType(), ['HiddenField']) ? true : false;
$description = $element->getDescription();
?>
<?php if(!$hideWrapper): ?><div class="form-element" id="form-element-<?= $element->getName() ?>"><?php endif; ?>
<?php if($label != ''): ?><label><?= $label ?></label><?php endif; ?>
<?= unescape($element) ?>
<?php if($description != ''): ?>
    <div class="form-element-description"><?= $description ?></div>
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
