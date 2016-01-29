<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$toolTipArray = array('className' => 'season_date_tooltip');
JHTML::_('behavior.tooltip', '.hasSeasonTip', $toolTipArray);

$product_id = $this->lists[$this->lists['product_info']];
?>

<form action="index.php" method="post" name="adminForm">
<table width="100%">
    <tr>
        <td nowrap="nowrap" align="right">
                <?php
                        echo JText::_('CP.PRODUCT_RATE_SEASON_YEAR') . ': ' . $this->lists['season_years'];
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
                    <?php echo JHTML::_('grid.sort', 'CP.PRODUCT_RATE_SEASONS', 'season_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.SEASON_TYPE', 'is_special', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ROW_CREATED_BY', 'name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ROW_CREATION_DATE', 'modified', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JText::_('CP.SEASON_DAYS'); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JText::_('CP.SEASON_DATES'); ?>
            </th>
            <th width="1%" nowrap="nowrap">
                    <?php echo JText::_('CP.ID'); ?>
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
    $url = 'index.php?option=' . $option . '&view=' . $this->viewName . '&product_id=' . $product_id . '&task=edit&cid[]=';
    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row = &$this->data[$i];

        $link   = JRoute::_($url . $row->rate_id);

        $checked = JHTML::_('grid.id', $i, $row->rate_id);

        $created_date = JHTML::_('date', $row->created, 'd/m/Y h:i A');

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
                <?php echo $row->creator; ?>
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
                <?php echo $row->rate_id; ?>
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
<input type="hidden" name="<?php echo $this->lists['product_info']; ?>" value="<?php echo $product_id; ?>" />
<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>