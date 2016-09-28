<?php
$grid = $this->data->grid[$this->gridType];
 
$i = 0;
?>
    <table id="table-<?php echo $this->gridType; ?>" class="grid"  cellpadding="2" cellspacing="2">
  <thead>
    <tr align="center">
      <th><?php echo JText::_('COM_ADMINFARE_MIN'); ?></th>
      <th><?php echo JText::_('COM_ADMINFARE_MAX'); ?></th>
      <th><?php echo JText::_('COM_ADMINFARE_VALUE_ADULT'); ?></th>
      <th><?php echo JText::_('COM_ADMINFARE_VALUE_SENIOR'); ?></th>
      <th><?php echo JText::_('COM_ADMINFARE_VALUE_CHILD'); ?></th>
      <th><?php echo JText::_('COM_ADMINFARE_VALUE_INFANT'); ?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php  
    foreach($grid['min'] as $row): 
     
    ?>
    <tr >
      <td><input type="text" name="jform[grid][<?php echo $this->gridType; ?>][min][]"
        class="number min" value="<?php echo number_format($grid['min'][$i], 2, ',', '.'); ?>"/></td>
      <td><input type="text" name="jform[grid][<?php echo $this->gridType; ?>][max][]"
        class="number max" value="<?php echo is_numeric($grid['max'][$i]) ? number_format($grid['max'][$i], 2, ',', '.') : $grid['max'][$i]; ?>"/></td>
      <td><input type="text" name="jform[grid][<?php echo $this->gridType; ?>][value_adult][]"
        class="number value" value="<?php echo number_format($grid['value_adult'][$i], 2, ',', '.'); ?>"/></td>
      <td><input type="text"  name="jform[grid][<?php echo $this->gridType; ?>][value_senior][]"
        class="number value_senior" value="<?php echo number_format($grid['value_senior'][$i], 2, ',', '.'); ?>"/></td>
      <td><input type="text"  name="jform[grid][<?php echo $this->gridType; ?>][value_child][]"
        class="number value_child"  value="<?php echo number_format($grid['value_child'][$i], 2, ',', '.'); ?>"/></td>
      <td><input type="text"  name="jform[grid][<?php echo $this->gridType; ?>][value_infant][]"
        class="number value_infant"  value="<?php echo number_format($grid['value_infant'][$i], 2, ',', '.'); ?>"/></td>
      <td><input type="button" value="<?php echo JText::_('COM_ADMINFARE_REMOVE'); ?>"/></td>
    </tr>
    <?php 
    $i++;
    endforeach; ?>
  </tbody>
  <tfoot>
    <td colspan="4">
    </td>
  </tfoot>
</table>
