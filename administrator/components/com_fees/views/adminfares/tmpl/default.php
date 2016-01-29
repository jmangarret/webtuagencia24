<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

$dir    = $this->model->getState('filter_order_Dir');
$ord    = $this->model->getState('filter_order');
$search = $this->model->getState('search');

$resetFilters = '';
foreach($this->model->getFilters() as $filter)
{
    $resetFilters .= 'document.adminForm.'.$filter.'.value=\'\';';
}
?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete='off'>
  <table width='100%'>
    <tr>
      <td align="left" width="100%">
        <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
        <input type="text" name="search" id="search" value="<?php echo $search; ?>" class="text_area" onchange="document.adminForm.submit();">
        <button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
        <button onclick="<?php echo $resetFilters; ?>"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
      </td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="20" class="title">#</th>
        <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" /> </th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AF_AIRLINE'), 'af.airline', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AF_NOMBRE'), 'a.nombre', $dir, $ord ); ?></th>
        <th width="120"><?php echo JHTML::_('grid.sort', JText::_('AF_VALUETYPE'), 'af.valuetype', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AF_STATE'), 'af.published', $dir, $ord ); ?></th>
        <th width="30" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AF_ID'), 'af.id', $dir, $ord ); ?></th>
      </tr>
    </thead>
    <tfoot>
      <td colspan="7"><?php echo $this->pagination->getListFooter();?></td>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    $page      = $this->pagination;
    $types     = array('P' => JText::_('COM_FEE_PERCENT'), 'V' => JText::_('COM_FEE_VALUE'));
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_fees');
		if(count($this->data)<1){?>
		 <tr >
	     <td align="left" colspan="7"><?php echo JText::_('COM_FEE_MSN_3'); ?></td>
	     </tr>
		<?php 
	}
    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row  = $this->data[$i];
        $edit = JRoute::_($this->model->getUrl('adminfare.edit', array('cid[]' => $row->id)),false);

     ?>
       <tr class="<?php echo "row$k"; ?>">
	     <td align="right"><?php echo $page->getRowOffset( $i ); ?></td>
         <td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
         <td style="width: 90px;" align="center"><a href='<?php echo $edit; ?>'><?php echo $row->airline; ?></a></td>
         <td><a href='<?php echo $edit; ?>'><?php echo $row->nombre; ?></a></td>
         <td><?php echo $types[$row->valuetype]; ?></td>
         <td style="width: 90px; text-align: center;">
           <?php echo JHtml::_('jgrid.published', $row->published, $i, 'adminfare.', $canChange); ?>
         </td>
         <td align="center"><?php echo $row->id; ?></td>
       </tr>
    <?php $k = 1 - $k; } ?>
    </tbody>
  </table>
  <input type="hidden" name="task" value="adminfare.display" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $dir; ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $ord; ?>" />
</form>
