<h2>Edit <?= $wyf_entity ?></h2>
<div id="wyf-form-wrapper">
<?= $this->partial("wyf_crud_edit_{$wyf_entity}_form", ['model' => $model, 'data' => $data, 'errors' => $errors ?? []]) ?>
</div>
