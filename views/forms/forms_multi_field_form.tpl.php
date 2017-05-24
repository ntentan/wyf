<h2>Add a new <?= $entity ?></h2>
<?php
$form = new ntentan\wyf\utilities\forms\Form();
$form->setTag('div')
    ->add(
        $form->create('model_field', unescape($package)),
        $form->create('html', "<div id='{$type}_add_form_view'></div>")
    )
    ->setAttribute('class', '')
    ->getSubmitButton()
        ->setAttribute('onclick', "payItemAssign('allowance_id', '$api_url')");
echo $form; ?>

<script id="<?= "{$type}_add_form_template" ?>" type="text/x-handlebars">
<?php 
$createForm = new ntentan\wyf\utilities\forms\Form();
$createForm->setTag('div')->setSubmitValue(false);

$fieldset = new \ntentan\wyf\utilities\forms\Fieldset("New $entity Details");
$fieldset->add(
    $form->create('html', t($create_form, ['form' => $createForm]))
);
echo $fieldset;
?>
</script>