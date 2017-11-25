<?php foreach($elements as $element):?>
    <label><?= $element->getLabel()?></label><?= unescape($element) ?>
<?php endforeach; ?>