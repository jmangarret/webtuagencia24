<?php
JLoader::register('AawsHelperRoute', JPATH_SITE.DS.'components'.DS.'com_aaws'.DS.'helpers'.DS.'route.php');

$clases   = array();
$clases[] = JHTML::_('select.option', 'Economy', JText::_('MOD_AAWS_TURIST'));
$clases[] = JHTML::_('select.option', 'First', JText::_('MOD_AAWS_FIRST'));
$clases[] = JHTML::_('select.option', 'Business', JText::_('MOD_AAWS_BUSINESS'));

$stops   = array();
$stops[] = JHTML::_('select.option', '', JText::_('MOD_AAWS_SELECT_STOPS'));
$stops[] = JHTML::_('select.option', '0', JText::_('MOD_AAWS_DIRECT'));
$stops[] = JHTML::_('select.option', '1', JText::_('MOD_AAWS_ONE_STOP'));
$stops[] = JHTML::_('select.option', '2', JText::_('MOD_AAWS_TWO_STOPS'));
$origin=$params->get('qs_origin');
$selectOrigen = "";
if($params->get('qs_origin')==2){
  $lcountry= $params->get('local_country');
  $selectOrigen =	AawsQsHelper::getSelectListOrigen($lcountry);
}
?>
<form action="<?php echo JRoute::_(AawsHelperRoute::getFlowRoute('air.availability'), false); ?>" method="post" autocomplete="off">
  <div class="options">
    <ul>
      <li>
        <label for="tt-round-trip"><?php echo JText::_('MOD_AAWS_ROUND_TRIP'); ?></label>
        <input type="radio" id="tt-round-trip" name="<?php AawsQsHelper::form('TRIP_TYPE'); ?>" value="R" checked="checked"/>
      </li>
      <li>
        <label for="tt-one-way"><?php echo JText::_('MOD_AAWS_ONE_WAY'); ?></label>
        <input type="radio" id="tt-one-way" name="<?php AawsQsHelper::form('TRIP_TYPE'); ?>" value="O"/>
      </li>
      <li>
        <label for="tt-multiple"><?php echo JText::_('MOD_AAWS_MULTIPLE'); ?></label>
        <input type="radio" id="tt-multiple" name="<?php AawsQsHelper::form('TRIP_TYPE'); ?>" value="M"/>
      </li>
    </ul>

  </div>

  <div class="itinerary">
    <div class="segment-1">
      <div class="m-header" style="display: none;">
        <div class="m-title"><?php echo JText::sprintf('MOD_AAWS_SEGMENT', 1); ?></div>
        <div class="m-close"></div>

      </div>
      <div class="cities">
       <div class="field-1">
        <label id="departure-1-lbl" for="departure-1"><?php echo JText::_('MOD_AAWS_B_LOCATION_LABEL'); ?></label>
        <div id="combito"></div>
        <input type="hidden" class="required" name="<?php AawsQsHelper::form('B_LOCATION_1'); ?>" id="h-departure-1"/>
        <input type="text" id="departure-1" class="complete-air"/>		       
      </div>
      <div class="field-1">
        <label id="arrival-1-lbl" for="arrival-1"><?php echo JText::_('MOD_AAWS_E_LOCATION_LABEL'); ?></label>
        <input type="hidden" class="required" name="<?php AawsQsHelper::form('E_LOCATION_1'); ?>" id="h-arrival-1"/>
        <input type="text" id="arrival-1" class="complete-air"/>
      </div>

    </div>
    <div class="dates">
      <div class="field-1">
        <label id="departure_date-1-lbl" for="departure_date-1"><?php echo JText::_('MOD_AAWS_B_DATE_LABEL'); ?></label>
        <input type="hidden" class="required" name="<?php AawsQsHelper::form('B_DATE_1'); ?>" id="h-departure_date-1"/>
        <input type="text" id="departure_date-1" readonly="readonly" class="datepicker-air air-ins-1"/>
      </div>
      <div class="field-2">
        <label id="departure_date-2-lbl" for="departure_date-2"><?php echo JText::_('MOD_AAWS_E_DATE_LABEL'); ?></label>
        <input type="hidden" class="required" name="<?php AawsQsHelper::form('B_DATE_2'); ?>" id="h-departure_date-2"/>
        <input type="text" id="departure_date-2" readonly="readonly" class="datepicker-air air-ins-2"/>
      </div>
    </div>
  </div>
</div>

<div class="airline-box">
  <div class="clase">
    <label id="clase-lbl" for="clase"><?php echo JText::_('MOD_AAWS_CLASE_LABEL'); ?></label>
    <?php echo JHTML::_('select.genericlist', $clases, AawsQsHelper::form('CABIN', false), '', 'value', 'text', 'Economy', 'clase'); ?>
  </div>
  <div class="airline">
    <label id="airline-lbl" for="airline"><?php echo JText::_('MOD_AAWS_AIRLINE_LABEL'); ?></label>
    <input type="hidden" name="<?php AawsQsHelper::form('AIRLINE'); ?>" id="h-airline"/>
    <input type="text" id="airline" class="complete-airline"/>
  </div>
</div>

<div class="passengers">
  <div class="field">
    <label id="adults-lbl" for="adults"><?php echo JText::_('MOD_AAWS_TRAVELLER_TYPE_ADT_LABEL'); ?></label>
    <?php echo JHTML::_('select.integerlist', 0, 9, 1, AawsQsHelper::form('TRAVELLER_TYPE_ADT', false), 'id="adults"', 1); ?>
  </div>
  <div class="field">
    <label id="seniors-lbl" for="seniors"><?php echo JText::_('MOD_AAWS_TRAVELLER_TYPE_YCD_LABEL'); ?></label>
    <?php echo JHTML::_('select.integerlist', 0, 9, 1, AawsQsHelper::form('TRAVELLER_TYPE_YCD', false), 'id="seniors"', 0); ?>
  </div>
  <div class="field">
    <label id="children-lbl" for="children"><?php echo JText::_('MOD_AAWS_TRAVELLER_TYPE_CHD_LABEL'); ?></label>
    <?php echo JHTML::_('select.integerlist', 0, 9, 1, AawsQsHelper::form('TRAVELLER_TYPE_CHD', false), 'id="children"', 0); ?>
  </div>

  <div class="field">
    <label id="babies-lbl" for="babies"><?php echo JText::_('MOD_AAWS_TRAVELLER_TYPE_INF_LABEL'); ?></label>
    <?php echo JHTML::_('select.integerlist', 0, 9, 1, AawsQsHelper::form('TRAVELLER_TYPE_INF', false), 'id="babies"', 0); ?>
  </div>

</div>

<!--<input type="hidden" id="search" value="buscador"/>-->


<div class="button">
  <input type="submit" value="<?php echo JText::_('MOD_AAWS_SUBMIT_BUTTON'); ?>" id="accion"/>
</div>
<input type="hidden" name="<?php AawsQsHelper::form('ajax'); ?>" value="0"/>
<input type="hidden" id="tipo" value="<?php echo $origin;?>" />
<input type="hidden" id="combo" value="<?php echo $selectOrigen;?>" />
<div class="clear"></div>
</form>
