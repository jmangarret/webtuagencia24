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
      <td nowrap="nowrap">
        <?php echo $this->filterCategories(); ?>
        <?php echo $this->filterPublished(); ?>
      </td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="20" class="title">#</th>
        <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" /> </th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_ORIGIN'), 'lf.originname', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_DESTINY'), 'lf.destinyname', $dir, $ord ); ?></th>
        <th width="90" nowrap="nowrap"><?php echo JText::_('LFS_DEPARTURE'); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_DURATION'), 'lf.duration', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_VALUE'), 'lf.value', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_CATEGORY'), 'ct.title', $dir, $ord ); ?></th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_PUBLISH'), 'lf.published', $dir, $ord ); ?></th>
        <th width="30" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('LFS_ID'), 'lf.id', $dir, $ord ); ?></th>
      </tr>
    </thead>
    <tfoot>
      <td colspan="10"><?php echo $this->pagination->getListFooter();?></td>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    $page      = $this->pagination;
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_lowfares');

    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row  = $this->data[$i];
        $edit = JRoute::_($this->model->getUrl('flights.edit', array('cid[]' => $row->id)),false);

     ?>
       <tr class="<?php echo "row$k"; ?>">
	     <td align="right"><?php echo $page->getRowOffset( $i ); ?></td>
         <td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
         <td><a href='<?php echo $edit; ?>'><?php echo $row->originname; ?></a></td>
         <td><a href='<?php echo $edit; ?>'><?php echo $row->destinyname; ?></a></td>
         <td>
           <?php echo $row->offset == null ?
           JText::_('COM_LOWFARES_FIXED').': <b style="text-decoration: underline;">'.$row->departure.'</b>' :
           JText::_('COM_LOWFARES_PERIOD').': <b style="text-decoration: underline;">'.$row->offset.'</b>'; ?>
         </td>
         <td style="width: 100px;">
           <?php echo '<b style="text-decoration: underline;">'.$row->duration.'</b> '
           .JText::_('COM_LOWFARES_DAYS_AFTER'); ?>
         </td>
         <td style="text-align: right;"><?php echo number_format($row->value, 2, ',', '.'); ?></td>
         <td><?php echo $row->category; ?></td>
         <td style="width: 90px; text-align: center;">
           <?php echo JHtml::_('jgrid.published', $row->published, $i, 'flights.', $canChange); ?>
         </td>
         <td align="center"><?php echo $row->id; ?></td>
       </tr>
    <?php $k = 1 - $k; } ?>
    </tbody>
  </table>
  <input type="hidden" name="task" value="flights.display" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $dir; ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $ord; ?>" />
</form>

<div id="window-process" style="display:none;">
  <div>
    <div class="filters">
      <h4><?php echo JText::_('COM_LOWFARES_PROCESS_TITLE'); ?></h4>
      <div class="description">
        <?php echo JText::_('COM_LOWFARES_PROCESS_DESC'); ?>
      </div>
      <div class="category">
        <label><?php echo JText::_('COM_LOWFARES_CATEGORY_SELECT'); ?></label>
        <?php echo $this->filterCategories(true); ?>
      </div>
      <div class="published">
        <label><?php echo JText::_('COM_LOWFARES_STATE_SELECT'); ?></label>
        <?php echo $this->filterPublished(true); ?>
      </div>
      <div class="itinerary">
        <label><?php echo JText::_('COM_LOWFARES_ITINERARY_SELECT'); ?></label>
        <?php
        $option = array(
            JHTML::_('select.option', '', JText::_('COM_LOWFARES_ALL_ITINERARIES'))
        );

        echo JHTML::_('select.genericlist', $option, 'filter_itineraries', null, 'value', 'text', '');
        ?>
      </div>
    </div>
    <div class="loader">
      <div class="container processing">
        <div class="percent"><?php echo JText::_('COM_LOWFARES_START');?></div>
        <div class="progress-wrapper">
          <div class="progress"></div>
        </div>
      </div>
    </div>
    <div class="log">
      <ul></ul>
    </div>
    <div class="button">
      <input type="button" value="<?php echo JText::_('COM_LOWFARES_CANCEL'); ?>" />
    </div>
  </div>
</div>

