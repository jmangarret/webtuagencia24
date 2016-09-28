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
JHTML::_('script',  'components/com_jhotelreservation/assets/js/jquery.isotope.min.js');
JHTML::_('script',  'components/com_jhotelreservation/assets/js/isotope.init.js');
?>

<div class="top-offers<?php echo $moduleclass_sfx ?>">
	<p>
		<?php echo $params->get('offersIntro')?>
	</p>
	
	<!-- layout -->
	<div id="layout" class="pagewidth clearfix grid4" >
	
	<div id="grid-content">
		<div id="loops-wrapper" class="loops-wrapper infinite-scrolling AutoWidthElement">
		<?php if(isset($items)){ ?>
			<?php foreach($items as $i=>$item){ ?>
				<article id="post-63" class="post-63 post type-post status-publish format-standard hentry category-food post clearfix ">
					<div class="post-inner">
						<figure class="post-image">
							<a href="<?php echo JHotelUtil::getHotelLink($item) ?>"><img alt="<?php echo $item->offer_picture_info ?>" src="<?php echo JURI::root().PATH_PICTURES.$item->offer_picture_path?>"	title="<?php echo $item->offer_picture_info ?>"></a>
							<div class="info" onclick="document.location.href='<?php echo JHotelUtil::getHotelLink($item) ?>'">
								<h2><a href="<?php echo JHotelUtil::getHotelLink($item) ?>"><?php echo stripslashes($item->offer_name)?></a></h2>
								<div class="hover_info">
									<a href="<?php echo JHotelUtil::getHotelLink($item) ?>" title="<?php echo $item->offer_name ?>" class="btn" rel="nofollow noindex"><?php echo JText::_("LNG_BOOK")?></a>
								</div>
							</div>
						</figure>
						<div class="post-content">
							<p>
								<?php echo strip_tags($item->offer_short_description)?>
							</p>
							
							<?php 
								//var_dump($item);
								//TODO move this in helper
								$price = $item->starting_price;
								if($item->price_type == 0){
									$price = $price / $item->base_adults;
								}
							?>
							<div class="offer-lowest-price right"><span class="currency-sign fucsia"><?php echo $item->currency_symbol ?>&nbsp;</span><span class="fucsia price"> <?php echo number_format($price,2) ?></span></div>
						</div>
						<!-- /.post-content -->
					</div>
				<!-- /.post-inner -->
			</article>
		<?php 
			}
			}
		 ?>	
		 <div class="clear"></div>
		</div>
		</div>
	</div>	

	<div class="view-all-offers">
		<a href="<?php echo JRoute::_('index.php?option=com_jhotelreservation&task=offers.searchOffers'); ?>"><?php echo JText::_("LNG_VIEW_ALL_OFFERS")?></a>
	</div>
</div>