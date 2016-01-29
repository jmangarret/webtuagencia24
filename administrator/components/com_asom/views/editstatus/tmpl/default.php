<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

$checked_1 = $this->data->default_status == 1 ? 'checked="checked"' : '';
$checked_2 = $this->data->default_status == 0 ? 'checked="checked"' : '';
?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete="off">
  <fieldset>
    <legend><?php echo JText::_('AOM_STATUS_INFO'); ?></legend>
    <table class="admintable">
      <tbody>
        <tr>
          <td width="185" class="key">
            <label for="name"><?php echo JText::_('AOM_NAME'); ?></label>
          </td>
          <td><input type="text" name="name" id="name" size="50" value="<?php echo $this->data->name; ?>" /></td>
        </tr>
        <tr>
          <td width="185" class="key">
            <label for="color"><?php echo JText::_('AOM_COLOR'); ?></label>
          </td>
          <td><input type="text" name="color" id="color" size="10" value="<?php echo $this->data->color; ?>" /></td>
        </tr>
        <tr>
          <td width="185" class="key">
            <label for="default_1"><?php echo JText::_('AOM_DEFAULT'); ?></label>
          </td>
          <td>
          <input type="radio" name="default_status" id="default_1" value="1" <?php echo $checked_1; ?> />
            <label for="default_1"><?php echo JText::_('YES'); ?></label>
            <input type="radio" name="default_status" id="default_2" value="0" <?php echo $checked_2; ?> />
            <label for="default_2"><?php echo JText::_('NO'); ?></label>
          </td>
        </tr>
      </tbody>
    </table>
  </fieldset>
  <input type="hidden" name="task" value="">
  <input type="hidden" name="id" id="id" value="<?php echo $this->data->id; ?>">
</form>
