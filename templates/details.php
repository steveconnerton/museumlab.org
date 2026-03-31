<?php if ($details = get_sub_field('details')): ?>
  <div class="details">

    <table>
      <thead>
      <tr>
        <th colspan="2">
          <?php get_template_part_args('templates/content-modules-text', array('v' => 'details_heading', 't' => '')); ?>
        </th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($details as $detail): ?>
        <tr>
          <td valign="top">
            <?php echo $detail['label']; ?>:
          </td>
          <td>
            <?php echo nl2br($detail['value']); ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
