<h2>Edit <?= $wyf_entity ?></h2>
<div id="wyf-form-wrapper">
<?= $this->partial("wyf_{$wyf_entity}_crud_edit_form", ['model' => $model, 'data' => $data, 'errors' => $errors ?? []]) ?>
</div>
