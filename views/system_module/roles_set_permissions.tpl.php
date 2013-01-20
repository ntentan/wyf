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
            <?php foreach($permission_item['permissions'] as $permission => $permission_description): ?>
                <tr>
                    <td><?= $permission_description ?></td>
                    <td><input name="<?= $permission ?>" type="radio"></td>
                    <td><input name="<?= $permission ?>" type="radio"></td>
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
