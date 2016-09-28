<?php
/**
 * @paymentprocessor    JBusinessDirectory
 * @subpaymentprocessor  com_jhotereservation
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The HTML  View.

 */
if (!checkUserAccess(JFactory::getUser()->id,"payment_processors")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JHotelReservationViewPaymentProcessor extends JViewLegacy
{
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');

		$this->statuses = JHotelReservationHelper::getStatuses();
		$this->modes = JHotelReservationHelper::getModes();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		
		parent::display($tpl);
		
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = JHotelReservationHelper::getActions();
		$user  = JFactory::getUser();
		
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'LNG_NEW_PAYMENT_PROCESSOR' : 'LNG_EDIT_PAYMENT_PROCESSOR',true), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('paymentprocessor.apply');
			JToolbarHelper::save('paymentprocessor.save');
		}
		
		JToolbarHelper::cancel('paymentprocessor.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JHOTELRESERVATION_PAYMENT_PROCESSOR_EDIT');
	}
	
}
