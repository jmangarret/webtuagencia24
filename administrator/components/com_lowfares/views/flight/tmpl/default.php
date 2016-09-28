<?php
/**
 * @file com_geoplanes/admin/views/geoplanes/tmpl/default.php
 * @ingroup _compadmin
 *
 * Vista que visualiza los registros almacenados en la base de datos
 */
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
jimport('joomla.html.html');
jimport('joomla.html.pane');
JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

$type_1 = $this->data->departure != null ? 'checked="checked"' : '';
$type_2 = $this->data->departure == null ? 'checked="checked"' : '';

$published_1 = $this->data->published == 1 || $this->data->id == 0 ? 'checked="checked"' : '';
$published_2 = $this->data->published == 0 && $this->data->id != 0 ? 'checked="checked"' : '';

$this->data->duration = $this->data->duration == '' ? 0 : $this->data->duration;

/*------------------------------------------------------------------------------------------------------*/
/*MICOD.
Aquí generamos los enlaces estáticos para cada oferta de vuelo*/
$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; //Capturamos la URL
$pos1 = strpos($url, "http"); //Nos ubicamos la posición de la URL donde comenzará la sustracción.
$pos2 = strpos($url, "administrator"); //Nos ubicamos la posición de la URL donde terminará la sustracción.
$rest = substr($url, $pos1, $pos2); //Sustraemos la URL correcta para concatenar.

if ($this->data->id == "") {//Comprobamos si la ID existe
  $url_final = "La URL se generará después de guardar"; //Mensaje por default si el ID no existe
}else{
  $url_final = $rest."?id_vuelo=".$this->data->id; //Generamos el enlace para ésta oferta de vuelo
}
/*------------------------------------------------------------------------------------------------------*/
?>
<form action="<?php echo $this->url; ?>" method="post" name="adminForm" autocomplete="off">
  <div class="width-100">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_LOWFARES_INFO'); ?></legend>
      <ul class="adminformlist">
        <li>
        <!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
        <!--MICOD.
        Creamos un espacio en HTML para visualizar el enlace estático de ésta oferta de vuelo-->
          <label for="url" class="hasTip" title="URL fija de éste pasaje aereo, solo se visualizará después de guardar. Dicha URL será funcional solo después de comprobar el precio de la tarifa">
            Dirección URL
          </label>
          <label style="margin-top:-20px; margin-left:140px; font-weight:bold;"><?php echo $url_final; ?></label>
        <!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
        </li>
        <li>
        <li>
          <label for="origin" class="hasTip" title="<?php echo JText::_('COM_LOWFARES_ORIGIN_LABEL').'::'.JText::_('COM_LOWFARES_ORIGIN_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_ORIGIN_LABEL'); ?>
          </label>
          <input type="text" name="jform[origin]" id="origin" class="complete-air" size="10" value="<?php echo $this->data->origin; ?>" />
          <input type="text" name="jform[originname]" id="originname" size="50" value="<?php echo $this->data->originname; ?>" />
        </li>
        <li>
          <label for="destiny" class="hasTip" title="<?php echo JText::_('COM_LOWFARES_DESTINY_LABEL').'::'.JText::_('COM_LOWFARES_DESTINY_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_DESTINY_LABEL'); ?>
          </label>
          <input type="text" name="jform[destiny]" id="destiny" class="complete-air" size="10" value="<?php echo $this->data->destiny; ?>" />
          <input type="text" name="jform[destinyname]" id="destinyname" size="50" value="<?php echo $this->data->destinyname; ?>" />
        </li>

        <li>
          <span class="spacer">
            <span class="before"></span>
              <br/>
            <span class="after"></span>
          </span>
        </li>

        <li>
          <label class="hasTip" title="<?php echo JText::_('COM_LOWFARES_DEPARTURE_TYPE_LABEL').'::'.JText::_('COM_LOWFARES_DEPARTURE_TYPE_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_DEPARTURE_TYPE_LABEL'); ?>
          </label>
          <fieldset class="radio">
            <input type="radio" name="type" id="type_1" value="1" <?php echo $type_1; ?> />
            <label for="type_1"><?php echo JText::_('COM_LOWFARES_FIXED'); ?></label>
            <input type="radio" name="type" id="type_2" value="0" <?php echo $type_2; ?> />
            <label for="type_2"><?php echo JText::_('COM_LOWFARES_PERIOD'); ?></label>
          </fieldset>
        </li>
        <li>
          <label for="departure" class="hasTip" title="<?php echo JText::_('COM_LOWFARES_DEPARTURE_LABEL').'::'.JText::_('COM_LOWFARES_DEPARTURE_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_DEPARTURE_LABEL'); ?>
          </label>
          <input type="text" name="jform[offset]" id="departure" size="10" value="<?php echo $type_1 != '' ? $this->data->departure : $this->data->offset; ?>" />
        </li>
        <li>
          <label for="duration" class="hasTip" title="<?php echo JText::_('COM_LOWFARES_DURATION_LABEL').'::'.JText::_('COM_LOWFARES_DURATION_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_DURATION_LABEL'); ?>
          </label>
          <input type="text" name="jform[duration]" id="duration" size="5" value="<?php echo $this->data->duration; ?>" />
        </li>

        <li>
          <span class="spacer">
            <span class="before"></span>
              <br/>
            <span class="after"></span>
          </span>
        </li>

        <li>
          <label for="jformcategory" class="hasTip" title="<?php echo JText::_('COM_LOWFARES_CATEGORY_LABEL').'::'.JText::_('COM_LOWFARES_CATEGORY_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_CATEGORY_LABEL'); ?>
          </label>
          <?php echo JHTML::_('select.genericlist', JHtml::_('category.options', 'com_lowfares'), 'jform[category]', null, 'value', 'text', $this->data->category); ?>
        </li>
        <li>
          <label class="hasTip" title="<?php echo JText::_('COM_LOWFARES_PUBLISHED_LABEL').'::'.JText::_('COM_LOWFARES_PUBLISHED_DESC'); ?>">
            <?php echo JText::_('COM_LOWFARES_PUBLISHED_LABEL'); ?>
          </label>
          <fieldset class="radio">
            <input type="radio" name="jform[published]" id="published_1" value="1" <?php echo $published_1; ?> />
            <label for="published_1"><?php echo JText::_('JYES'); ?></label>
            <input type="radio" name="jform[published]" id="published_2" value="0" <?php echo $published_2; ?> />
            <label for="published_2"><?php echo JText::_('JNO'); ?></label>
          </fieldset>
        </li>
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
            <!--MICOD
            Nueva opción para cargar imágenes utilizando el gestor de imágenes del JCE en PopUp--> 
          <label for="origin" class="hasTip" title="Imagen de portada para el pasaje aereo">
            Imagen de portada
          </label>
          <div id="menu-pane" class="pane-sliders" style="padding: 10px; width: auto; margin-left: 130px; margin-top:45px; height: auto;">
          <div class="panel" style="display: inline-block;">
            <h3 class="title">Imagen</h3>
            <div style="margin: 0 auto;">
              <a  rel="{handler: 'iframe', size: {x: 800, y: 600}}"  href="<?php echo JURI::base(true); ?>/index.php?option=com_media&view=images&tmpl=component&fieldid=tempimage" class="modal-button"><?php echo JHtml::_('image', 'media/folderup_32.png', '..', array('width' => 32, 'height' => 32), true); ?><br/>
              Cargar imagen</a>

              <ul id="galleryContainer" class="sortable" style="list-style: none outside none; min-width: 250px; margin: 0 auto;">
              <?php
               echo '<li id="imgPos" style="margin: 0 auto; height: auto;"><img src="' . JURI::root() . $this->data->image. '" border="0" width="180" height="120" /><br /><a class="delImage" href="javascript:deleteImg(' . $i . ')">Borrar</a><input type="hidden" name="jform[image]" id="image" value="' .$this->data->image. '" /></li>';
                /*------------------------------------------------------------------------------------------*/
                /*MICOD
                IMPORTANTE!!! Aqui es donde llamo el gestor de imágenes del JCE de Joomla dentro de un POPUP
                El segundo parámetro representa la etiqueta HTML y su "class" que llaman a la acción*/
                JHTML::_('behavior.modal', 'a.modal-button');
                /*------------------------------------------------------------------------------------------*/
              ?>
              </ul>
              <ul id="prueba"></ul>
            </div>
          </div>
          </div>
