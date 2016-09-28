<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$toolTipArray = array('className' => 'season_date_tooltip');
JHTML::_('behavior.tooltip', '.hasSeasonTip', $toolTipArray);
?>

<form action="index.php" method="post" name="adminForm">
<table>
<tr>
    <td align="left" width="100%">
            <?php echo JText::_('FILTER'); ?>:
            <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']); ?>" class="text_area" onchange="document.adminForm.submit();" />
            <button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
            <button onclick="document.getElementById('search').value='';document.getElementById('filter_is_special').selectedIndex=0;this.form.submit();"><?php echo JText::_('RESET'); ?></button>
    </td>
    <td nowrap="nowrap">
            <?php
                    echo $this->lists['season_types'];
            ?>
    </td>
</tr>
</table>
<div id="editcell">
  <table class="adminlist">
   <thead>
    <tr>
            <th width="5">
                    <?php echo JText::_('NUM'); ?>
            </th>
            <th width="20">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->data); ?>);" />
            </th>
            <th class="title">
                    <?php echo JHTML::_('grid.sort', 'Name', 'season_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.SEASON_TYPE', 'is_special', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ROW_CREATED_BY', 'editor', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ROW_UPDATED_DATE', 'modified', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JText::_('CP.SEASON_DAYS'); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JText::_('CP.SEASON_DATES'); ?>
            </th>
			<th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ID', 'season_id', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
        </tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="9">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	// Se imprime la información de cada registro
    $url = 'index.php?option=' . $option . '&view=' . $this->viewName . '&task=edit&cid[]=';
    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row = &$this->data[$i];

        $link   = JRoute::_($url . $row->season_id);

		$checked = JHTML::_('grid.id', $i, $row->season_id);

		$created_date = JHTML::_('date', $row->modified, 'd/m/Y H:i');

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_('EDIT'); ?>::<?php echo $this->escape($row->season_name); ?>">
					<a href="<?php echo $link; ?>">
						<?php echo $this->escape($row->season_name); ?></a></span>
			</td>
            <td align="center">
                <?php echo ($row->is_special == 1)? JText::_('CP.SEASON_TYPE_SPECIAL'): JText::_('CP.SEASON_TYPE_STANDARD'); ?>
            </td>
            <td align="center">
                <?php echo $row->editor; ?>
            </td>
            <td align="center">
                <?php echo $created_date; ?>
            </td>
            <td align="center">
                <?php
                if ($row->is_special == 0) {
                    $days = array();
                    for ($cont = 1; $cont < 8; $cont++) {
                    	$day = 'day' . $cont;
                    	if ($row->$day == 1) {
                    		$days[] = JText::_('CP.DAY_SHORT' . $cont);
                    	}
                    }
                    echo implode(', ', $days);
                }
                ?>
            </td>
            <td align="center">
                <?php
                    // Se muestran las fechas de cada vigencia
                    $dates = array();
                    if (!empty($row->dates)) {
	                    foreach ($row->dates as $key => $date) {
	                        if ($date->start_date == $date->end_date) {
	                            $dates[] = JHTML::_('date', $date->start_date, 'd/m/Y');
	                        } else {
                                $dates[] = JHTML::_('date', $date->start_date, 'd/m/Y') . ' - ' . JHTML::_('date', $date->end_date, 'd/m/Y');
                            }
	                    }
	                    echo '<span class="editlinktip hasSeasonTip" title="' . JText::_('CP.SEASON_DATES') . '::' . implode('<br />', $dates) . '"><img border="0" alt="Tooltip" src="' . COM_CATALOGO_PLANES_IMAGESDIR . '/season_dates.png"></span>';
                    }
                ?>
            </td>
            <td align="center">
                <?php echo $row->season_id; ?>
            </td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
</div>

<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>