<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
$xmlstr=$this->data->getAditionalInfo();
$data = new SimpleXMLElement($xmlstr);
$passenger=$data->AdditionalInfo->Travelers;
?>
<fieldset class="adminform">
  <legend><?php echo JText::_('AOM_CONTACT_AND_PASSANGERS'); ?></legend>
  <table class="adminlist" width="100%">
    <thead>
      <tr>
        <th><?php echo JText::_('AOM_NAME'); ?></th>
        <th><?php echo JText::_('AOM_LASTNAME'); ?></th>
        <th><?php echo JText::_('AOM_TYPE'); ?></th>
        <th><?php echo JText::_('AOM_DOCTYPE'); ?></th>
        <th><?php echo JText::_('AOM_DOC'); ?></th> 
        <th><?php echo JText::_('AOM_BIRTHDAY'); ?></th>
        <th><?php echo JText::_('AOM_GENDER'); ?></th>
        <th><?php echo JText::_('AOM_NOVIAJEROFREC'); ?></th>
        <th><?php echo JText::_('AOM_AEROLINAVIAJEROFRECUENTE'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php
      foreach($passenger->TravelerInfo as $pax) { ?>
      <tr class="row0">
        <td style="height: auto;"><?php echo $pax->GivenName; ?></td>
        <td style="height: auto;"><?php echo $pax->Surname; ?></td>
        <td style="height: auto;"><?php echo JText::_("AOM_".$pax->PassengerType); ?></td>
        <?php
        if($pax->PassengerType=='ADT' || $pax->PassengerType=='YCD' ): ?>
            <td style="height: auto;"><?php echo JText::_("AOM_DOC_TYPE_".$pax->DocumentTypeId); ?></td>
        <?php endif;
        if($pax->PassengerType=='CHD' || $pax->PassengerType=='INF'): ?>
            <td style="height: auto;"><?php echo JText::_("AOM_TYPEDIF_".$pax->DocumentTypeId); ?></td>
        <?php endif; ?>
        <td style="height: auto;"><?php echo $pax->DocumentNumber; ?></td>
        <td style="height: auto;"><?php echo $pax->BithDate; ?></td>
        <td style="height: auto;"><?php echo JText::_('AOM_'.$pax->Gender); ?></td>
        <td style="height: auto;"><?php echo $pax->FrequentFlyerNumber; ?></td>
        <td style="height: auto;"><?php echo $pax->FrequentFlyerAirline; ?></td>
      </tr>
    <?php
      }
    ?>
    </tbody>
  </table>
</fieldset>
