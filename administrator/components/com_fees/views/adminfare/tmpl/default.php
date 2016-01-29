<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
JHtml::_('behavior.tooltip');

if($this->data->published != null)
{
    $published   = $this->data->published == '1' ? 'checked="checked"' : '';
    $unpublished = $this->data->published == '0' ? 'checked="checked"' : '';
}
else
{
    $published = 'checked="checked"';
    $unpublished = '';
}

if($this->data->valuetype != null)
{
    $percent = $this->data->valuetype == 'P' ? 'checked="checked"' : '';
    $value   = $this->data->valuetype == 'V' ? 'checked="checked"' : '';
}
else
{
    $percent = '';
    $value   = 'checked="checked"';
}

?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete="off">
  <div class="width-100">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_ADMINFARE_INFO'); ?></legend>
      <ul class="adminformlist">
        <li>
          <label for="jformusergroupid" class="hasTip" title="<?php echo JText::_('COM_ADMINFARE_AIRLINE_LABEL').'::'.JText::_('COM_ADMINFARE_AIRLINE_DESC'); ?>">
            <?php echo JText::_('COM_ADMINFARE_AIRLINE_LABEL'); ?>
          </label>
          <?php if($this->data->id > 0 && $this->data->airline != ''): ?>
            <fieldset class="radio">
              <label><b><?php echo $this->getAirlineName($this->data->airline); ?></b></label>
              <input type="hidden" name="jform[airline]" value="<?php echo $this->data->airline; ?>" />
            </fieldset>
          <?php else: ?>
            <?php echo $this->getAirlines(); ?>
          <?php endif; ?>
          </li>
          <li>
           <label class="hasTip" title="<?php echo JText::_('COM_ADMINFARE_ALLPASSENGERS_LABEL').'::'.JText::_('COM_ADMINFARE_VALUETYPE_DESC'); ?>">
            <?php echo JText::_('COM_ADMINFARE_ALLPASSENGERS_LABEL'); ?>
          </label> 
          <?php if($this->data->all==1):?>
          <input type="checkbox"  id="all" name="jform[all]" value="1" checked>
          <?php else:?>
          <input type="checkbox"  id="all" name="jform[all]" value="0">
          <?php endif;?>
        </li>

        <li>
          <label class="hasTip" title="<?php echo JText::_('COM_ADMINFARE_VALUETYPE_LABEL').'::'.JText::_('COM_ADMINFARE_VALUETYPE_DESC'); ?>">
            <?php echo JText::_('COM_ADMINFARE_VALUETYPE_LABEL'); ?>
          </label>
          <fieldset class="radio">
            <input type="radio" name="jform[valuetype]" id="percent" value="P" <?php echo $percent; ?> />
            <label for="percent"><?php echo JText::_('COM_FEE_PERCENT'); ?></label>
            <input type="radio" name="jform[valuetype]" id="value" value="V" <?php echo $value; ?> />
            <label for="value"><?php echo JText::_('COM_FEE_VALUE'); ?></label>
          </fieldset>
        </li>

        <li>
          <label class="hasTip" title="<?php echo JText::_('COM_ADMINFARE_PUBLISHED_LABEL').'::'.JText::_('COM_ADMINFARE_PUBLISHED_DESC'); ?>">
            <?php echo JText::_('COM_ADMINFARE_PUBLISHED_LABEL'); ?>
          </label>
          <fieldset class="radio">
            <input type="radio" name="jform[published]" id="published" value="1" <?php echo $published; ?> />
            <label for="published"><?php echo JText::_('JYES'); ?></label>
            <input type="radio" name="jform[published]" id="unpublished" value="0" <?php echo $unpublished; ?> />
            <label for="unpublished"><?php echo JText::_('JNO'); ?></label>
          </fieldset>
        </li>
      </ul>

      <div style="clear: both;"></div>
      <br />

      <div id="tabs">
        <ul>
          <li><a href="#ow-nal"><?php echo JText::_('COM_ADMINFARE_ONEWAY_NAL'); ?></a></li>
          <li><a href="#rt-nal"><?php echo JText::_('COM_ADMINFARE_ROUNDTRIP_NAL'); ?></a></li>
          <li><a href="#ow-int"><?php echo JText::_('COM_ADMINFARE_ONEWAY_INTER'); ?></a></li>
          <li><a href="#rt-int"><?php echo JText::_('COM_ADMINFARE_ROUNDTRIP_INTER'); ?></a></li>
        </ul>
        <div id="ow-nal">
          <?php $this->gridType = 'ON'; ?>
          <?php echo $this->loadTemplate('grid'); ?>
        </div>
        <div id="rt-nal">
          <?php $this->gridType = 'RN'; ?>
          <?php echo $this->loadTemplate('grid'); ?>
        </div>
        <div id="ow-int">
          <?php $this->gridType = 'OI'; ?>
          <?php echo $this->loadTemplate('grid'); ?>
        </div>
        <div id="rt-int">
          <?php $this->gridType = 'RI'; ?>
          <?php echo $this->loadTemplate('grid'); ?>
        </div>
      </div>
    </fieldset>
  </div>
  <input type="hidden" name="task" value="">
  <input type="hidden" name="jform[id]" id="id" value="<?php echo $this->data->id; ?>">
</form>
 