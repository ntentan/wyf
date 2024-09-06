<?php use ntentan\wyf\forms\f; ?>
<h2>Delete <?= $entity ?> <?= $item ?></h2>
<?php if (isset($errors)): ?>
    <?= $errors ?>
<?php else: ?>
    <p>Are you sure you want to delete the <?= $entity ?> <?= $item ?>? Please note that this action cannot be reversed.</p>
    <?= f::create('form')
        ->add(f::create('hidden', 'id', $id))
        ->setSubmitValue("Yes, delete $item")
    ?>
<?php endif; ?>

