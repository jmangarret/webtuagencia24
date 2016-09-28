<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_cp&view=cpproductslist'); ?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_CP_FILTER'); ?>:</label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />

            <button type="submit" class="btn"><?php echo JText::_('COM_CP_GO'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';document.id('filter_tourismtype_id').value='';document.id('filter_category_id').value='';document.id('filter_published').value='';this.form.submit();"><?php echo JText::_('COM_CP_RESET'); ?></button>
        </div>
        <div class="filter-select fltrt">
            <?php
                echo $this->lists['tourismtype_id'];
                echo $this->lists['category_id'];
            ?>
            <select name="filter_published" id="filter_published" class="inputbox" onchange="this.form.submit();">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                <?php echo JHtml::_('select.options', $this->posibleStates, 'value', 'text', $this->state->get('filter.published'), true); ?>
            </select>
        </div>
    </fieldset>
    <div class="clr"> </div>

	<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_PRODUCT_NAME', 'product_name', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_PRICE', 'price', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_CREATED', 'created', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_PRODUCT_CATEGORY_NAME', 'category_name', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_FEATURED', 'featured', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_ACCESS', 'access', $listDirn, $listOrder); ?>
			</th>
			<th width="10%">
                <?php echo JHtml::_('grid.sort', 'COM_CP_ORDERING', 'ordering', $listDirn, $listOrder); ?>
                <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'cpproductslist.saveorder'); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_CP_PRODUCT_ID', 'product_id', $listDirn, $listOrder); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<?php
	$k = 0;
	$ordering = ($listOrder == 'ordering');
	$link = 'index.php?option=com_cp&task=cpproducts.edit&product_id=';
	$canEdit = $user->authorise('core.edit', 'com_cp');
	$canChange = $user->authorise('core.edit.state', 'com_cp');
	for ($i = 0, $n = count($this->items); $i < $n; $i++) {
		$row = $this->items[$i];
		$canEditOwn = $user->authorise('core.edit.own', 'com_cp') && $row->created_by == $userId;
		$checked = JHtml::_('grid.id', $i, $row->product_id);
		?>
		<tr class="row<?php echo $i % 2; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
                    <?php if ($canEdit || $canEditOwn) : ?>
                        <a href="<?php echo JRoute::_($link . $row->product_id);?>"><?php echo $this->escape($row->product_name); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($row->product_name); ?>
                    <?php endif; ?>
			</td>
			<td class="right">
				<?php echo number_format($row->price, 0, ',', '.'); ?>
			</td>
			<td class="center">
				<?php echo JHtml::_('date', $row->created, JText::_('DATE_FORMAT_LC2')); ?>
			</td>
			<td class="center">
				<?php echo $row->category_name; ?>
			</td>
			<td class="center">
				<?php echo $this->helper->featured($row->featured, $i, $canChange); ?>
			</td>
			<td class="center">
				<?php echo $this->escape($row->access_level); ?>
			</td>
			<td class="order">
                    <?php if ($canChange) : ?>
                            <?php if ($listDirn == 'asc') : ?>
                                <span><?php echo $this->pagination->orderUpIcon($i, ($i > 0), 'cpproductslist.orderup', 'COM_CP_MOVE_UP', $ordering); ?></span>
                                <span><?php echo $this->pagination->orderDownIcon($i, $n, ($i < $n), 'cpproductslist.orderdown', 'COM_CP_MOVE_DOWN', $ordering); ?></span>
                            <?php elseif ($listDirn == 'desc') : ?>
                                <span><?php echo $this->pagination->orderUpIcon($i, ($i > 0), 'cpproductslist.orderdown', 'COM_CP_MOVE_UP', $ordering); ?></span>
                                <span><?php echo $this->pagination->orderDownIcon($i, $n, ($i < $n), 'cpproductslist.orderup', 'COM_CP_MOVE_DOWN', $ordering); ?></span>
                            <?php endif; ?>
                        <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text-area-order" />
                    <?php else : ?>
                        <?php echo $row->ordering; ?>
                    <?php endif; ?>
			</td>
			<td class="center">
				<?php echo JHtml::_('jgrid.published', $row->published, $i, 'cpproductslist.', $canChange, 'cb', $row->publish_up, $row->publish_down); ?>
			</td>
			<td class="center">
				<?php echo $row->product_id; ?>
			</td>
		</tr>
	<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>