<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
        </li>
      </ul>
    </fieldset>
  </div>
  <input type="hidden" name="task" value="">
  <input type="hidden" name="jform[id]" id="id" value="<?php echo $this->data->id; ?>">
</form>

<!--////////////////////////////////////////////////////////////////////////////////////////////////////////-->
              <script type="text/javascript">

              /*MICOD
              Script importante para el perfecto funcionamiento del gestor de imágenes en PopUp*/
              var siteURL = "<?php echo JURI::root(); ?>"; // Ruta principal de la página web
              var delText = "Borrar"; // Texto por default

              jQuery(document).ready(function($) {
                    $("#menu-pane a.modal-button").click(function() {
                        return false;
                      });

                    $("#galleryContainer").sortable();
                    $("#galleryContainer").disableSelection();
              });

              //Función principal al que llama el gestón de imágenes del JCE. NO MODIFCARLO
              var oldImageURL = '';
              function jInsertFieldValue(value, id) { 
                var old_value = jQuery("#" + id).value;
                if (old_value != value) {
                  var elem = jQuery("#" + id);
                  elem.value = value;
                  setMedia(value);
                }
              }
              
              //Función encargado de cargar las imágenes con sus etiquetas HTML
              var mediaCount = 0;
              function setMedia(url) {
                if (url.length > 0) {
                   if(jQuery('#imgPos').length){
                    //alert("Existe");
                    jQuery('#imgPos').replaceWith('<li id="imgPos" style="margin: 0 auto; height: auto;"><img src="' + siteURL + url + '" border="0" width="180" height="120" /><br /><a class="delImage" href="javascript:deleteImg(' + mediaCount + ')">' + delText + '</a><input type="hidden" name="jform[image]" id="image" value="' + url + '" /></li>');
                   }else{
                    //alert("No existe");
                    jQuery('#galleryContainer').append('<li id="imgPos" style="margin: 0 auto; height: auto;"><img src="' + siteURL + url + '" border="0" width="180" height="120" /><br /><a class="delImage" href="javascript:deleteImg(' + mediaCount + ')">' + delText + '</a><input type="hidden" name="jform[image]" id="image" value="' + url + '" /></li>');
                   }

                }
              }

              //Borrar imágenes
              function deleteImg(pos) {
                 jQuery('#imgPos').remove();
              }

              </script>
<!--////////////////////////////////////////////////////////////////////////////////////////////////////////-->