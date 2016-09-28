<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$nameClient = ($user->id)? $user->name . ' ' . $user->getParam('LastName'): '';
$emailClient = ($user->id)? $user->email: '';
$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_cp/assets/js/jquery.min.js');
$document->addScript(JURI::base() . 'components/com_cp/assets/js/jquery-ui.custom.min.js');
$document->addScript(JURI::base() . 'components/com_cp/assets/js/animatedcollapse.js');
$document->addStyleSheet(JURI::base() . 'components/com_cp/assets/css/galleria.classic.css');
$document->addScript(JURI::base() . 'components/com_cp/assets/js/galleria-1.2.7.min.js');
$document->addScript(JURI::base() . 'components/com_cp/assets/js/galleria.classic.min.js');
$document->addScript(JURI::base() . 'components/com_cp/assets/js/scripts.js');
$document->addStyleSheet(JURI::base() . 'components/com_cp/assets/css/tabs_mo.css');
$galleryWidth = $this->params->get('cfg_gallery_width', '100%');
$galleryHeight = $this->params->get('cfg_gallery_height', '300px');

$data = $this->data;
$link = JRoute::_( "index.php?option=com_cp&view=cpproducts&id={$data->product_id}" );
$featuredText = JText::_('COM_CP_FEATURED');
if ($data->featured) {
	$data->product_name .= '&nbsp;<img width="16" height="16" title="' . $featuredText . '" alt="' . $featuredText . '" src="' . JURI::base(true) . '/components/com_cp/assets/images/featured_icon.png" />';
}

// Organizar las pesta�as que describen el producto.
$tags_contents = array();
for ($i = 1; $i < 7; $i++) {
	$tag_name = 'tag_name' . $i;
	$tag_content = 'tag_content' . $i;
	if (strlen($data->$tag_name) && strlen($data->$tag_content) > 20) {
		$tags_contents[] = array($data->$tag_name, $data->$tag_content);
	}
}

