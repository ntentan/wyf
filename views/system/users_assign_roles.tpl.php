<h2>Assign roles to <?= $item ?></h2>
<form method="post">
<?php foreach($roles as $role): ?>
<div>
    <div><input type="checkbox" <?= $assigned_roles[$role['id']->unescape()] === true ? "checked='checked'" : '' ?> value="<?= $role['id'] ?>" name="role_id_<?= $role['id'] ?>" /><?= $role['name'] ?></div>
    <div><?= $role['description'] ?></div>
</div>
<?php endforeach; ?>
    <input type="submit" value="Save Permissions" />
</form>