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
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('FEE_TITLE'), 'g.title', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('FEE_DISCOUNT'), 'fg.discount', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('FEE_FEETYPE'), 'fg.feetype', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('FEE_VALUE'), 'fg.fee', $dir, $ord ); ?></th>
        <th width="30" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('FEE_ID'), 'fg.id', $dir, $ord ); ?></th>
      </tr>
    </thead>
    <tfoot>
      <td colspan="10"><?php echo $this->pagination->getListFooter();?></td>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    $page  = $this->pagination;
    $types = array('P' => JText::_('COM_FEE_PERCENT'), 'V' => JText::_('COM_FEE_VALUE'));

    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row  = $this->data[$i];
        $edit = JRoute::_($this->model->getUrl('groups.edit', array('cid[]' => $row->id)),false);

     ?>
       <tr class="<?php echo "row$k"; ?>">
	     <td align="right"><?php echo $page->getRowOffset( $i ); ?></td>
         <td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
         <td><a href='<?php echo $edit; ?>'><?php echo $row->title; ?></a></td>
         <td style="width: 100px;text-align: right;"><?php echo number_format($row->discount, 2, ',', '.'); ?> %</td>
         <td style="width: 120px;"><?php echo $types[$row->feetype]; ?></td>
         <td style="width: 120px;text-align: right;">
           <?php
            echo $row->feetype == 'P' ? '' : '$ ';
            echo number_format($row->fee, 2, ',', '.');
            echo $row->feetype == 'P' ? ' %' : '';
           ?>
         </td>
         <td align="center"><?php echo $row->id; ?></td>
       </tr>
    <?php $k = 1 - $k; } ?>
    </tbody>
  </table>
  <input type="hidden" name="task" value="groups.display" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $dir; ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $ord; ?>" />
</form>
