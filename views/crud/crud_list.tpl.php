<script type="text/x-mustache" id="wyf-list-head">
  <table>
    <thead><tr><?php foreach ($labels as $header): ?><th><?= $header ?></th><?php endforeach; ?></tr></thead>
    <tbody>
</script>
<script type="text/x-mustache" id="wyf-list-item">
    <tr><?php foreach ($fields as $field): ?><td>{{<?= $field ?>}}</td> <?php endforeach; ?></tr>
</script>
<script type="text/x-mustache" id="wyf-list-foot">
    </tbody>
  </table>
</script>