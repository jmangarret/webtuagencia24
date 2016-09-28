<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(isset($this->customerReview->review_date)){
	if(!JRequest::getVar('submitreviewform'))
		echo JText::_('LNG_REVIEW_ALREADY_SUBMITTED',true);	
}
else 
{
?>
 <div class="review">
	<?php echo JText::_('LNG_REVIEW_INVITE',true); ?>
 	<br>
 
 </div>
 	<span class="mand">*</span> - <?php echo JText::_("LNG_MANDATORY_FIELDS")?>.
  <form action="<?php echo JRoute::_('index.php') ?>" method="post" name="hotelRatingForm" id="hotelRatingForm">
  	<input type="hidden" value="<?php echo $this->reservationDetails->confirmation_id?>" name="confirmation_id">
	<div class="hotel_reservation">
		<div class="review_info">
	
			<br/>
			<b><?php echo JText::_('LNG_CLIENT_NAME',true); ?></b> <?php echo $this->reservationDetails->bookingPerson?>	
			<br/>
			<b><?php echo JText::_('LNG_HOTEL',true); ?></b> <?php echo $this->reservationDetails->hotelDetails?>	
			<br/>
			<b><?php echo JText::_('LNG_RESERVATION_PERIOD',true); ?> </b> <?php echo $this->reservationDetails->arrivalDate?> <?php echo JText::_("LNG_TO")?> <?php echo $this->reservationDetails->returnDate?>	
			<br/>
		</div>
		<table class="reviewtable">
				<tr>
					<th></th>
				<?php
				foreach( $this->reviewRatingScales as $reviewRatingScale ){
				?>
					
						<th>
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
				?>
					<tr class="row<?php echo $i%2?>">	
						<td class="reviewQuestion">
							<?php echo $reviewQuestion->review_question_desc?><span class="mand">*</span>
						</td>
						
							<?php	
							foreach( $this->reviewRatingScales as $reviewRatingScale ){
							?>
							<td>
								<input type="radio" name="<?php	echo "question_".$reviewQuestion->review_question_id?>" value="<?php	echo $reviewRatingScale->rating_scale_id?>"> 
							</td>
							<?php } ?>
					</tr>
					<?php
					}
					?>
				</table>	
				<table class="reviewtable" >
					<tr>	
						<td class="reviewQuestion" width="1%">
							<?php echo JText::_('LNG_REVIEW_DESCRIPTION',true); ?><span class="mand">*</span>
						</td>
						<td class="reviewQuestion" colspan="5">
							<input type="text" id="review_short_description" name="review_short_description" value="" size="72" onFocus="this.value=''"> 
						</td>
					</tr>
					<tr>	
						<td class="reviewQuestion">
							<?php echo JText::_('LNG_REVIEW_REMARKS',true); ?>
						</td>
						<td class="reviewQuestion" colspan="5">
							<textarea rows="10" cols="60" name="review_remarks"></textarea>
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
							<select name="party_composition">
								<option value=""><?php echo JText::_('LNG_REVIEW_SELECT_CHOICE',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_PARTNER',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_PARTNER',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FRIENDS',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FRIENDS',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FAMILY_YOUNG_CHILDREN',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FAMILY_YOUNG_CHILDREN',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FAMILY_OLDER_CHILDREN',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_FAMILY_OLDER_CHILDREN',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_BUSINESS_TRIP',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_BUSINESS_TRIP',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_GROUP_TRIP',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_GROUP_TRIP',true); ?></option>
								<option value="<?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_TRAVEL_ALONE',true); ?>"><?php echo JText::_('LNG_REVIEW_PARTY_COMPOSITION_TRAVEL_ALONE',true); ?></option>
							</select>
						</td>
					</tr>
					<tr>	
						<td align="right" colspan=10>
							<input type="button" value="<?php echo JText::_('LNG_REVIEW_SUBMIT',true); ?>" class="btn_general" onClick="submitForm();">
						</td>
					</tr>	
			</table>		
	</div> 


	<input type="hidden" name="option" 				id="option" 				value="<?php echo getBookingExtName()?>" />
	<input type="hidden" name="task" 				id="task" 					value="hotelratings.submitReview" />
	<input type="hidden" name="controller" 			id="controller" 			value="hotelratings" />
	<input type="hidden" name="view" 				id="view" 					value="hotelratings" /> 
	<input type="hidden" name="hotel_id" 			id="hotel_id" 				value="<?php echo $this->reservationDetails->hotelId?>" /> 
	<input type="hidden" name="_lang" id="_lang" 	value="<?php echo JRequest::getVar('_lang') ?>" />
</form>

<script type="text/javascript">

	function isRadioChecked(radioObj) {
		if(!radioObj)
			return false;
		var radioLength = radioObj.length;
		if(radioLength == undefined) {
			return false;
		}
		for(var i = 0; i < radioLength; i++) {
			if (radioObj[i].checked == true){
				return true;
			}
		}
		return false
	}
	
	function submitForm(){
		
		var nrQuestions = <?php echo count($this->reviewQuestions)?>;
		 <?php
		 	foreach( $this->reviewQuestions as $reviewQuestion )
		 	{
		 ?>
				var ratingQuestion = document.getElementsByName("question_<?php echo $reviewQuestion->review_question_id;?>");
				if(!isRadioChecked(ratingQuestion)){
					alert('<?php echo JText::_('LNG_PLEASE_RATE_QUESTION',true)." ".$reviewQuestion->review_question_desc;?>');
					return false;
				}
		<?php
			}
		?>
		var reviewDesc = document.getElementById("review_short_description")
		if(reviewDesc.value==''){
			alert('<?php echo JText::_('LNG_PLEASE_ENTER_REVIEW_DESCRIPTION',true);?>');
			reviewDesc.focus();
			return false;	
		}
		document.getElementById("hotelRatingForm").submit();
	}


</script>
<?php
	}
?>