// Error messages, map and tabs settings
$document->addScriptDeclaration('
	var errorSaveProduct = "' . JText::_('COM_CP_REQUIRED_FIELDS_ERROR_MESSAGE') . '";	
	var errorConfirmEmailSaveProduct = "' . JText::_('COM_CP_ERROR_CONFIRM_EMAIL') . '";	
	var errorEmailSaveProduct = "' . JText::_('COM_CP_ERROR_EMAIL') . '";	
	var errorPassengersSaveProduct = "' . JText::_('COM_CP_ERROR_ZERO_PASSENGERS') . '";	
	var latlng;
	var mapProduct = "";
	animatedcollapse.addDiv("conte_form_reserva_plan", "fade=0,speed=400,group=pets,persist=1,hide=1");
	animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
		//$: Access to jQuery
		//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
		//state: "block" or "none", depending on state
	}
	animatedcollapse.init();
	jQuery.noConflict()(document).ready(function($) {
		//Default Action
		$(".tab_content").hide(); //Hide all content
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
		//On Click Event
		$("ul.tabs li").click(function() {
			$("ul.tabs li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active content
			if (activeTab == "#tab' . count($tags_contents) . '" && mapProduct) {
				google.maps.event.trigger(mapProduct, "resize");
				mapProduct.setCenter(latlng);
			}
			return false;
		});
	});
');

// Format price
$resultprice = $this->getModel()->formatNumber($data->price, $this->params->get('cfg_currency'));
if (empty($resultprice)) {
	$price = $data->price;
} else {
	$price = $resultprice[0];
}

// Incluir mapa si tiene ambas coordenadas
if ($data->latitude && $data->longitude) {
	$document->addScript('https://maps.google.com/maps/api/js?sensor=false');
	$document->addScriptDeclaration('
			var marker;
			var geocoder;
			var infowindow;
			function initialize() {
			    latlng = new google.maps.LatLng(' . $data->latitude . ', ' . $data->longitude . ');
			    infowindow = new google.maps.InfoWindow();
			    geocoder = new google.maps.Geocoder();
			    var myOptions = {
					zoom: 4,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
			    };

			    mapProduct = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
				marker = new google.maps.Marker({
					map: mapProduct
				});
				placeMarker(latlng);
			}
	  
			function placeMarker(location) {
				infowindow.setContent();
			    geocoder.geocode({"latLng": location}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							marker.setPosition(location);
							infowindow.setContent("<b>Direcci&oacute;n: </b>" + results[0].formatted_address + "<br />");
							infowindow.open(mapProduct, marker);
				        }
					}
				});
			}
			jQuery.noConflict()(document).ready(function($) {initialize()});
	');
	$tags_contents[] = array(JText::_('COM_CP_PRODUCT_MAP'), '<div id="map_canvas" style="width: 100%; height: 396px;"></div>');
}
?>
<div class="conte_plan">
	<div class="top_zona_verde"><?php echo $data->product_name; ?></div>
	<div class="conte_zona_plan">
		<div class="ciudad_pais_precio_plan">
			<div class="ciudad_pais_plan">
				<strong><?php echo JText::_('COM_CP_COUNTRY' ); ?>:</strong>&nbsp;<?php echo $data->country_name; ?>&nbsp;-&nbsp;
				<strong><?php echo JText::_('COM_CP_CITY' ); ?>:</strong>&nbsp;<?php echo $data->city_name; ?>
			</div>
			<div class="precio_plan">
				<strong><?php echo strtolower(JText::_('COM_CP_FROM' )); ?>:</strong>&nbsp;
				<span class="precio_detalle_plan"><span class="precio_tarifas_vuelos" colspan="2">Bs.&nbsp;<?php echo number_format($price, 0, ',', '.'); ?></span></span>
			</div>
			<div class="clear"></div>
		</div>
		<div class="descripcion_corta_plan">
			<?php echo $data->product_desc; ?>
		</div>
		<?php
		$i = 0;
		if (!empty($data->media)) {
			$image_string = '';
			foreach ($data->media as $img) {
				if (file_exists($img->file_url)) {
					$image_string .= '<img src="' . $img->file_url . '" border="0" />';
					$i++;
				}
			}
		}
		if ($i > 0) {
		?>
		<div class="conte_slider_fotos" id="galleriaProduct" style="<?php echo 'height: ' . $galleryHeight . '; width: ' . $galleryWidth . ';'; ?>>"><!-- Open div conte_slider_fotos  -->
			<?php
			echo $image_string;
			$document->addScriptDeclaration('
		    jQuery.noConflict()(document).ready(function($) {
			    // Initialize Galleria
			    Galleria.configure({
			    	debug: false,
			    	imageCrop: "height"
				});
			    Galleria.run("#galleriaProduct");
			});
			');
			?>
		</div>
		<?php
		}
		?>
		<div class="top_reserva_plan"><!-- Open div class top_reserva_plan  -->
			<?php echo JText::_('COM_CP_ASK_FOR_PRODUCT' ); ?> - <a href="javascript:animatedcollapse.show('conte_form_reserva_plan');"><?php echo JText::_('COM_CP_CLICK_HERE' ); ?></a>
		</div>
		<div id="conte_form_reserva_plan" style="display: none;" groupname="pets" speed="400"><!-- Open div id conte_form_reserva_plan  -->
			<h2><?php echo JText::_('COM_CP_BOOKING_TITLE' ); ?></h2>
			<form name="productSaveCatalog" id="productSaveCatalog" class="form-validate" action="<?php echo JRoute::_( 'index.php' ); ?>" method="post">
				<table width="100%" cellspacing="4" cellpadding="4">
				  <tbody><tr>
				    <td colspan="4"><?php echo JText::_('COM_CP_REQUIRED_FIELDS_DESC' ); ?></td>
				    </tr>
				  <tr>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_NAME' ); ?></td>
				    <td valign="top" align="left"><input type="text" id="client_name" class="campo_form_plan_big required" name="client_name" value="<?php echo $nameClient; ?>" /> <span class="red">*</span></td>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_CITY' ); ?></td>
				    <td valign="top" align="left"><input type="text" id="client_city" class="campo_form_plan_big required" name="client_city" /> <span class="red">*</span></td>
				  </tr>
				  <tr>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_PHONE' ); ?></td>
				    <td valign="top" align="left"><input type="text" id="client_phone" class="campo_form_plan_big required" name="client_phone" /> <span class="red">*</span></td>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_DATE' ); ?></td>
				    <td valign="top" align="left"><input type="text" id="booking_date" class="campo_form_plan_fecha required" name="booking_date" readonly /> <span class="red">*</span></td>
				  </tr>
				  <tr>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_EMAIL' ); ?></td>
				    <td valign="top" align="left"><input type="text" id="client_email" class="campo_form_plan_big required" name="client_email" value="<?php echo $emailClient; ?>" /> <span class="red">*</span></td>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_CONFIRM_EMAIL' ); ?></td>
				    <td valign="top" align="left"><input type="text" id="client_confirm_email" class="campo_form_plan_big required" name="client_confirm_email" value="<?php echo $emailClient; ?>" /> <span class="red">*</span></td>
				  </tr>
				  <tr>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_ADULTS' ); ?></td>
				    <td valign="top" align="left"><span style="font-size:11px;">
				      <select id="total_adults" class="campo_lista_planes_reserva required" name="total_adults">
				        <option value="0">0</option>
				        <option value="1" selected="selected">1</option>
				        <option value="2">2</option>
				        <option value="3">3</option>
				        <option value="4">4</option>
				        <option value="5">5</option>
				        <option value="6">6</option>
				        <option value="7">7</option>
				        <option value="8">8</option>
				        <option value="9">9</option>
				      </select>
				    </span> <span class="red">*</span></td>
				    <td valign="top" align="left"><?php echo JText::_('COM_CP_BOOKING_FIELD_CHILDREN' ); ?></td>
				    <td valign="top" align="left"><span style="font-size:11px;">
				      <select id="total_children" class="campo_lista_planes_reserva required" name="total_children">
				        <option value="0" selected="selected">0</option>
				        <option value="1">1</option>
				        <option value="2">2</option>
				        <option value="3">3</option>
				        <option value="4">4</option>
				        <option value="5">5</option>
				        <option value="6">6</option>
				        <option value="7">7</option>
				        <option value="8">8</option>
				        <option value="9">9</option>
				      </select>
				    </span> <span class="red">*</span></td>
				  </tr>
				  <tr>
				    <td valign="top" align="left" colspan="4"><?php echo JText::_('COM_CP_BOOKING_FIELD_COMMENTS' ); ?></td>
				    </tr>
				  <tr>
				    <td valign="top" align="left" colspan="4"><label>
				      <textarea id="comments" class="campo_coments_plan" rows="5" cols="45" name="comments"></textarea>
				    </label></td>
				    </tr>
				  <tr>
				    <td valign="top" align="left"><a href="javascript:animatedcollapse.hide('conte_form_reserva_plan');"><?php echo JText::_('COM_CP_MINIMIZE' ); ?></a></td>
				    <td valign="top" align="left">&nbsp;</td>
				    <td valign="top" align="left">&nbsp;</td>
				    <td valign="top" align="left"><input type="submit" value="<?php echo JText::_('COM_CP_BOOKING_BUTTON' ); ?>" id="button" class="boton_qs" name="button"></td>
				  </tr>
	              <tr>
	                <td colspan="4" class="mensaje_error"></td>
	              </tr>
				  </tbody>
				</table>
			  	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
				<input type="hidden" name="option" value="com_cp" />
				<input type="hidden" name="view" value="cpproducts" />
				<input type="hidden" name="task" value="saveorder" />
				<input type="hidden" name="id" value="<?php echo $data->product_id; ?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</div>
		<?php
		if (!empty($tags_contents)) {
		?>
		<div class="conte_info_plan"><!-- Open div class conte_info_plan  -->
			<ul class="tabs"><!-- Inicia Lista que forma las pestañas para la navegacion -->
				<?php
				$additional = ' class="active"';
				for ($j = 0; $j < count($tags_contents); $j++) {
					echo '<li' . $additional . '><a href="#tab' . $j . '" id="link_tab' . $j . '">' . $tags_contents[$j][0] . '</a></li>';
					$additional = '';
				}
				?>
			</ul><!-- Cierra Lista que forma las pestañas para la navegacion -->

			<div class="tab_container"><!-- Abro div class tab_container -->
				<?php
				$additional = 'block';
				for ($j = 0; $j < count($tags_contents); $j++) {
					echo '<div class="tab_content" id="tab' . $j . '" style="display: ' . $additional . ';">' . $tags_contents[$j][1] . '</div>';
					$additional = 'none';
				}
				?>
			</div><!-- Cierro div class tab_container -->
			<div class="clear"></div>
		</div>
		<?php
		}
		?>
	</div>

</div>

<div class="banner_pendon_interno"><!-- Open div class banner_pendon_interno  -->
<?php
	jimport( 'joomla.application.module.helper' );
	$modules = JModuleHelper::getModules( 'derecha' );
	$attribs['style'] = 'custom';
	foreach ($modules as $module) {
		echo JModuleHelper::renderModule( $module, $attribs );
	}
?>
</div><!-- Close div class banner_pendon_interno  -->
