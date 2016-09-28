<?php // no direct access
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

defined('_JEXEC') or die('Restricted access'); 

?>

<div class="hotel_reservation">
<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="userForm" >
	<div class="thank-you-holder">
		<?php 
			$text=  JText::_('LNG_THANK_YOU_CONFIRMATION');
			$text = str_replace("<<hotelname>>", $this->reservation->reservationData->hotel->hotel_name, $text);
			$text = str_replace("<<e-mail adress>>", $this->reservation->reservationData->userData->email, $text);
			echo $text;
		?>
	</div>
	<?php 
	/*-------------------------------------------------------*/
	//MICOD: Código original:
	//echo $this->reservation->reservationInfo
	/*-------------------------------------------------------*/
	echo $this->reservation->reservationData->userData->confirmation_details
	?>
	<BR>
</form>	
<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="userForm_new" >
	<div class="hotel_reservation">
		<a class="ui-hotel-button" href="<?php echo JHotelUtil::getHotelLink($this->reservation->reservationData->hotel) ?>">
			<span class="ui-button-text"><?php echo isset($this->reservation->reservationData->hotel->types) & $this->reservation->reservationData->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_BACK_TO_PARK'): JText::_('LNG_BACK_TO_HOTEL')?></span>
		</a>
	</div>
	<input type="hidden" name="task" id="task" value="" />
</form>
</div>
	