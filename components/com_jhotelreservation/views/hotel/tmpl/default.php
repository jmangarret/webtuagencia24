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
$hotel = $this->hotel;
$hotelUrl = JURI::current(); 
//set metainfo

$config =JFactory::getConfig();
$document = JFactory::getDocument();

if( $this->state->get("hotel.tabId") != 4){
	$title = JText::_('LNG_METAINFO_HOTEL_TITLE');
	$description = JText::_('LNG_METAINFO_HOTEL_DESCRIPTION');
	$keywords = JText::_('LNG_METAINFO_HOTEL_KEYWORDS');
	
	$title =  str_replace("<<hotel>>", $hotel->hotel_name, $title);
	$title =  str_replace("<<city>>", $hotel->hotel_city, $title);
	$title =  str_replace("<<province>>", $hotel->hotel_county, $title);
	
	$description =  str_replace("<<hotel>>", $hotel->hotel_name, $description);
	$description =  str_replace("<<city>>", $hotel->hotel_city, $description);
	$description =  str_replace("<<province>>", $hotel->hotel_county, $description);
	$description =  str_replace("<<hotel-stars>>", $hotel->hotel_stars, $description);
	
	$keywords =  str_replace("<<hotel>>", $hotel->hotel_name, $keywords);
	$keywords =  str_replace("<<city>>", $hotel->hotel_city, $keywords);
	$keywords =  str_replace("<<province>>", $hotel->hotel_county, $keywords);
	
	
	$document->setTitle($title);
	$document->setDescription($description);
	$document->setMetaData('keywords', $keywords);
	$document->addCustomTag('<meta property="og:title" content="'.$title.'"/>');
	$document->addCustomTag('<meta property="og:description" content="'.$description.'"/>');
	$document->addCustomTag('<meta property="og:image" content="'.(isset($hotel->pictures[0])?JURI::root().PATH_PICTURES.$hotel->pictures[0]->hotel_picture_path:'').'"/>');
	$document->addCustomTag('<meta property="og:type" content="website"/>');
	$document->addCustomTag('<meta property="og:url" content="'.$hotelUrl.'"/>');
	$document->addCustomTag('<meta property="og:site_name" content="'.$hotelUrl.'"/>');
}
$need_all_fields = true;

