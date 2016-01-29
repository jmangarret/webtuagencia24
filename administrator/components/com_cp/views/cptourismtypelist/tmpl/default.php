<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pagination');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$canCreate	= $user->authorise('core.create', 'com_cp');
$canEdit	= $user->authorise('core.edit', 'com_cp');
$canChange	= $user->authorise('core.edit.state', 'com_cp');
?>
<form action="<?php echo JRoute::_('index.php?option=com_cp&view=cptourismtypelist');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_CP_FILTER'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('COM_CP_GO'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';document.id('filter_published').value='';this.form.submit();"><?php echo JText::_('COM_CP_RESET'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" id="filter_published" class="inputbox" onchange="this.form.submit();">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', $this->posibleStates, 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_CP_TOURISMTYPE_NAME', 'tourismtype_name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_CP_PUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->tourismtype_id); ?>
				</td>
				<td>
					<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_cp&task=cptourismtype.edit&tourismtype_id='.$item->tourismtype_id);?>">
							<?php echo $this->escape($item->tourismtype_name); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->tourismtype_name); ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'cptourismtypelist.', $canChange, 'cb'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
