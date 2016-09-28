/**
* @version 1.0.6.2
* @package PWebFBLikeBox
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/
window.addEvent("domready", function(){

	// validate url & FB Page
	document.id('jform_params_href').addEvent("change", function() {
		var regex = /^((http|https):){0,1}\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/i,
			url = decodeURI(this.value),
			controlGroup = this.getParent().getParent();
		if (!controlGroup.hasClass('control-group')) {
			controlGroup = false;
		}
		this.value = encodeURI(url);
		if (!this.value || regex.test(this.value)) {
			this.removeClass('invalid');
			if (controlGroup) controlGroup.removeClass('error');
			if (FB && this.value) {
				var segments = url.split('/');
				var page = segments[segments.length-1];
				
				FB.api(page, {fields: 'category'}, function(response) {
					if (!response || response.category) {
						document.id('jform_params_href').removeClass('invalid');
						if (controlGroup) controlGroup.removeClass('error');
					}
					else {
						document.id('jform_params_href').addClass('invalid');
						if (controlGroup) controlGroup.addClass('error');
						if (response.error && response.error.code == 100) 
							alert('Given URL is not a Facebook Page');
					} 
				});
			}
		} else {
			this.addClass('invalid');
			if (controlGroup) controlGroup.addClass('error');
		}
	});
	
	document.formvalidator.setHandler("int", function(value) {
		var regex = /^\d+$/i;
		return regex.test(value);
	});
	document.formvalidator.setHandler("unit", function(value) {
		var regex=/^\d+(px|em|ex|cm|mm|in|pt|pc|%){1}$/i;
		return regex.test(value);
	});
	document.formvalidator.setHandler("color", function(value) {
		var regex = /^(\w|#[0-9a-f]{3}|#[0-9a-f]{6}|rgb\(\d{1,3},[ ]?\d{1,3},[ ]?\d{1,3}\)|rgba\(\d{1,3},[ ]?\d{1,3},[ ]?\d{1,3},[ ]?[0]?\.\d{1}\))$/i;
		return regex.test(value);
	});
	
	document.id("jform_params_href").addEvent("change", function() {
		this.value = encodeURI(decodeURI(this.value));
	});
	
	document.id("jform_params_width").addEvent("change", function() {
		if (parseInt(this.value) < 292) alert(Joomla.JText._('MOD_PWEBFBLIKEBOX_WIDTH_MESSAGE'));
	});
});

// load FB SDK 
(function(d,s,id){
var js,fjs=d.getElementsByTagName(s)[0];
if(d.getElementById(id))return;
js=d.createElement(s);js.id=id;
js.src="//connect.facebook.net/en_US/all.js";
fjs.parentNode.insertBefore(js,fjs);
}(document,"script","facebook-jssdk"));