<h2>Add a new <?= $wyf_entity ?></h2>
<div id="wyf-form-wrapper">
<?= $this->partial("wyf_{$wyf_entity}_crud_add_form", ['model' => $model, 'data' => $data ?? [], 'errors' => $errors ?? []]) ?>
</div>
