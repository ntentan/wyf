<script type="text/x-mustache" id="wyf-list-item">
<table>
    <thead><tr><?php foreach ($labels as $header): ?><th><?= $header ?></th><?php endforeach; ?><th></th></tr></thead>
    <tbody>
  {{#items}}
    <tr><?php foreach ($fields as $field): ?><td>{{<?= $field ?>}}</td> <?php endforeach; ?>
      <td class="wyf-operations">{{#operations}}<a href='{{path}}'>{{label}}</a>{{/operations}}
      </td>
    </tr>
  {{/items}}
  </tbody>
  </table>
</script>
