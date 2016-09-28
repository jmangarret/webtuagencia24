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
// No direct access to this file
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');

class JHotelReservationViewHotelRatings extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->reservationDetails = $this->get('ReservationDetails');
		$this->customerReview = $this->get('CustomerReview');
		$this->reviewQuestions = $this->get('ReviewQuestions');
		$this->reviewRatingScales = $this->get('ReviewRatingScale');
		if (JRequest::getVar('layout') =='printrating'){
			$this->customerReview = $this->get('CustomerReviewById');
			$ratingAnswers = $this->get('ReviewAnswers');
			$this->ratingAnswers = $ratingAnswers;
			$tpl = 'printrating';
		}
	
		parent::display($tpl);
	}
}
?>
