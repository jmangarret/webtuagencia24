<?php 
/*------------------------------------------------------------------------
# JHotelReservation
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHTML::_('behavior.modal');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= true;
$saveOrder	= $listOrder == 'eo.ordering';

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if(task =='extraoptions.addDefault'){
			 SqueezeBox.initialize({
			        size: {x: 1200, y: 500}
			    }); 

			
			 SqueezeBox.open('<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=extraoptions&hotel_id=-1&tmpl=component&layout=defaults',false,-1);?>',{handler: 'iframe',size:{x:840,y:550}});
		}
		else if (task != 'companies.delete' || confirm('<?php echo JText::_('LNG_EXTRA_OPTIONS_CONFIRM_DELETE', true,true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=extraoptions');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left fltlft">
				<label class="filter-search-lbl element-invisible" for="hotel_id"><?php echo JText::_('LNG_SELECT_HOTEL',true); ?></label>
					 <select name="hotel_id" id="hotel_id" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('LNG_SELECT_DEFAULT',true)?></option>
							<option value="-1" <?php echo $this->state->get('filter.hotel_id') == -1?'selected="selected"':''?>><?php echo JText::_('LNG_DEFAULT_EXTRA_OPTIONS',true)?></option>
							<?php echo JHtml::_('select.options', $this->hotels, 'hotel_id', 'hotel_name', $this->state->get('filter.hotel_id'));?>
					</select>
			</div>
			
			<div class="filter-select pull-right fltrt btn-group">
				<select name="status_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATUS',true);?></option>
					<?php echo JHtml::_('select.options', $this->statuses, 'value', 'text', $this->state->get('filter.status_id'));?>
				</select>
			</div>
		</div>
	</div>

	<div class="clr clearfix"> </div>
	
	<table class="table table-striped adminlist"  id="itemList">
		<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL',true); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="15%" >
						<?php echo JHtml::_('grid.sort', 'LNG_NAME', 'eo.name', $listDirn, $listOrder); ?>
					</th>
					<th width="20%" >
						<?php echo JText::_('LNG_DESCRIPTION',true); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'LNG_PRICE', 'eo.price', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'LNG_START_DATE', 'eo.start_date', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'LNG_END_DATE', 'eo.end_date', $listDirn, $listOrder); ?>
					</th>
					<th width="5%">
						<?php echo JText::_('LNG_IMAGE',true); ?>
					</th>
					<th width="3%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'LNG_PER_DAY', 'eo.is_per_day', $listDirn, $listOrder); ?>
					</th>
					<th width="3%">
						<?php echo JHtml::_('grid.sort', 'LNG_MANDATORY', 'eo.mandatory', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'eo.ordering', $listDirn, $listOrder); ?>
						<?php if ($canOrder && $saveOrder) :?>
							<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'extraoptions.saveorder'); ?>
						<?php endif; ?>
					</th>
					<th width="3%" class="left">
						<?php echo JHtml::_('grid.sort', 'LNG_STATUS', 'eo.status', $listDirn, $listOrder); ?>
					</th>
					<th width="3%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'eo.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="15">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php $count = count($this->items); ?>
			<?php foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'eo.ordering');
				$canCreate = true;
				$canEdit   = true;
				$canChange = true;
				$description= $this->hoteltranslationsModel->getObjectTranslation(EXTRA_OPTIONS_TRANSLATION,$item->id,JRequest::getVar( '_lang'));
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td>
						<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_jhotelreservation&task=extraoption.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->name); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->name); ?>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php echo isset($description->content)?$description->content:""; ?>
					</td>
					<td class="center">
						<?php echo $item->price; ?>
					</td>
					<td class="center">
						<?php echo $item->start_date!='0000-00-00' ? JHotelUtil::getDateGeneralFormat($item->start_date):"" ?>
					</td>
					<td class="center">
						<?php echo $item->end_date!='0000-00-00' ? JHotelUtil::getDateGeneralFormat($item->end_date):"" ?>
					</td>
					<td class="center">
						<?php
							if(isset($item->image_path)){
								echo "<img class='preview' src='".JURI::root().PATH_PICTURES.EXTRA_OPTISON_PICTURE_PATH.$item->image_path."'/>";
							}
						?>
					</td>
					<td class="center">
						<?php echo $item->is_per_day==1?JText::_("LNG_YES"):JText::_("LNG_NO"); ?>
					</td>
					<td class="center">
						<?php echo $item->mandatory==1?JText::_("LNG_YES"):JText::_("LNG_NO"); ?>
					</td>
					<td class="order">
						<?php if ($canChange) : ?>
							<div class="input-prepend">
							<?php if ($saveOrder) :?>
								<?php if ($listDirn == 'asc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'extraoptions.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'extraoptions.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'extraoptions.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'extraoptions.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
						 	<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
						 </div>
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php echo (int) $item->status==1?JText::_("LNG_ACTIVE"):JText::_("LNG_INACTIVE"); ?>
					</td>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			
			</tbody>
		</table>
	 
	 <input type="hidden" name="option"	value="<?php echo getBookingExtName()?>" />
	 <input type="hidden" name="task" id="task" value="" /> 
	 <input type="hidden" name="id" value="" />
	 <input type="hidden" name="sourceId" id="sourceId" value="" />
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>

<script type="text/javascript">
	function addNewItem(sourceId){
		SqueezeBox.close();
		jQuery("#sourceId").val(sourceId);
		jQuery("#task").val("extraoption.add");
		jQuery("#adminForm").submit();
	}
	
</script>