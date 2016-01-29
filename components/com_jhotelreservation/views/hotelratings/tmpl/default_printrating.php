<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$ratingAnswers= $this->ratingAnswers;
function getSelectedRatingScale($ratingAnswers,$questionId){
	foreach($ratingAnswers as $ratingAnswer){
		if ($ratingAnswer->review_question_id==$questionId){
			return $ratingAnswer->rating_scale_id;
		} 
	}
}
?>
  <form action="<?php echo JRoute::_('index.php') ?>" method="post" name="hotelRatingForm" id="hotelRatingForm">
  	<input type="hidden" value="<?php echo $this->reservationDetails->confirmation_id?>" name="confirmation_id">
	<div class="hotel_reservation">
		<div class="review_info">
	
			<br/>
			<b><?php echo JText::_('LNG_CLIENT_NAME',true); ?></b> <?php echo $this->reservationDetails->bookingPerson?>	
			<br/>
			<b><?php echo JText::_('LNG_HOTEL',true); ?></b> <?php echo stripslashes($this->reservationDetails->hotelDetails) ?>	
			<br/>
			<b><?php echo JText::_('LNG_RESERVATION_PERIOD',true); ?> </b><?php echo JHotelUtil::getDateGeneralFormat($this->reservationDetails->arrivalDate).' '.JText::_('LNG_TO',true).' '.  $this->reservationDetails->returnDate?>	
			<br/>
		</div>
		<table class="reviewtable">
				<tr>
					<th style="background-color:#FFFFFF">&nbsp;</th>
				<?php
				foreach( $this->reviewRatingScales as $reviewRatingScale ){
				?>
					
						<th style="background-color:#FFFFFF">
							<?php echo $reviewRatingScale->rating_scale_desc?>
						</th>
					
				<?php
				}
				?>
				</tr>
				<tr>
					<td></td>
					<?php
					foreach( $this->reviewRatingScales as $reviewRatingScale ){
						?>
							<td>
								<?php echo $reviewRatingScale->weight?>
							</td>
						<?php
						}
						?>
				</tr>
								
				<?php 
				$i = 0;
				foreach( $this->reviewQuestions as $reviewQuestion ){
					$i++;
					$selectedRatingScale = getSelectedRatingScale($ratingAnswers,$reviewQuestion->review_question_id);
				?>
					<tr class="row<?php echo $i%2?>">	
						<td class="reviewQuestion">
							<?php echo $reviewQuestion->review_question_desc?><span class="mand">*</span>
						</td>
						
							<?php	
							foreach( $this->reviewRatingScales as $reviewRatingScale ){
							?>
							<td>
								<input type="radio" disabled name="<?php	echo "question_".$reviewQuestion->review_question_id?>" value="<?php	echo $reviewRatingScale->rating_scale_id?>"
								<?php if ($selectedRatingScale==$reviewRatingScale->rating_scale_id) echo "checked";?>
								> 
							</td>
							<?php } ?>
					</tr>
					<?php
					}
					?>
				</table>	
				<table>
					<tr>	
						<td class="reviewQuestion" width="1%">
							<?php echo JText::_('LNG_RESERVATION_PERIOD',true); ?>
							<?php echo JText::_('LNG_REVIEW_DESCRIPTION',true); ?>
						</td>
						<td class="reviewQuestion" colspan="5">
							<input type="text" disabled name="review_short_description" size="72" onFocus="this.value='';" value="<?php echo $this->customerReview->review_short_description?>"> 
						</td>
					</tr>
					<tr>	
						<td class="reviewQuestion">
							<?php echo JText::_('LNG_REVIEW_REMARKS',true); ?>
						</td>
						<td class="reviewQuestion" colspan="5">
							<textarea rows="10"  disabled cols="60" name="review_remarks"><?php echo $this->customerReview->review_remarks?></textarea>
						</td>
					</tr>
					<tr>	
						<td class="reviewQuestion">
							<?php echo JText::_('LNG_REVIEW_DATE_OF_ARRIVAL',true); ?>
						</td>
						<td class="reviewQuestion" colspan="5">
							<b><?php echo $this->reservationDetails->arrivalDate?></b>
							<input type="hidden" value="<?php echo $this->reservationDetails->arrivalDate?>" name="arrival_date">
						</td>
					</tr>
					<tr>	
						<td class="reviewQuestion">
							<?php echo JText::_('LNG_REVIEW_DATE_OF_RETURN',true); ?>
						</td>
						<td class="reviewQuestion" colspan="5">
							<b><?php echo $this->reservationDetails->returnDate?></b>
							<input type="hidden" value="<?php echo $this->reservationDetails->returnDate?>" name="return_date">
						</td>
					</tr>	
					<tr>	
						<td class="reviewQuestion">
							<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION',true); ?>
						</td>
						<td class="reviewQuestion" colspan="5">
							<select name="party_composition" disabled>
								<option value="">-- Select choice --</option>
								<option value="With Partner" <?php if($this->customerReview->party_composition == 'With Partner') echo "selected";?> ><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_PARTNER',true); ?></option>
								<option value="Friends" <?php if($this->customerReview->party_composition == 'Friends') echo "selected";?>><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FRIENDS',true); ?></option>
								<option value="Family with young children" <?php if($this->customerReview->party_composition == 'Family with young children') echo "selected";?>><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FAMILY_YOUNG_CHILDREN',true); ?></option>
								<option value="Family with older children" <?php if($this->customerReview->party_composition == 'Family with older children') echo "selected";?>><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FAMILY_OLDER_CHILDREN',true); ?></option>
								<option value="Business trip" <?php if($this->customerReview->party_composition == 'Business trip') echo "selected";?>><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_BUSINESS_TRIP',true); ?></option>
								<option value="Group trip" <?php if($this->customerReview->party_composition == 'Group trip') echo "selected";?>><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_GROUP_TRIP',true); ?></option>
								<option value="Travel alone" <?php if($this->customerReview->party_composition == 'Travel alone') echo "selected";?> default><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_TRAVEL_ALONE',true); ?></option>
							</select>
						</td>
					</tr>
			</table>		
	</div> 

<?php exit;?>
