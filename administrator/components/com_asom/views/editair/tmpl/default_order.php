<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

$order = $this->data->getOrder();
$user = JFactory::getUser($order->user_id);
?>
<fieldset class="adminform">
  <legend><?php echo JText::_('AOM_GENERAL_INFORMATION'); ?></legend>
  <table class="admintable">
    <tbody>
       <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_ORDERID'); ?> : </label>
        </td>
        <td><?php echo $order->id; ?></td>
      </tr>
      <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_USERNAME'); ?> : </label>
        </td>
        <td><?php echo $user->get('name', JText::_('PUBLIC_USER')); ?></td>
      </tr>
      <tr>
        <td class="key">
          <label for="email" class="hasTip"><?php echo JText::_('AOM_EMAIL'); ?> : </label>
        </td>
        <td>
        <a href="mailto:<?php echo $user->get('email', ''); ?>"><?php echo $user->get('email', ''); ?></a>
        <input type="hidden" name="mail_usuario" value="<?php echo $user->get('email', ''); ?>">
        </td>
      </tr>
      <tr>
        <td class="key">
          <label for="total" class="hasTip"><?php echo JText::_('AOM_TOTAL'); ?> : </label>
        </td>
        <td><b><?php echo number_format($order->total, 2, '.', ','); ?></b></td>
      </tr>
      <tr>
        <td class="key">
          <label for="estado" class="hasTip"><?php echo JText::_('AOM_STATUS'); ?> : </label>
        </td>
        <td>
          <?php echo $this->status[$order->status]['name']; ?>
        </td>
      </tr>
      <tr>
        <td class="key">
          <label for="estado" class="hasTip"><?php echo JText::_('AOM_STATUS_CAMB'); ?> : </label>
        </td>
        <td>
          <?php echo $this->filterStatus($order->status);
          echo '<div style="clear:both"><input type="checkbox" name="enviar_mail" value="si">';
          echo JText::_('AOM_SEND_MAIL').'</div>
               <input type="hidden" id="old_state" name="old_state" value="'.$order->status.'">
               <input type="hidden" id="old_state_name" name="old_state_name" value="'.$this->status[$order->status]['name'].'">';?>
        </td>
      </tr>
      <tr>
        <td class="key">
          <label for="nota" class="required"><?php echo JText::_('AOM_NOTE'); ?> : </label>
        </td>
        <td>
          <textarea name="nota" id="nota" rows="5" cols="40"><?php echo $order->note; ?></textarea>
        </td>
      </tr>
    </tbody>
  </table>
</fieldset>
