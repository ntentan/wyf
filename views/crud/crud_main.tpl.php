<div class='wyf-top-actions'>
<a href="<?= $wyf_add_link ?>">Add <?= $wyf_entity ?></a>
</div>
<div class='wyf-listing'></div>
<?= $this->partial('crud_list', ['fields' => $fields, 'headers' => $headers]) ?>