<h2> Set permissions for the <?= $role ?> role</h2>

<form method="post">

<?php foreach($permission_items as $permission_item): ?>
<div>
    <?php
    switch ($permission_item['type'])
    {
        case 'link':
            echo "<a href='{$permission_item['link']}'>{$permission_item['label']}</a>";
            break;
        case 'permission':
            echo "Permissions for {$permission_item['label']}";?>
            <table>
            <tr><td></td><td>Yes</td><td>No</td></tr>
            
            <?php foreach($permission_item['permissions'] as $permission): ?>
                <tr>
                    <td><?= $permission['description'] ?></td>
                    <td><input name="<?= $permission['name'] ?>" type="radio" <?=  $permission['active'] ? 'checked="checked"' : '' ?> value="<?= $permission_item['path'] ?>"></td>
                    <td><input name="<?= $permission['name'] ?>" type="radio" <?= !$permission['active'] ? 'checked="checked"' : '' ?> value="no"></td>
                </tr>
            <?php  endforeach; ?>
            
            </table>
            
            <?php 
            break;
    }
    ?>
</div>
<?php endforeach; ?>
    
<input type="submit" value="Save Permissions" />
</form>