?>


	<div class="hotel_reservation" id="hotel_reservation">
		<div>
			<div class="hotel-content">
				<?php if(count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS & $this->appSettings->enable_hotel_rating==1){ ?>
					<div class="hotel-rating">
						<div class="info">
							<strong><?php  echo JText::_('LNG_CUSTOMER_REVIEW');?></strong><br/>
							<a href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_REVIEWS")) ?>" ><?php echo count($hotel->reviews)?> <?php echo JText::_('LNG_REVIEWS')?></a>
						</div>
						<div class="rating">
							<?php echo JHotelUtil::fmt($hotel->hotel_rating_score,1)?>
						</div>
						<div class="clear"></div>					
					</div>
				<?php } ?>
			
			<div class="hotel-details">
				<?php if($hotel->recommended==1){?>
				<div class="hotel-recommanded">
					<span><?php  echo JText::_('LNG_RECOMMENDED');?></span>
				</div>
				<?php } ?>
			</div>	
			
				<div class="hotel-title">
					<h1>
						<?php echo stripslashes($this->hotel->hotel_name) ?> 
					</h1>
					<span class="hotel-stars">
						<?php
						for ($i=1;$i<= $this->hotel->hotel_stars;$i++){ ?>
							<img  src='<?php echo JURI::base() ."administrator/components/".getBookingExtName()."/assets/img/star.png" ?>' />
						<?php } ?>
					</span>
				</div>
				
				<div class="hotel-address">
					<?php echo $this->hotel->hotel_address?>, <?php echo $this->hotel->hotel_zipcode?$this->hotel->hotel_zipcode.", ":""?> <?php echo $this->hotel->hotel_city?>,
					 <?php echo $this->hotel->hotel_county?$this->hotel->hotel_county.", ":""?><?php echo $this->hotel->country_name?>
				</div>
				<div class="clear"></div>
				
				<!-- <div class="styled">
				
					<select name="user_currency" id="user_currency" onChange="checkRoomRates('searchForm')">
						<?php foreach ($this->currencies as $currency) {?>
							<option value="<?php echo $currency->currency_id ?>" <?php if($this->userData->user_currency==$currency->description) echo "selected"?>><?php echo $currency->description; if( $this->hotel->hotel_currency==$currency->description) echo " *" ?></option>
						<?php }?>	
					</select>
				</div> -->
				
				
				<div class="hotel-actions right">
					<?php require "social_share.php" ?>
				</div>
			
				<div class="clear"></div>
			</div>
			<?php 
			
			$map = JRequest::getVar(strtolower(JText::_("LNG_MAP")));
			$fotoGallery = JRequest::getVar(strtolower(JText::_("LNG_PHOTO")));
			$reviews = JRequest::getVar( strtolower(JText::_("LNG_REVIEWS")));
			$facilities = JRequest::getVar(strtolower(JText::_("LNG_FACILITIES")));
			
			$overview = !(isset($map)|| isset($fotoGallery) || isset($reviews) || isset($facilities));
			
			?>
			<?php if ($this->appSettings->enable_hotel_tabs==1) {?>	
			<div class="rel">
				<div class="tabs">
					<ul>
						<li class="<?php echo $overview?'selected':''?> ">
							<a  href="<?php echo JHotelUtil::getHotelLink($this->hotel) ?>"><span><?php echo isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ?JText::_('LNG_PARK_OVERVIEW'): JText::_("LNG_HOTEL_OVERVIEW")?></span></a>
						</li>
						<li class="<?php echo isset($map)?'selected':''?> ">
							<a  href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_MAP")) ?>"><span><?php echo JText::_('LNG_MAP')?></span></a>
						</li>
						<li class="<?php echo isset($fotoGallery)?'selected':''?>">
							<a  href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_PHOTO")) ?>"><span><?php echo JText::_('LNG_PHOTO_GALLERY')?></span></a>
						</li>
						
						<?php if(count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS && $this->appSettings->enable_hotel_rating==1){ ?>
							<li class="<?php echo isset($reviews)?'selected':''?>">
								<a  href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_REVIEWS")) ?>"><span><?php echo JText::_('LNG_REVIEWS')?></span></a>
							</li>
						<?php }?>
						<?php if($this->appSettings->enable_hotel_facilities==1){?>
							<li class="<?php echo isset($facilities)?'selected':''?>">
								<a  href="<?php echo JHotelUtil::getHotelLink($this->hotel).'?'.strtolower(JText::_("LNG_FACILITIES")) ?>"><span><?php echo  isset($this->hotel->types) & $this->hotel->types[0]->id == PARK_TYPE_ID ? JText::_('LNG_PARK_FACILITIES'):JText::_('LNG_HOTEL_FACILITIES')?></span></a>
							</li>
						<?php }?>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<?php }?>
			<div class="hotel_details_container">
				<?php 
					
					if(isset($map)){
						require_once 'hotelmap.php';
					} else if(isset($fotoGallery)){
						require_once 'hotelgallery.php';
					} else if(isset($reviews)){
						require_once 'hotelreviews.php';
					}else if(isset($facilities)){
						require_once 'hotelfacilities.php';
					}else{
						require_once 'hoteloverview.php';
					}
				?>
			</div>
		</div> 
	</div>
	
	<script>
	<?php if(JRequest::getVar('rm_id',0)>0){?>
		var roomId = "#room_<?php echo JRequest::getVar('rm_id',0)?> div";
		jQuery(document).ready(function(){
				setTimeout(openSelectedRoom, 500);
			});
	<?php }?>	

		jQuery(document).ready(function(){
			jQuery('body').removeClass("homepage");
			jQuery('body').addClass("subpage");
		});
		
		function openSelectedRoom(){
			jQuery(roomId).removeClass('open');
			jQuery(roomId).addClass('close');
			jQuery(roomId).parent().parent('tr').next().children('.td_cnt').children('.cnt').slideDown(100);
			jQuery(roomId).children('.room_expand').addClass('expanded');
			jQuery(roomId).children('.link_more').html('&nbsp;<?php echo JText::_('LNG_LESS',true)?> »');
			jQuery(roomId).focus();
			jQuery('html, body').animate({ scrollTop: jQuery(roomId).offset().top-40 }, 'slow');
			
			return false;
			}	
	
		function showEmailDialog(){
			jQuery.blockUI({ message: jQuery('#share-hotel-email'), css: {width: '600px'} }); 
			var form = document.emailForm;
			form.elements["email_to_address"].value='';
			form.elements["email_from_name"].value='';
			form.elements["email_from_address"].value='';
			form.elements["email_note"].value='';
			form.elements["copy_yourself"][1].checked=false;
			
		}

		function goBack(){
			var form 	= document.forms['userForm'];
			form.task.value	="hotels.searchHotels";
			form.submit();
		}
	
		function showTab(tabId){
			location = "<?php echo $hotelUrl ?>"+"?tabId="+tabId;
		}

		function sendMail(){
			
			jQuery("#emailError").hide();
			var form = document.emailForm;
			var postParameters='';
			postParameters +="&email_to_address=" + form.elements["email_to_address"].value;
			postParameters +="&email_from_name=" + form.elements["email_from_name"].value;
			postParameters +="&email_from_address=" + form.elements["email_from_address"].value;
			postParameters +="&email_note=" + form.elements["email_note"].value;
			postParameters +="&copy_yourself=" + form.elements["copy_yourself"][1].checked;
			var postData='&controller=email&task=sendEmail'+postParameters;

			jQuery.post(baseUrl, postData, sendMailResult);
		}


		function sendMailResult(responce){
			var xml = responce;
			alert(xml);
			//jQuery('#frmFacilitiesFormSubmitWait').hide();
			jQuery(xml).find('answer').each(function()
			{
				if(jQuery(this).attr('result')==true){
					jQuery("#email-message").html("<p><?php echo JText::_('LNG_EMAIL_SUCCESSFULLY_SENT') ?></p>");
					jQuery.unblockUI();
					jQuery.blockUI({ message: jQuery('#share-hotel-email-message'), css: {width: '600px'} }); 
					setTimeout(jQuery.unblockUI, 2500);
				}else{
					jQuery("#emailError").html(jQuery(this).attr('result'));
					jQuery("#emailError").show();
				}
			});
		}


		</script> 
	
	

