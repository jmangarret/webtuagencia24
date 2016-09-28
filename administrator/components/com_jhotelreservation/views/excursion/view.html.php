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

if (!checkUserAccess(JFactory::getUser()->id,"manage_excursions")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}
JHTML::_( 'behavior.calendar' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'hoteltranslations.php');

jimport('joomla.application.component.model');  

class JHotelReservationViewExcursion extends JViewLegacy{

	protected $form;
	protected $item;
	protected $state;
	protected $hotel;


	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		//$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');
		$this->state = $this->get('State');
		$this->hotel = $this->get('Hotel');
		$this->pictures	= $this->get('ExcursionPictures');
		
		$this->appSettings = JHotelUtil::getInstance()->getApplicationSettings();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		//get hotel translations
		$hoteltranslationsModel = new JHotelReservationModelhoteltranslations();
		$this->translations = $hoteltranslationsModel->getAllTranslations(EXCURSION_TRANSLATION, $this->item->id);
		
		$this->includeFunctions();
		
		$appSettings = JHotelUtil::getApplicationSettings();
		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$canDo = JHotelReservationHelper::getActions();
		
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title(JText::_('LNG_EXCURSION',true)." : ".(!$isNew ? JText::_('LNG_EDIT',true): JText::_('LNG_ADD_NEW',true)), 'menu.png');

		if ($canDo->get('core.create')){
			JToolBarHelper::apply('excursion.apply');
			JToolBarHelper::save('excursion.save');
			JToolBarHelper::save2new('excursion.save2new');
		}
			
		if(!$isNew){
			JToolBarHelper::custom('excursion.editrateprices', 'stats', 'stats', 'JTOOLBAR_EDIT_RATE_DETAILS',false);
		}
		JToolBarHelper::cancel('excursion.cancel');
		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_EXCURSION_EDIT');

	}
	
	function includeFunctions(){
		$doc =JFactory::getDocument();
		$doc->addScript('components/'.getBookingExtName().'/assets/js/jquery.upload.js');
		$doc->addStyleSheet('components/'.getBookingExtName().'/assets/js/datepicker/css/datepicker.css');
		$doc->addStyleSheet('components/'.getBookingExtName().'/assets/js/datepicker/css/layout.css');

		$doc->addScript('components/'.getBookingExtName().'/assets/js/datepicker/js/eye.js' );
		$doc->addScript('components/'.getBookingExtName().'/assets/js/datepicker/js/datepicker.js' );
		$doc->addScript('components/'.getBookingExtName().'/assets/js/datepicker/js/utils.js' );
		$doc->addScript('components/'.getBookingExtName().'/assets/js/datepicker/js/layout.js' );
	
	}
}

