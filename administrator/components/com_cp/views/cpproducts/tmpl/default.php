<?php
defined('_JEXEC') or die('Restricted access');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
jimport('joomla.html.html');
jimport('joomla.html.pane');
JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

$data = $this->item;
$myTabs = JPane::getInstance ('tabs');
$document = JFactory::getDocument();
$document->addScript('https://maps.google.com/maps/api/js?sensor=false');
$latitude = ($data->latitude) ? $data->latitude: '3.598219';
$longitude = ($data->longitude) ? $data->longitude: '-73.087749';
$document->addScriptDeclaration('
	var map;
	var marker;
	var geocoder;
	var infowindow;
	var countryName;
	function initialize() {
	    var latlng = new google.maps.LatLng(' . $latitude . ', ' . $longitude . ');
	    infowindow = new google.maps.InfoWindow();
	    geocoder = new google.maps.Geocoder();
	    var myOptions = {
			zoom: 4,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
	    };

	    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		marker = new google.maps.Marker({
			map: map
		});
		' . (($data->product_id)? 'placeMarker(latlng, false);': '') . '

		google.maps.event.addListener(map, "click", function(event) {
		    placeMarker(event.latLng, true);
		});
	}
	  
	function placeMarker(location, save) {
		infowindow.setContent();
		countryName = "";
	    geocoder.geocode({"latLng": location}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					if (save) {
						// Put the coordinates in the forms fields
						jQuery("#jform_latitude").val(location.lat());
						jQuery("#jform_longitude").val(location.lng());
					}
					marker.setPosition(location);

					// Show the location.
					countryName = "";
					for (var i = 0; i < results[0].address_components.length; i++) {
						if (jQuery.inArray("country", results[0].address_components[i].types[0])) {
							countryName = "<b>C&oacute;digo de Pa&iacute;s:   </b> " + results[0].address_components[i].short_name;
						}
					}
					infowindow.setContent(
			        "<b>Latitud:   </b>" + location.lat() + "<br />" + 
			        "<b>Longitud:   </b>" + location.lng() + "<br />" +
			        "<b>Direcci&oacute;n:   </b>" + results[0].formatted_address + "<br />" + countryName);
					infowindow.open(map, marker);
		        }
			} else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
				alert("Ha seleccionado un punto fuera de territorio, por favor seleccione un nuevo punto.");
			} else {
				alert("Se ha presentado un error, por favor intente de nuevo.");
			}
		});
	}

	function getVarsUrl(){
	    var url= location.search.replace("?", "");
	    var arrUrl = url.split("&");
	    var urlObj={};   
	    for(var i=0; i<arrUrl.length; i++){
	        var x= arrUrl[i].split("=");
	        urlObj[x[0]]=x[1]
	    }
	    return urlObj;
	}

	jQuery(document).ready(function() {
		initialize();

		var vGet = getVarsUrl();
		if (vGet.product_id == undefined) {
			jQuery("#toolbar-apply").remove();
		}
	});
');

