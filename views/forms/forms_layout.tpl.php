<?php foreach($elements as $element):?>
    <?php 
    $label = $element->getLabel();
    $id = $element->getAttribute('id');
    $hideWrapper = in_array($element->getType(), ['HiddenField']) ? true : false;
    $description = $element->getDescription();
    $errors = $element->getErrors();
    $elementclasses = implode(' ', $element->getCssClasses(\ntentan\wyf\utilities\forms\Element::CSS_CLASS_TARGET_WRAPPER)->unescape());
    ?>
    <?php if(!$hideWrapper): ?><div class="form-element<?= $errors ? " form-error" : "" ?><?= " {$elementclasses}" ?>" id="form-element-<?= $id ?>"><?php endif; ?>
    <?php if($label): ?><label><?= $label ?></label><?php endif; ?>
    <?= unescape($element) ?>
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

