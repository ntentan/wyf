<h1><?= $wyf_entity ?></h1>
<div class='wyf-top-actions'>
<a href="<?= $add_path ?>" class='button button-green button-mega'>Add <?= $wyf_entity ?></a>
</div>
<div id='wyf-item-list' wyf-data-path="<?= $list_data_path ?>"></div>
<?= $this->partial(
    'crud_list', 
    ['fields' => $list_fields, 'labels' => $list_labels, 'key_field' => $key_fields]
) ?>