$db = JFactory::getDbo();
?>
<script type="text/javascript">
	control_save = false;
	control_save2new = false;
	Joomla.submitbutton = function(task) {
		var form = document.adminForm;

		if (task == 'cpproducts.cancel') {
			Joomla.submitform(task, document.getElementById('adminForm'));
			return;
		}

		if (task == 'cpproducts.save') {
			if (control_save) {
				window.location = 'index.php?option=com_cp&view=cpproductslist';
				return false;
			}
			control_save = true;
		}

		if (task == 'cpproducts.save2new') {
			if (control_save2new) {
				window.location = 'index.php?option=com_cp&view=cpproducts&layout=edit';
				return false;
			}
			control_save2new = true;
		}

		document.formvalidator.isValid(document.id('adminForm'));

		// do field validation
		if (form.getElementById('jform_product_name').value == "") {
			alert("<?php echo JText::_('COM_CP_PRODUCT_MUST_HAVE_A_NAME', true); ?>");
			form.getElementById('jform_product_name').focus();
		} else if (form.getElementById('jform_country_code').selectedIndex == 0) {
			alert("<?php echo JText::_('COM_CP_YOU_MUST_SELECT_A_COUNTRY', true); ?>");
			form.getElementById('jform_country_code').focus();
		} else if (form.getElementById('jform_city').selectedIndex == 0) {
			alert("<?php echo JText::_('COM_CP_YOU_MUST_SELECT_A_CITY', true); ?>");
			form.getElementById('jform_city').focus();
		} else if (form.getElementById('jform_price').value == "") {
			alert("<?php echo JText::_('COM_CP_PRODUCT_MUST_HAVE_A_PRICE', true); ?>");
			form.getElementById('jform_price').focus();
		} else if (form.getElementById('jform_product_desc').value == "") {
			alert("<?php echo JText::_('COM_CP_PRODUCT_MUST_HAVE_A_SHORT_DESCRIPTION', true); ?>");
			form.getElementById('jform_product_desc').focus();
		} else if (parseInt(form.getElementById('jform_quota').value, 10) < 1) {
			alert("<?php echo JText::_('COM_CP_PRODUCT_MUST_HAVE_A_QUOTA', true); ?>");
			form.getElementById('jform_quota').focus();
		} else if (form.getElementById('jform_category_id').options[form.getElementById('jform_category_id').selectedIndex].value == "0") {
			alert("<?php echo JText::_('COM_CP_YOU_MUST_SELECT_A_CATEGORY', true); ?>");
			form.getElementById('jform_category_id').focus();
		} else {
			var endDateField = document.getElementById('jform_publish_down');
	        if (endDateField.value != '0000-00-00') {
				var oneDay = 24*60*60*1000;
				var startDateField = document.getElementById('jform_publish_up');
				var publish_date = startDateField.value.split('-');
			    var starDate = new Date(publish_date[0], (parseInt(publish_date[1]) - 1), (parseInt(publish_date[2]) + 1));
	            var endDate = new Date(endDateField.value);
	            if (starDate >= endDate) {
	            	alert("<?php echo JText::_('COM_CP_PUBLISHING_RANGE_NOT_VALID', true); ?>");
	            	endDateField.focus();
	            	return;
	            }
	        }
	        Joomla.submitform(task);
		}
	}

</script>

