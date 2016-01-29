<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
?>

<form action="index.php" method="post" name="adminForm">
<table>
 <tr>
    <td align="left" width="100%">
            <?php echo JText::_('FILTER'); ?>:
            <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']); ?>" class="text_area" onchange="document.adminForm.submit();" />
            <button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
            <button onclick="document.getElementById('search').value='';document.getElementById('category_id').selectedIndex=0;document.getElementById('city_id').selectedIndex=0;document.getElementById('country_id').selectedIndex=0;document.getElementById('region_id').selectedIndex=0;this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('RESET'); ?></button>
    </td>
    <td nowrap="nowrap">
    <?php
        echo $this->lists['countries'] . '&nbsp;';
        echo $this->lists['regions'] . '&nbsp;';
        echo $this->lists['cities'] . '&nbsp;';
        echo $this->lists['tourismtypes'] . '&nbsp;';
        echo $this->lists['categories'] . '&nbsp;';
        echo $this->lists['state'];
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
                    <?php echo JHTML::_('grid.sort', 'Name', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
            <?php echo JHTML::_('grid.sort', 'CP.CITY', 'city_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.TOUR_EDITOR', 'editor', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.ROW_UPDATED_DATE', 'modified', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.TOUR_CATEGORY_NAME', 'category_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.FEATURED', 'featured', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'ACCESS', 'access', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
			<th width="20">
				<?php echo JHTML::_('grid.sort', 'CP.ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?><?php echo JHTML::_('grid.order', $this->data); ?>
			</th>
			<th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'STATE', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ID', 'product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
            <tr>
                <td colspan="12">
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

                $link   = JRoute::_($url . $row->product_id);

		$checked = JHTML::_('grid.id', $i, $row->product_id);

		$published 	= JHTML::_('grid.published', $row, $i);

		$modified_date = JHTML::_('date', $row->modified, 'd/m/Y h:i A');

		?>
         <tr class="<?php echo "row$k"; ?>">
            <td>
                    <?php echo $this->pagination->getRowOffset($i); ?>
            </td>
            <td>
                    <?php echo $checked; ?>
            </td>
            <td>
                    <span class="editlinktip hasTip" title="<?php echo JText::_('EDIT'); ?>::<?php echo $this->escape($row->product_name); ?>">
                            <a href="<?php echo $link; ?>">
                                    <?php echo $this->escape($row->product_name); ?></a></span>
            </td>
            <td align="center">
                    <?php echo $row->city_name . ', ' . $row->country_name; ?>
            </td>
            <td align="center">
                <?php echo $row->editor; ?>
            </td>
            <td align="center">
                <?php echo $modified_date; ?>
            </td>
            <td align="center">
                <?php echo $row->category_name; ?>
            </td>
            <td align="center">
                <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','togglefeatured')" title="<?php echo ($row->featured) ? JText::_('CP.JYES') : JText::_('CP.JNO');?>">
                    <img src="images/<?php echo ($row->featured) ? 'tick.png' : ($row->featured != -1 ? 'publish_x.png' : 'disabled.png');?>" width="16" height="16" border="0" alt="<?php echo ($row->featured) ? JText::_('CP.Yes') : JText::_('CP.No'); ?>" /></a>
            </td>
            <td align="center">
                <?php echo JHTML::_('grid.access', $row, $i); ?>
            </td>
            <td nowrap>
            <?php
            $page = new JPagination($n, 1, $n);
            ?>
                    <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
                    <span><?php echo $page->orderUpIcon($i, $i>0, 'orderup', JText::_('MOVE_UP'), true); ?></span>
                    <span><?php echo $page->orderDownIcon($i, $n, $i<$n, 'orderdown', JText::_('MOVE_DOWN'), true); ?></span>
			</td>
			<td align="center">
				<?php echo $published; ?>
			</td>
			<td align="center">
				<?php echo $row->product_id; ?>
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