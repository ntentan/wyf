<h2>Add a new <?= $wyf_entity ?></h2>
<div id="wyf-form-wrapper">
<?= $this->partial("wyf_crud_add_{$wyf_entity}_form", ['model' => $model, 'data' => $data ?? [], 'errors' => $errors ?? []]) ?>
</div>