<form action="<?php echo JRoute::_('index.php?option=com_cp&view=cpproducts&product_id=' . (int) $this->item->product_id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
<?php
echo $myTabs->startPane('productInfo');
echo $myTabs->startPanel(JText::_('COM_CP_BASIC_PARAMETERS'), 'productDetails');
?>
	<table style="border: none;">
		<tr>
			<td>
				<table style="border: none;">
					<tr>
						<td align="left" class="key2"><?php echo $this->form->getLabel('product_name'); ?></td>
						<td><?php echo $this->form->getInput('product_name'); ?></td>
                        <td align="right" class="key2"><?php echo $this->form->getLabel('featured'); ?></td>
                        <td><?php echo $this->form->getInput('featured'); ?></td>
					</tr>
					<tr>
                        <td align="left" class="key2"><?php echo $this->form->getLabel('country_code'); ?></td>
                        <td><?php echo $this->form->getInput('country_code'); ?></td>
						<td align="right" class="key2"><?php echo $this->form->getLabel('city'); ?></td>
						<td id="citylist">
							<?php echo $data->cities; ?>
						</td>
					</tr>
                    <tr>
                        <td class="key2"><?php echo $this->form->getLabel('publish_up'); ?></td>
                        <td style="width: 100px;"><?php echo $this->form->getInput('publish_up'); ?></td>
                        <td align="right" class="key2"><?php echo $this->form->getLabel('publish_down'); ?></td>
                        <td style="width: 100px;"><?php echo $this->form->getInput('publish_down'); ?></td>
                    </tr>
					<tr>
						<td colspan="8">
							<div id="map_canvas" style="width: 100%; padding: 0px; height: 500px;"></div>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top" class="pane-sliders">
				<div class="panel">
				<h3 id="detail-page" class="title pane-toggler-down"><a href="javascript:void(0);">
				<span><?php echo JText::_('COM_CP_BASIC_PARAMETERS'); ?></span></a></h3>
		<div class="pane-slider content pane-down" style="padding-top: 0px; border-top: medium none; padding-bottom: 0px; border-bottom: medium none; overflow: hidden; height: auto;">           <fieldset class="panelform">
		<table style="width: 100%;" class="paramlist admintable">
			<tbody>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('product_id'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('product_id'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('price'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('price'); ?>&nbsp;<?php echo $this->params->get('cfg_currency'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('product_desc'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('product_desc'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('quota'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('quota'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('product_code'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('product_code'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('tourismtype_id'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('tourismtype_id'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('category_id'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('category_id'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('access'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('access'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('published'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('published'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('latitude'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('latitude'); ?></td>
				</tr>
				<tr>
					<td class="paramlist_key"><?php echo $this->form->getLabel('longitude'); ?></td>
					<td class="paramlist_value"><?php echo $this->form->getInput('longitude'); ?></td>
				</tr>
			</tbody>
		</table>
		</fieldset>
		</div>
		</div>
			</td>
		</tr>

		</table>
<?php
echo $myTabs->endPanel();
echo $myTabs->startPanel(JText::_('COM_CP_DETAILSPANEL'), 'productMedia');
?>
		<div align="left">
		<table style="margin: 0 auto;">
			<tr>
				<td align="left" style="width: 5%;"><?php echo $this->form->getLabel('tag_name1'); ?></td>
				<td align="left"><?php echo $this->form->getInput('tag_name1'); ?></td>
				<td rowspan="6" width="50%" valign="top">
					<div id="menu-pane" class="pane-sliders" style="padding: 10px;">
					<div class="panel">
						<h3 class="title"><?php echo JText::_('COM_CP_Images'); ?></h3>
						<div>
							<a rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="<?php echo JURI::base(true); ?>/index.php?option=com_media&view=images&tmpl=component&fieldid=tempimage" class="modal-button"><?php echo JHtml::_('image', 'media/folderup_32.png', '..', array('width' => 32, 'height' => 32), true); ?><br /><?php echo JText::_('COM_CP_IMAGES_DESC'); ?></a>
							<?php echo JText::_('COM_CP_IMAGES_UPLOAD_DESC'); ?>
							<ul id="galleryContainer" class="sortable">
							<?php
								if (is_array($data->media)) {
									$i = 0;
									foreach ($data->media as $image) {
										echo '<li id="imgPos' . $i . '"><img src="' . JURI::root() . $image->file_url . '" border="0" width="180" height="120" /><br /><a class="delImage" href="javascript:deleteImg(' . $i . ')">' . JText::_('COM_CP_Delete') . '</a><input type="hidden" name="mediafiles[]" value="' . $image->file_url . '" /></li>';
										$i++;
									}
								}
							?>
							</ul>
						</div>
					</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->form->getInput('tag_content1'); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="left"><?php echo $this->form->getLabel('tag_name2'); ?></td>
				<td align="left"><?php echo $this->form->getInput('tag_name2'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->form->getInput('tag_content2'); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="left"><?php echo $this->form->getLabel('tag_name3'); ?></td>
				<td align="left"><?php echo $this->form->getInput('tag_name3'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->form->getInput('tag_content3'); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="left"><?php echo $this->form->getLabel('tag_name4'); ?></td>
				<td align="left"><?php echo $this->form->getInput('tag_name4'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->form->getInput('tag_content4'); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="left"><?php echo $this->form->getLabel('tag_name5'); ?></td>
				<td align="left"><?php echo $this->form->getInput('tag_name5'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->form->getInput('tag_content5'); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td align="left"><?php echo $this->form->getLabel('tag_name6'); ?></td>
				<td align="left"><?php echo $this->form->getInput('tag_name6'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->form->getInput('tag_content6'); ?></td>
			</tr>
		</table>
		</div>
<?php
echo $myTabs->endPanel();
echo $myTabs->endPane();
?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="delMedia" value="" />
	<input type="hidden" name="tempimage" id="tempimage" value="" />
	<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
