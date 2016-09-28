<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
$i = 0;
$itineraries = $this->data->getItinerary();
 
?>
<?php foreach($itineraries as $segment) : ?>
<table class="segment" width="100%">
      <tbody>

      
      <?php foreach($segment as $itinerary): ?>
      <thead>
      <tr>
        <th colspan="4">
          <?php echo JText::sprintf('AOM_SEGMENT_TITLE', ++$i);?>
        </th>
      </tr>
      </thead>
      <tr class="flight">
        <td class="label"><?php echo JText::_('AOM_AIRLINE'); ?></td>
        <td class="value"><?php echo $itinerary->airline; ?></td>
        <td class="label"><?php echo JText::_('AOM_FLIGHTNUMBER'); ?></td>
        <td class="value"><?php echo $itinerary->flightnumber; ?></td>
      </tr>
      <tr class="flight">
        <td class="label"><?php echo JText::_('AOM_TIPO_CABINA'); ?></td>
        <td class="value"><?php echo $itinerary->tipocabina; ?></td>
        <td class="label"><?php echo JText::_('AOM_TIPO_AVION'); ?></td>
        <td class="value"><?php echo $itinerary->tipoavion; ?></td>
      </tr>
      <tr class="flight">
        <td class="label"><?php echo JText::_('AOM_EXIT_DATE'); ?></td>
        <td class="value"><?php echo $itinerary->bdate; ?></td>
        <td class="label"><?php echo JText::_('AOM_RETURN_DATE'); ?></td>
        <td class="value"><?php echo $itinerary->edate; ?></td>
      </tr>
      <tr class="flight">
        <td class="label"><?php echo JText::_('AOM_ORIGIN'); ?></td>
        <td class="value">
        <?php
          echo $itinerary->blocationcode.' - '.
               $itinerary->blocationname.', '.
               $itinerary->blocationcity.
               ' ('.$itinerary->blocationcountry.
               ($itinerary->blocationstate != '' ? ' - '.$itinerary->blocationstate : '').
               ')';
        ?>
        </td>
        <td class="label"><?php echo JText::_('AOM_DESTINY'); ?></td>
        <td class="value">
        <?php
          echo $itinerary->elocationcode.' - '.
               $itinerary->elocationname.', '.
               $itinerary->elocationcity.
               ($itinerary->elocationstate != '' ? ' - '.$itinerary->elocationstate : '').
               ' ('.$itinerary->elocationcountry.')';
        ?>
        </td>
        </tr>
        <tr>
        <td class="label"><?php echo JText::_('AOM_TECH_STOP'); ?></td>
          <td class="value">
           <?php echo $itinerary->paradas; ?> 
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
</table>
<?php endforeach; ?>
