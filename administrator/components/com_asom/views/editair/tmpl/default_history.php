<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
$history = $this->data->getHistory();
?>
<table class="adminlist">
  <thead>
    <tr>
      <th><?php echo JText::_('AOM_USER'); ?></th>
      <th><?php echo JText::_('AOM_NOTE'); ?></th>
      <th><?php echo JText::_('AOM_STATUS'); ?></th>
      <th width="170"><?php echo JText::_('AOM_FECSIS'); ?></th>
    </tr>
  </thead>
  <tbody>
  <?php
  $k = 0;
  foreach($history as $history):
      $status = $this->status[$history->status];
  ?>
    <tr class="row<?php echo $k; ?>">
      <?php $user = $history->user_id >= 0 ? JFactory::getUser($history->user_id) : new JObject(); ?>
      <td><?php echo $user->get('name', JText::_('PUBLIC_USER')); ?></td>
      <td><?php echo JText::_($history->note); ?></td>
      <td><?php echo $status['name']; ?></td>
      <td align="center"><?php echo $history->fecsis; ?></td>
    </tr>
  <?php endforeach; $k = 1 - $k; ?>
  </tbody>
</table>
