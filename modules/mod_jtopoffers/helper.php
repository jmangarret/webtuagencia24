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

class modJTopOffersHelper
{
	static function getItems($params)
	{
		$db = JFactory::getDBO();
		$noOffers = $params->get('noOffers');
		
		$language = JFactory::getLanguage();
		$languageTag = $language->getTag();
		
		$voucherFilter = " (hov.voucher is null or hov.voucher='' or public = 1)";
		
		$query = "select *, hlt.content as offer_short_description,
					if(ofr.price_type_day = 1,		
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
					left  join #__hotelreservation_offers_vouchers hov on of.offer_id = hov.offerId  
					left  join #__hotelreservation_currencies hcr on h.currency_id = hcr.currency_id
					left join #__hotelreservation_offers_pictures hop on hop.offer_id = of.offer_id
					left join (
							select * from 
							 #__hotelreservation_language_translations 
							 where type = ".OFFER_SHORT_TRANSLATION."
							 and language_tag = '$languageTag'
							)as hlt on hlt.object_id =  of.offer_id
					where $voucherFilter  and now() between of.offer_datasf and of.offer_dataef and h.is_available = 1 and of.is_available = 1 and of.top=1
					group by of.offer_id 
				";

		$db->setQuery($query, 0, 10);
		$offers = $db->loadObjectList();
		//dump($db->getErrorMsg());
		if(count($offers)){
			foreach($offers as $offer){
				if($offer->price_type == 0){
					$offer->starting_price = $offer->starting_price/ $offer->base_adults;
				}
			}
		}
		
		shuffle($offers);
		$offers= array_slice($offers,0,$noOffers);
		return $offers;
	}
}
?>
