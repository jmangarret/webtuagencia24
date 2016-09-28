<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
JHtml::_('behavior.tooltip');

if($this->data->feetype != null)
{
    $type_1 = $this->data->feetype == 'V' ? 'checked="checked"' : '';
    $type_2 = $this->data->feetype == 'P' ? 'checked="checked"' : '';
}
else
{
    $type_1 = 'checked="checked"';
    $type_2 = '';
}

?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete="off">
  <div class="width-100">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_FEES_INFO'); ?></legend>
      <ul class="adminformlist">
        <li>
          <label for="jformusergroupid" class="hasTip" title="<?php echo JText::_('COM_FEES_GROUP_LABEL').'::'.JText::_('COM_FEES_GROUP_DESC'); ?>">
            <?php echo JText::_('COM_FEES_GROUP_LABEL'); ?>
          </label>
          <?php if($this->data->id > 0 && $this->data->usergroupid != ''): ?>
            <fieldset class="radio">
              <label><b><?php echo $this->getGroupName($this->data->usergroupid); ?></b></label>
              <input type="hidden" name="jform[usergroupid]" value="<?php echo $this->data->usergroupid; ?>" />
            </fieldset>
          <?php else: ?>
            <?php echo $this->getGroups(); ?>
          <?php endif; ?>
        </li>
        <li>
          <label for="discount" class="hasTip" title="<?php echo JText::_('COM_FEES_DISCOUNT_LABEL').'::'.JText::_('COM_FEES_DISCOUNT_DESC'); ?>">
            <?php echo JText::_('COM_FEES_DISCOUNT_LABEL'); ?>
          </label>
          <input type="text" name="jform[discount]" id="discount" size="5" class="number" value="<?php echo number_format($this->data->discount, 2, ',', '.'); ?>" />
        </li>

        <li>
          <span class="spacer">
            <span class="before"></span>
              <br/>
            <span class="after"></span>
          </span>
        </li>

        <li>
          <label class="hasTip" title="<?php echo JText::_('COM_FEES_FEETYPE_LABEL').'::'.JText::_('COM_FEES_FEETYPE_DESC'); ?>">
            <?php echo JText::_('COM_FEES_FEETYPE_LABEL'); ?>
          </label>
          <fieldset class="radio">
            <input type="radio" name="jform[feetype]" id="type_1" value="V" <?php echo $type_1; ?> />
            <label for="type_1"><?php echo JText::_('COM_FEE_VALUE'); ?></label>
            <input type="radio" name="jform[feetype]" id="type_2" value="P" <?php echo $type_2; ?> />
            <label for="type_2"><?php echo JText::_('COM_FEE_PERCENT'); ?></label>
          </fieldset>
        </li>
        <li>
          <label for="fee" class="hasTip" title="<?php echo JText::_('COM_FEES_FEE_LABEL').'::'.JText::_('COM_FEES_FEE_DESC'); ?>">
            <?php echo JText::_('COM_FEES_FEE_LABEL'); ?>
          </label>
          <input type="text" name="jform[fee]" id="fee" size="12" class="number" value="<?php echo number_format($this->data->fee, 2, ',', '.'); ?>" />
        </li>

        <li>
          <span class="spacer">
            <span class="before"></span>
              <br/>
            <span class="after"></span>
          </span>
        </li>

<!--
        <li>
          <label for="jformcategory" class="hasTip" title="<?php echo JText::_('COM_LOWFARES_CATEGORY_LABEL').'::'.JText::_('COM_LOWFARES_CATEGORY_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_CATEGORY_LABEL'); ?>
          </label>
        </li>
-->
      </ul>
    </fieldset>
  </div>
  <input type="hidden" name="task" value="">
  <input type="hidden" name="jform[id]" id="id" value="<?php echo $this->data->id; ?>">
</form>
