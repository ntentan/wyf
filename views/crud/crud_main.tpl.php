<div class='wyf-top-actions'>
<a href="<?= $wyf_add_link ?>">Add <?= $wyf_entity ?></a>
</div>
<div class='wyf-item-list'></div>
<?= $this->partial(
    'crud_list', 
    ['fields' => $wyf_list_fields, 'labels' => $wyf_list_labels]
) ?>