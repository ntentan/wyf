<ul class="wyf_list_operations">
<?php foreach($operations as $operation): ?>
    <li><a class="operation-<?= $operation['action'] ?>" href='<?= "{$base_url}{$operation['action']}" ?>/{{<?= $primary_key_field ?>}}'> <?= $operation['label'] ?></a></li>
<?php endforeach; ?>
</ul>
    