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
    $resetFilters .= 'document.adminForm.'.$filter.'.value=\'\';';
?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete='off'>
  <table width='100%'>
    <tr>
      <td align="left" width="100%">
        <?php echo JText::_('FILTER'); ?>:
        <input type="text" name="search" id="search" value="<?php echo $search; ?>" class="text_area" onchange="document.adminForm.submit();">
        <button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
        <button onclick="<?php echo $resetFilters; ?>"><?php echo JText::_('RESET'); ?></button>
      </td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="20" class="title">#</th>
        <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->data ); ?>);" /> </th>
        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_NAME'), 'st.name', $dir, $ord ); ?></th>
        <th nowrap="nowrap" colspan="2">
          <?php echo JHTML::_('grid.sort', JText::_('AOM_COLOR'), 'st.color', $dir, $ord ); ?>
          <?php
          if($ord == 'st.color'){
              $image  = '<a href="javascript:saveorder(1, \'statuses.saveColors\')" class="saveorder" title="';
              $image .= JText::_('AOM_SAVE_COLORS').'"></a>';
              echo '<a id="save-colors" href="#" title="'.JText::_('AOM_SAVE_COLORS').'">'.$image.'</a>';
          }
          ?>
        </th>
        <th width="90" nowrap="nowrap"><?php echo JText::_('AOM_DEFAULT'); ?></th>
        <th width="30" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('AOM_ID'), 'st.id', $dir, $ord ); ?></th>
      </tr>
    </thead>
    <tfoot>
      <td colspan="7"><?php echo $this->pagination->getListFooter();?></td>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    $page = $this->pagination;

    $default  = '<a class="jgrid" title="'.JText::_('AOM_DEFAULT');
    $default .= '"><span class="state default"><span class="text">';
    $default .= JText::_('AOM_DEFAULT').'</span></span></a>';

    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row       = &$this->data[$i];
        $edit      = JRoute::_($this->model->getUrl('statuses.edit', array('cid[]' => $row->id)),false);

     ?>
       <tr class="<?php echo "row$k"; ?>">
	     <td align="right"><?php echo $page->getRowOffset( $i ); ?></td>
         <td><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
         <td><a href='<?php echo $edit; ?>'><?php echo $row->name; ?></a></td>
         <td class="col-color"><?php echo $row->color; ?></td>
         <?php if($ord != 'st.color'): ?>
         <td class="choose-color"><div style="background: <?php echo $row->color; ?>"></div></td>
         <?php else: ?>
         <td class="choose-color"><input type="text" class="color2" name="color[]" value="<?php echo $row->color; ?>" /></td>
         <?php endif; ?>
         <td align="center"><?php echo $row->default_status == 1 ? $default : ''; ?></td>
         <td align="center"><?php echo $row->id; ?></td>
       </tr>
    <?php $k = 1 - $k; } ?>
    </tbody>
  </table>
  <input type="hidden" name="task" value="statuses.display" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $dir; ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $ord; ?>" />
</form>
