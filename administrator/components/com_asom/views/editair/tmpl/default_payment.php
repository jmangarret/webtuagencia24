<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
$xmlstr=$this->data->getAditionalInfo();
$data = new SimpleXMLElement($xmlstr);
$TypeRequest=$data->AdditionalInfo->TypeRequest;
$PaymentTypeId=$data->AdditionalInfo->PaymentTypeId;
$passenger=$data->AdditionalInfo->Travelers;
/*echo '<pre>';
var_dump($data);
echo '</pre>';*/
?>
<fieldset class="adminform">
  <?php 
  if ($TypeRequest==1){//Reserva
      echo '<legend>'.JText::_('AOM_PAYMENTTYPE_REQUEST').'</legend>'; 
  }
  if ($TypeRequest==0){//Compra
    echo '<legend>'.JText::_('AOM_PAYMENTTYPE_'.$PaymentTypeId).'</legend>'; 
    if ($PaymentTypeId==0){//PAGO CON TARJETA
    ?>
          <table class="admintable">
            <tbody>
               <tr>
                <td style="width:10px;" class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_TIPO_TARJETA'); ?>: </label></td>
                <td><?php echo $data->AdditionalInfo->CreditCardType;?></td></tr>
                <td class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_NO_TARJETA'); ?></label></td>
                <td><?php echo base64_decode($data->AdditionalInfo->CreditCardNumber);?></td></tr>
                <td class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_FECVEN'); ?></label></td>
                <td><?php echo base64_decode($data->AdditionalInfo->CreditCardExpiration);?></td></tr>
                <td class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_CODSEG'); ?></label></td>
                <td><?php echo base64_decode($data->AdditionalInfo->CreditCardSecurityCode);?></td></tr>
                <td class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_NOMTIT'); ?></label></td>
                <td><?php echo $data->AdditionalInfo->CreditCardOwner;?></td></tr>
                <td class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_CEDTIT'); ?></label></td>
                <td><?php echo $data->AdditionalInfo->CreditCardDocumentNumber;?></td>
              </tr>
            </tbody>
          </table>
    <?php
    } 
    if ($PaymentTypeId==1){//TRANSFERENCIA
    ?>
          <table class="admintable">
            <tbody>
               <tr>
               <td style="width:10px;" class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_BANCO'); ?>: </label></td>
                <td><?php echo $data->AdditionalInfo->Bank;?></td>
                <td style="width:10px;" class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_CUENTA'); ?>: </label></td>
                <td><?php echo $data->AdditionalInfo->Acount;?></td>
                <td style="width:10px;" class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_TIPO'); ?>: </label></td>
                <td><?php echo $data->AdditionalInfo->TypeAcount;?></td>
                <td style="width:10px;" class="key"><label for="name" class="hasTip"><?php echo JText::_('AOM_CODIGO_TRANSFERENCIA'); ?>: </label></td>
                <td><?php echo $data->AdditionalInfo->Tranference;?></td></tr>
              </tr>
            </tbody>
          </table>
    <?php }  
    echo '<legend>'.JText::_('AOM_PAYMENT_FACTURA').'</legend>';

     ?>

          <table class="adminlist" width="100%">
            <thead>
              <tr>
                <th> </th>
                <th><?php echo JText::_('AOM_FACT_CIUDAD'); ?></th>
                <th><?php echo JText::_('AOM_FACT_URBAN'); ?></th>
                <th><?php echo JText::_('AOM_FACT_CODPOSTAL'); ?></th>
              </tr>
            </thead>
            <tbody>
              <tr class="row0">
                  <td style="height: auto;"><b>Envío Factura</b></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceCity; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceUrbanization; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InovicePostalCode; ?></td>
              </tr>
            </tbody>
          </table>


          <table class="adminlist" width="100%">
            <thead>
              <tr>
                <th><?php echo JText::_('AOM_FACT_ANOMBREDE'); ?></th>
                <th><?php echo JText::_('AOM_FACT_NOMEMPRESA'); ?></th>
                <th><?php echo JText::_('AOM_FACT_RIF'); ?></th>
                <th><?php echo JText::_('AOM_FACT_TELEMPRESA'); ?></th>
                <th><?php echo JText::_('AOM_FACT_UBIEMPRESA'); ?></th>
                <?php if ($data->AdditionalInfo->fapa != 'on') : ?>
                  <th><?php echo JText::_('AOM_FACT_CIUDAD'); ?></th>
                  <th><?php echo JText::_('AOM_FACT_URBAN'); ?></th>
                  <th><?php echo JText::_('AOM_FACT_CODPOSTAL'); ?></th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>

            <?php if ($data->AdditionalInfo->fapa != 'on') : ?>
              <tr class="row0">
                  <td style="height: auto;"><b>Envío Factura</b></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceCustomerName; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceDocumentNumber; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoicePhone; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceLocation; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceCity; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InvoiceUrbanization; ?></td>
                  <td style="height: auto;"><?php echo $data->AdditionalInfo->InovicePostalCode; ?></td>
              </tr>
            <?php else : ?>

                <?php foreach($passenger->TravelerInfo as $pax) { ?>
                  <tr class="row0">
                    <td style="height: auto;"><?php echo $pax->Treatment; ?></td>
                    <td style="height: auto;"><?php echo $pax->GivenName; ?> <?php echo $pax->Surname; ?></td>
                    <td style="height: auto;"><?php echo $pax->DocumentNumber; ?></td>
                    <td style="height: auto;"><?php echo $pax->Phone; ?></td>
                    <td style="height: auto;"><?php echo $pax->Address; ?></td>
                  </tr>
                <?php } ?>

            <?php endif; ?>



            </tbody>
          </table>
    
          
    <?php } ?>
    
</fieldset>
