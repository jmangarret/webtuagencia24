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

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="custom special-offer">
	<div>
		<p>
			<a href="<?php echo JHotelUtil::getHotelLink($offer).'?resetSearch=1&minNights='.$offer->offer_min_nights?>" title="<?php echo $offer->hotel_name?>">
				<strong><?php echo $params->get('title')?></strong>
			</a>
		</p>
		
		<p>
			<?php echo $params->get('offerIntro')?>
		</p>
		
		<div class="row-fluid">
			<div class="offer-image-container span5">
				<div class="offer-image">
					<a href="<?php echo JHotelUtil::getHotelLink($offer).'?resetSearch=1&minNights='.$offer->offer_min_nights?>" title="<?php echo $offer->hotel_name?>">
						<img alt="<?php echo $offer->offer_picture_info ?>" src="<?php echo JURI::root().PATH_PICTURES.$offer->offer_picture_path?>"
										title="<?php echo $offer->offer_picture_info ?>"> 
					</a>
					<div class="info" onclick="document.location.href='<?php echo JHotelUtil::getHotelLink($offer) ?>'">
						<h2><?php echo $params->get('title')?></h2>
						<div class="hover_info">
							<a href="<?php echo JHotelUtil::getHotelLink($offer).'?resetSearch=1&minNights='.$offer->offer_min_nights?>" title="<?php echo $params->get('title')?>" class="btn" rel="nofollow noindex"><?php echo JText::_("LNG_BOOK")?></a>
						</div>
					</div>
				</div>
				<div class="offer-price-details">
					<div class="offer-price">
						<em><?php echo JText::_("LNG_PRICE")?></em>
						<span class="old-price"><?php echo $offer->currency_symbol?> <?php echo $params->get('price') ?> <?php echo $offer->price_type == 1?"p.p.":""?></span>
					</div>
					<div class="offer-price">
						<em><?php echo JText::_("LNG_ONLY_NOW")?></em>
						<span><?php echo $offer->currency_symbol?> <?php echo $offer->starting_price?> <?php echo $offer->price_type == 1?"p.p.":""?></span>
					</div>
					<div class="offer-price">
						<em class="discount"><?php echo JText::_("LNG_DISCOUNT")?></em>
						<span><?php echo round(($params->get('price') - $offer->starting_price) * 100/$params->get('price'),0) ?> %</span>
					</div>
					<div class="clear"></div>
				</div>
				

			</div>	
			
			<div class="offer-details span7">
				
				<div class="offer-description">
					
					<?php 	
						$offerDescription = $params->get('offerDescription');
						if(isset($offerDescription) && strlen($offerDescription)>0){
							echo $offerDescription;
						}else{
							echo ($offer->offer_short_description); 
						} 
					?>
					<?php //echo JHotelUtil::truncate($offer->hotel_description,300) ?>	
					
				</div>
				<div class="offer-content">
					
					<?php
						$offerContent = $params->get('offerContent');
						if(isset($offerContent) && strlen($offerContent)>0){
							echo $offerContent;
						}else{
							echo $offer->offer_content;
						}
					?>
				</div> 
				<br/>
				<a href="<?php echo JHotelUtil::getHotelLink($offer).'?resetSearch=1&minNights='.$offer->offer_min_nights?>"><?php echo JText::_("LNG_MORE_INFORMATIONS_AND_RESERVE")?></a>
			</div>
		</div>
	</div>
</div>


