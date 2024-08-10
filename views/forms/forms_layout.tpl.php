<?php use ntentan\wyf\utilities\forms\HiddenField;

 foreach($elements as $element):?>
    <?php 
    $label = $element->getLabel();
    $id = $element->getAttribute('id');
    $hideWrapper = $element->u() instanceof HiddenField;
    $description = $element->getDescription();
    $errors = $element->getErrors();
    ?>
    <?php if(!$hideWrapper): ?><div class="form-element <?= $errors->unescape() ? "form-error" : "" ?>" id="form-element-<?= $id ?>"><?php endif; ?>
    <?php if($label): ?><label><?= $label ?></label><?php endif; ?>
    <?= $element->u() ?>
    <?php if(count($errors)): ?>
        <ul>
            <?php foreach($errors as $error): ?>
            <li><?= $error ?></li>
            <?php endforeach; ?>
        <?php ?>
        </ul>
    <?php endif; ?>
    <?php if($description != ''): ?>
        <span class="form-description"><?= $description ?></span>
    <?php endif; ?>
    <?php if(!$hideWrapper): ?></div><?php endif; ?>
<?php endforeach; ?>

