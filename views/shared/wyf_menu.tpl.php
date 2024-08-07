<?php if(isset($menu) && count($menu) > 0): ?>
<ul>
<?php foreach($menu as $path => $item):?>
  <li>
     <a href='<?= $prefix . $path ?>'><?= $item['label'] ?></a>
     <?php if (isset($item['children'])):?>
     <?= $this->partial('wyf_menu.tpl.php', ['menu' => $item['children'], 'prefix' => "{$prefix}{$path}"]) ?>
     <?php endif?>
  </li>
<?php endforeach;?>
</ul>
<?php endif ?>