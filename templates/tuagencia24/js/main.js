jQuery(window).load(function()
{
	var extra = [];

	if (typeof _data != 'undefined') {
		extra = _data.wsform.extra;

		jQuery('[name="wsform[B_LOCATION_1]"]').val(_data.wsform.B_LOCATION_1);

		if (typeof extra != 'undefined') {
			extra = extra.split(';');

			if (typeof extra[0] != '') {
				setWord(extra[0],'#title-header-aws #from');
				jQuery('#title-header-aws').show();
			}

			if (typeof extra[1] != '') {
				setWord(extra[1],'#title-header-aws #to');
			}
		}
	}

});

jQuery(document).ready(function()
{	
	/*jQuery('#availability_main').on('click','.als-next',function()
	{
		items = jQuery('ul.als-wrapper li.als-item').length;
		target = jQuery('ul.als-wrapper');
		step = 134;
		limit = step * (items - 1);

		if (items > 5) {
			controlNext(target,step,limit);
		}
	});

	jQuery('#availability_main').on('click','.als-prev',function()
	{
		target = jQuery('ul.als-wrapper');
		step = 134;

		controlPrev(target,step);
	});*/

	jQuery('#passenger_main').on('click','#ForAnyPassager',function()
	{
		if (jQuery(this).attr('checked') != undefined) {
			jQuery('#filedsFactura').hide();
		}
		else {
			jQuery('#filedsFactura').show();
		}
	});

	jQuery('#passenger_main').on('click','#continue-button',function()
	{
		if (jQuery(this).attr('checked') != undefined) {
			jQuery('.email.confirm').show();
		}
	});

	jQuery('#passenger_main').on('blur','#contactemail',function()
	{		
		if (jQuery(this).val() != '') {
			jQuery('#contactemailconfirm').val(jQuery(this).val());
		}
	});

	if (jQuery('#resume-order').length > 0) {
		jQuery('#resume-order').height(jQuery('.resume-flight').innerHeight());
	}

});

function setWord(s,t)
{
	if (s) {
		var from = s;
		from = from.split(',');
		from = from[0].split('|');
		from = from[1];
		jQuery(t).html(from);
	}

}

function controlPrev(target,step) {
	var ml = target.css('marginLeft');
	var ml = ml.split('px');
	var ml = ml[0];

	if (target.is(":animated")) {
		return;
	}	

	if(ml < 0) {
		target.animate({
			marginLeft: '+=' + step + 'px'
		}, 400);
	}

}

function controlNext(target,step,limit) {
	var ml = target.css('marginLeft');
	var ml = ml.split('px');
	var ml = ml[0];

	if (target.is(":animated")) {
		return;
	}

	if(Math.abs(ml) < limit) {
		target.animate({
			marginLeft: '-=' + step + 'px'
		}, 400);
	}

}