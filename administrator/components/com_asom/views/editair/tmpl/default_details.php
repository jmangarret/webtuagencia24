<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */

$pane = JPane::getInstance('sliders');
$order = $this->data->getOrder();
$info  = $this->data->getContactInformation();
$proveedor = $this->data->getProveedor();
?>
<fieldset class="adminform">
  <legend><?php echo JText::_('AOM_BOOKING_DETAIL'); ?></legend>
  <table class="admintable">
    <tbody>
       <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_SEND_PRODUCT_TYPE'); ?> : </label>
        </td>
        <td><?php echo $order->product_type; ?></td>
      </tr>
      <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_SEND_PRODUCT_NAME'); ?> : </label>
        </td>
        <td><?php echo $order->product_name; ?></td>
      </tr>
       <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_RECLOC'); ?> : </label>
        </td>
        <td><?php echo $info->recloc; ?></td>
      </tr>
      <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_PROVEEDOR_NAME'); ?> : </label>
        </td>
        <td><?php echo $proveedor[0]->dtl_name; ?></td>
      </tr>
      <tr>
        <td class="key">
          <label for="name" class="hasTip"><?php echo JText::_('AOM_PROVEEDOR_CODE'); ?> : </label>
        </td>
        <td><?php echo $proveedor[0]->dtl_cod; ?></td>
      </tr>
    </tbody>
  </table>
  <?php
    echo $pane->startPane("detail-booking");
    //Detalle del vuelo
    echo $pane->startPanel(JText::sprintf('AOM_ITINERARY', ''), "itinerary-page");
    echo $this->loadTemplate('itinerary');
    echo $pane->endPanel();
    //tarifas
    echo $pane->startPanel(JText :: _('AOM_TAXES'), "taxes-page");
    echo $this->loadTemplate('values');
    echo $pane->endPanel();
    //Forma de pago
    echo $pane->startPanel(JText :: _('AOM_PAYMENT'), "payment-page");
    echo $this->loadTemplate('payment');
    echo $pane->endPanel();
    //Historial
    echo $pane->startPanel(JText :: _('AOM_HISTORY'), "history-page");
    echo $this->loadTemplate('history');
    echo $pane->endPanel();

    echo $pane->endPane();
  ?>
</fieldset>
