<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

$values = $this->transformArrayValues($this->data->getValues());
?>
<table class="adminlist">
  <thead>
    <tr>
      <th><?php echo JText::_('AOM_CONCEPT'); ?></th>
      <?php foreach($values->passengers as $type => $value): ?>
        <th><?php echo JText::sprintf('AOM_'.$type.'_TITLE', $value); ?></th>
      <?php endforeach; ?>
      <th><?php echo JText::_('AOM_TOTAL'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
      $k = 0;
      $sum = array();
      foreach($values->values as $name => $value)
      {
    ?>
    <tr class="row<?php echo $k; ?>">
      <td class="text"><?php echo JText::_($name); ?></td>
      <?php
      $total = array();
      foreach($values->passengers as $type => $number)
      {
        $total[$type] += $number * (float)$value[$type];
        $sum[$type] += $number * (float)$value[$type];
      ?>
      <td class="number"><?php echo number_format($number * (float)$value[$type], 2, '.', ','); ?></td>
      <?php
      }
      ?>
      <td class="number"><?php $sum['total'] += array_sum($total); echo number_format(array_sum($total), 2, '.', ','); ?></td>
    </tr>
    <?php
        $k = 1 - $k;
      }
    ?>
    <tr class="last">
      <td class="totaltitle"><?php echo JText::_('AOM_TOTAL'); ?></td>
      <?php foreach($values->passengers as $type => $value): ?>
        <td class="subtotal"><?php echo number_format($sum[$type], 2, '.', ','); ?></td>
      <?php endforeach; ?>
      <td class="total"><?php echo number_format($sum['total'], 2, '.', ','); ?></td>
    </tr>
  </tbody>
</table>
