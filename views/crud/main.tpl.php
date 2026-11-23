<div class="wyf-title">
    <?= $this->partial('bread_crumbs', ['breadcrumbs' => $wyf_breadcrumbs]) ?>
    <div class='wyf-top-actions'>
        <a href="<?= $add_path ?>" class='button button-green button-big'>Add <?= $wyf_entity ?></a>
    </div>
</div>

<div id='wyf-item-list' wyf-data-path="<?= $list_data_path ?>?fields=<?= urlencode(implode(',', $list_fields->unescape())) ?>"></div>
<?= $this->partial(
    'crud_list', 
    ['fields' => $list_fields, 'labels' => $list_labels, 'key_field' => $key_fields]
) ?>