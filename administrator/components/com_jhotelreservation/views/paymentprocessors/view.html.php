<?php
/**
 * @order     Joomlp.Administrator
 * @suborder  com_jhotereservation
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The HTML Menus Menu Menus View.
 *
 * @order     Joomlp.Administrator
 * @suborder  com_jhotereservation

 */
if (!checkUserAccess(JFactory::getUser()->id,"payment_processors")){
	$msg = "You are not authorized to access this resource";
	$this->setRedirect( 'index.php?option='.getBookingExtName(), $msg );
}

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JHotelReservationViewPaymentProcessors extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		$this->statuses		= JHotelReservationHelper:: getStatuses();
		
		JHotelReservationHelper::addSubmenu('paymentprocessors');

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
		
		JToolBarHelper::title(   'J-HotelReservation : '.JText::_('LNG_PAYMENT_PROCESSORS',true), 'generic.png' );
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_jhotereservation', 'core.create'))) > 0 )
		{
			JToolbarHelper::addNew('paymentprocessor.add');
		}
		
		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('paymentprocessor.edit');
		}
		
		if($canDo->get('core.delete')){
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'paymentprocessors.delete');
		}
				
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_jhotelreservation');
		}
		
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'paymentprocessors.back', JHotelUtil::getDashboardIcon(), 'preview.png', JText::_('LNG_HOTEL_DASHBOARD',true), false, false );
		JToolbarHelper::help('JHELP_COMPANIES');
	}
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
				'p.status' => JText::_('JSTATUS',true),
				'p.name' => JText::_('JGLOBAL_TITLE',true),
				'p.id' => JText::_('JGRID_HEADING_ID',true)
		);
	}
}
