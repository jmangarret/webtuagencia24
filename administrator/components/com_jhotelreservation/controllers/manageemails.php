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

class JHotelReservationControllerManageEmails extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'state', 'state');  
		$this->registerTask( 'add', 'edit');   
	}
	
	function show()
	{
		JRequest::setVar( 'view', 'manageemails' );
		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	
	function apply(){
       $this->save();
	}
	
	function save()
	{
		$model = $this->getModel('manageemails');

		$post = JRequest::get( 'post' );
		$post['email_content'] = JRequest::getVar('email_content', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$task     = $this->getTask();
		

		if( JHotelUtil::checkIndexKey( '#__hotelreservation_emails', array('hotel_id' => $post['hotel_id'] ,'email_name' => $post['email_name']) , 'email_id', $post['email_id'] ) )
		{
			$msg = '';
			JError::raiseWarning( 500, JText::_('LNG_EMAIL_NAME_EXISTENT',true));
			$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageemails&view=manageemails&task=add&hotel_id='.$post['hotel_id'], $msg );
		}
		else if ($model->store($post)) 
		{
			$post["email_id"] = $model->_email_id;
			$model->saveEmailContent($post);
		} 
		else 
		{
			$msg = "";
			JError::raiseWarning( 500, JText::_('LNG_ERROR_SAVING_EMAIL',true) );
			$this->setRedirect( 'index.php?option='.getBookingExtName().'&task=manageemails.edit&email_id[]='.$model->_email_id.'&hotel_id='.$post['hotel_id'], $msg );	
		}
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$msg = JText::_('LNG_EMAIL_SAVED',true);
				$this->setRedirect( 'index.php?option='.getBookingExtName().'&task=manageemails.edit&email_id[]='.$model->_email_id.'&hotel_id='.$post['hotel_id'], $msg );
				break;
		
			default:
				$msg = JText::_('LNG_EMAIL_SAVED',true);
				// Redirect to the list screen.
				$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageemails&view=manageemails&hotel_id='.$post['hotel_id'], $msg );
				break;
		}

		// Check the table in so it can be edited.... we are done with it anyway
		
		
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		
		$msg = JText::_('LNG_OPERATION_CANCELLED',true);
		$post 		= JRequest::get( 'post' );
		if( !isset($post['hotel_id']) )
			$post['hotel_id'] = 0;
			
		$this->setRedirect( 'index.php?option='.getBookingExtName().'&view=manageemails&hotel_id='.$post['hotel_id'], $msg );
	}
	
	function delete()
	{
		$model = $this->getModel('manageemails');
		$post = JRequest::get( 'post' );
		if( !isset($post['hotel_id']) )
			$post['hotel_id'] = 0;

		if ($model->remove()) {
			$msg = JText::_('LNG_EMAIL_HAS_BEEN_DELETED',true);
		} else {
			$msg = JText::_('LNG_ERROR_DELETE_EMAIL',true);
		}

		// Check the table in so it can be edited.... we are done with it anyway
		
		$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageemails&view=manageemails&hotel_id='.$post['hotel_id'], $msg );
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'manageemails' );
		
	
		parent::display(); 
		
	}
	
	function add()
	{
		JRequest::setVar( 'view', 'manageemails' );
		
	
		parent::display(); 
		
	}
	
	function state()
	{
		$model = $this->getModel('manageemails');
		$get = JRequest::get( 'get' );
		if( !isset($get['hotel_id']) )
			$get['hotel_id'] = 0;

		if ($model->state()) {
			$msg = JText::_( '' ,true);
		} else {
			$msg = JText::_('LNG_ERROR_CHANGE_EMAIL_STATE',true);
		}

		$this->setRedirect( 'index.php?option='.getBookingExtName().'&controller=manageemails&view=manageemails&hotel_id='.$get['hotel_id'], $msg );
	}
}