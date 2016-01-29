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
defined('_JEXEC') or die('Restricted access');

class modJWeekOfferHelper
{

	static function getItem($params)
	{
		$db = JFactory::getDBO();
		$offerId = $params->get('offerId');
	
		$query = "select *, if(ofr.price_type_day = 1,		
						if(min(orp.price),
							least(orp.price,ofr.price_1, ofr.price_2, ofr.price_3, ofr.price_4, ofr.price_5, ofr.price_6, ofr.price_7),
							least(ofr.price_1, ofr.price_2, ofr.price_3, ofr.price_4, ofr.price_5, ofr.price_6, ofr.price_7)),
							if(min(orp.price),
							least(orp.price,ofr.price_1, ofr.price_2, ofr.price_3, ofr.price_4, ofr.price_5, ofr.price_6, ofr.price_7)* of.offer_min_nights,
							least(ofr.price_1, ofr.price_2, ofr.price_3, ofr.price_4, ofr.price_5, ofr.price_6, ofr.price_7)* of.offer_min_nights)
							 )  as starting_price 
						from #__hotelreservation_offers of
						inner join #__hotelreservation_hotels h on h.hotel_id=of.hotel_id
						inner join #__hotelreservation_countries c on h.country_id=c.country_id
						left join #__hotelreservation_offers_rooms ofrr on ofrr.offer_id = of.offer_id 
						left join #__hotelreservation_offers_rates ofr on ofr.offer_id = of.offer_id and ofr.room_id = ofrr.room_id
						left join #__hotelreservation_offers_rate_prices orp on orp.rate_id = ofr.id
						left join #__hotelreservation_currencies hcr on h.currency_id = hcr.currency_id
						left join #__hotelreservation_offers_pictures op on op.offer_id=of.offer_id
						where of.offer_id=$offerId";
		//dmp($query);
		$db->setQuery($query);
		return $db->loadObject();
	}
}
?>
