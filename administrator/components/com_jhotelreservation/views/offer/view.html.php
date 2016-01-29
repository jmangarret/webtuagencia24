<?php
/**
 * @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
if (!checkUserAccess(JFactory::getUser()->id,"manage_offers")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}
JHTML::_('script','administrator/components/'.getBookingExtName().'/assets/js/jquery.selectlist.js');
JHTML::_('script','administrator/components/'.getBookingExtName().'/assets/js/jquery.upload.js');
JHTML::_('script','administrator/components/'.getBookingExtName().'/assets/js/manageextraoptions.js');

jimport('joomla.html.pane');

class JHotelReservationViewOffer extends JViewLegacy
{
	function display($tpl = null){
		
		$this->appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		
		$this->item = $this->get('Item');
		$this->extraOptions =  $this->get('ExtraOptions');
		$this->pictures=  $this->get('OfferPictures');
		$this->excursions =  $this->get('Excursions');
		
		
		//get offer translations
		$hoteltranslationsModel = new JHotelReservationModelhoteltranslations();
		$this->offerShortDescTranslations = $hoteltranslationsModel->getAllTranslations(OFFER_SHORT_TRANSLATION, $this->item->offer_id);
		$this->offerTranslations = $hoteltranslationsModel->getAllTranslations(OFFER_TRANSLATION, $this->item->offer_id);
		$this->offerContentTranslations = $hoteltranslationsModel->getAllTranslations(OFFER_CONTENT_TRANSLATION, $this->item->offer_id);
		$this->offerOtherInfoTranslations = $hoteltranslationsModel->getAllTranslations(OFFER_INFO_TRANSLATION, $this->item->offer_id);
		
		JToolBarHelper::title(    'J-Hotel Reservation : '.JText::_($this->item->offer_id > 0? "LNG_EDIT" : "LNG_ADD_NEW",true).' '.JText::_('LNG_OFFER' ,true), 'generic.png' );
		$hotel_id =  $this->get('HotelId');
		$this->hotel_id =  $hotel_id;
			
		$this->includeFunctions();	
		parent::display($tpl);
		
		$this->addToolbar();
	}
	
	protected function addToolbar()
	{
		$canDo = JHotelReservationHelper::getActions();
		
		JRequest::setVar( 'hidemainmenu', 1 );
		if($this->item->state==0 || isSuperUser(JFactory::getUser()->id)){
			if ($canDo->get('core.create')){
				JToolBarHelper::apply('offer.apply');
				JToolBarHelper::save('offer.save');
				JToolBarHelper::custom('offer.saveAsNew', 'save.png', 'save.png', 'Duplicate',false, false );
			}
		}
		JToolBarHelper::cancel('offer.cancel');
	}
	
	function includeFunctions(){
		$doc =JFactory::getDocument();
		$doc->addStyleSheet('components/'.getBookingExtName().'/assets/js/validation/css/validationEngine.jquery.css' );
		$tag = JHotelUtil::getJoomlaLanguage();
		$doc->addScript('components/'.getBookingExtName().'/assets/js/validation/js/languages/jquery.validationEngine-'.$tag.'.js');
		$doc->addScript('components/'.getBookingExtName().'/assets/js/validation/js/jquery.validationEngine.js');
		$doc->addScript('components/'.getBookingExtName().'/assets/js/jquery.selectlist.js');
	
	}
	
	
}