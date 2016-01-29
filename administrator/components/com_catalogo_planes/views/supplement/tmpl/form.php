<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$document =& JFactory::getDocument();
$document->addScriptDeclaration('var textareaLengthExceeded = "' . JText::_('CP.PRODUCT_ERROR_TEXTAREA_LENGTH_EXCEEDED') . '";');

$data =& $this->data;
$tmpl = strtolower(JRequest::getVar('tmpl'));

JHTML::_('behavior.modal', 'a.modal-button');
jimport('joomla.html.pane');
$myTabs = & JPane::getInstance('tabs');
?>
<script type="text/javascript">
	jQuery.noConflict()(document).ready(function ($) {
            <?php
            if ($data->imageurl) {
            // Mostrar la imagen si existe
            echo 'jQuery(\'#supplementImageContainer\').html(\'<img src="' . JURI::root() . $data->imageurl . '" border="0" class="supplement_image" />\')';
            }
            ?>

            // Lo que debe pasar cuando se da click en el botón de "Cargar Imagen"
	    $("a.modal-button").click(function() {
	        showImageIframe();
	        return false;
	    });
	});

	function showImageIframe() {
	    if (!jQuery("#sbox-window").is(':visible')) {
	        setTimeout(function() {showImageIframe();}, 200); //wait
	        return;
	    }

	    var f = jQuery('#sbox-window iframe');
	    var ocfns, btns;

	    if (f[0] == undefined) {
	        setTimeout(function() {showImageIframe();}, 200); //wait
	        return;
	    } else {
	        f.load(function() {//wait
	            var imageManager = f[0].contentWindow.ImageManager;
	            imageManager.onok = function() {
	                url = imageManager.fields.url.value;
	                jQuery('#imageurl').val(url);
	                jQuery('#supplementImageContainer').html('<img src="<?php echo JURI::root(); ?>' + url + '" border="0" class="supplement_image" />');
	            };
	        });
	    }
	}

	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		// do field validation
		if (jQuery.trim(form.supplement_name.value) == "") {
                    alert("<?php echo JText::_('CP.SUPPLEMENT_ERROR_EMPTY_NAME', true); ?>");
                    form.supplement_name.focus();
                } else if (jQuery.trim(form.supplement_code.value) == "") {
                    alert("<?php echo JText::_('CP.SUPPLEMENT_ERROR_EMPTY_CODE', true); ?>");
                    form.supplement_code.focus();
                } else {
                // validar que al menos seleccione un tipo de producto y un tipo de turismo
                var submit = false;
                for (var i = 0; i < document.forms['adminForm']['producttypes[]'].length; i++) {
                    if (document.forms['adminForm']['producttypes[]'][i].checked) {
                        submit = true;
                        break;
                    }
                }
                if (!submit) {
                    alert("<?php echo JText::_('CP.SUPPLEMENT_ERROR_EMPTY_PRODUCT_TYPE', true); ?>");
                    return;
                }
                // validar que al menos seleccione un tipo de producto y un tipo de turismo
                submit = false;
                var select = document.forms['adminForm']['tourismtypes[]'];
                for (var i = 0; i < select.length; i++) {
                    if (select[i].selected) {
                        submit = true;
                        break;
                    }
                }
                if (!submit) {
                    alert("<?php echo JText::_('CP.SUPPLEMENT_ERROR_EMPTY_TOURISM_TYPE', true); ?>");
                    return;
                }
                <?php if ($tmpl == 'component'): ?>
                return true;
                <?php else: ?>
                            submitform(pressbutton);
                            <?php endif; ?>
		}
	}

	<?php if ($tmpl == 'component'): ?>
	function saveData () {
		document.adminForm.task.value = "apply";
        if (!submitbutton("apply")) {
            return;
        }
        jQuery("#adminForm input:button").attr("disabled", "disabled");
        jQuery.post("<?php echo JURI::base(true); ?>/index.php", jQuery("#adminForm").serialize(), function (data, textStatus, jqXHR) {
            if (data.result == 'message') {
                window.parent.addSupplement(data.supplement_id);
                parent.SqueezeBox.close();
            } else {
                jQuery("#errorMessage").html(data.message);
                jQuery("#adminForm input:button").removeAttr("disabled");
            }
        }, 'json');
	}
	<?php endif; ?>
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="supplementdiv" class="col100">
<?php if ($tmpl == 'component'): ?>
    <div id="errorMessage"></div>
    <fieldset class="adminform">
        <legend><?php echo JText::_('CP.SUPPLEMENT') ?></legend>
        <input type="hidden" name="format" value="raw">
        <div style="float: right">
            <input type="button" onclick="saveData();" value="<?php echo JText::_('SAVE') ?>" />
            <input type="button" onclick="parent.SqueezeBox.close();" value="<?php echo JText::_('CANCEL') ?>" />
        </div>
    </fieldset>
<?php endif; ?>
<?php
echo $myTabs->startPane('rowpane');
echo $myTabs->startPanel(JText::_('CP.DETAILS'), 'details');
?>
	<table class="admintable">
		<tr>
			<td align="right" class="key">
				<label for="supplement_name">
					<?php echo JText::_('CP.SUPPLEMENT_NAME'); ?>: *
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="supplement_name" id="supplement_name" size="32" maxlength="50" value="<?php echo htmlspecialchars($data->supplement_name, ENT_COMPAT, 'UTF-8'); ?>" />
			</td>
		</tr>
            <tr>
                <td align="right" class="key">
                    <label for="supplement_code">
                        <?php echo JText::_('CP.SUPPLEMENT_CODE'); ?>: *
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="supplement_code" id="supplement_code" size="10" maxlength="10" value="<?php echo $data->supplement_code; ?>" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="supplement_value">
                        <?php echo JText::_('CP.SUPPLEMENT_IMAGEURL'); ?>:
                    </label>
                </td>
                <td nowrap="nowrap">
                    <?php if ($tmpl == 'component' && $data->supplement_id < 1): ?>
                    <?php echo JHTML::_('list.images', 'imageurl', $data->imageurl, null, $this->supplement_image_folder); ?>
                    <br /><br /><img src="<?php echo $data->imageurl? '../' . $data->imageurl: 'images/blank.png'; ?>" name="imagelib" />
                    <?php else: ?>
                    <a rel="{handler: 'iframe', size: {x: 570, y: 450}}" href="<?php echo JURI::base(true); ?>/index.php?option=com_media&view=images&tmpl=component&e_name=imageurl&noeditor=1" class="modal-button"><img src="<?php echo JURI::base(true); ?>/components/com_media/images/folderup_32.png" /><br /><?php echo JText::_('CP.PRODUCT_IMAGES_DESC'); ?></a>
                    <?php echo JText::_('CP.SUPPLEMENT_IMAGE_UPLOAD_DESC'); ?>
                    <input type="hidden" name="imageurl" id="imageurl" value="<?php echo $data->imageurl; ?>" /><br />
                    <div id="supplementImageContainer">
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="supplement_value">
                        <?php echo JText::_('CP.SUPPLEMENT_DESCRIPTION'); ?>:<br />
                        <?php echo JText::_('CP.SUPPLEMENT_DESCRIPTION_LENGTH_MAXIMUM'); ?>
                    </label>
                </td>
                <td>
                    <textarea name="description" id="description" cols="30" rows="5"><?php echo htmlspecialchars($data->description, ENT_COMPAT, 'UTF-8'); ?></textarea>
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="producttypes">
                        <?php echo JText::_('CP.SUPPLEMENT_PRODUCT_TYPES'); ?>: *
                    </label>
                </td>
                <td>
                    <?php
                    // Mostrar los tipos de producto a los que aplica
                    $selectedProductTypes = array();
                    if (is_array($data->producttypes)) {
                        foreach ($data->producttypes as $type) {
                            $selectedProductTypes[] = $type;
                        }
                    }
                    if (is_array($this->productTypes)) {
                        foreach ($this->productTypes as $type) {
                            if (!$data->supplement_id || in_array($type->product_type_id, $selectedProductTypes)) {
                                $checked = ' checked';
                            } else {
                                $checked = '';
                            }
                            echo '<input type="checkbox" name="producttypes[]" value="' . $type->product_type_id . '"' .  $checked . ' />' .  $type->product_type_name . '<br />';
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="tourismtypes">
                        <?php echo JText::_('CP.SUPPLEMENT_TOURISM_TYPES'); ?>: *
                    </label>
                </td>
                <td>
                    <?php
                    // Mostrar los tipos de tourismo a los que aplica
                    $selectedtourismTypes = array();
                    if (is_array($data->tourismtypes)) {
                        foreach ($data->tourismtypes as $type) {
                            $selectedtourismTypes[] = $type;
                        }
                    }
                    if (is_array($this->tourismTypes)) {
                        foreach ($this->tourismTypes as $key => $type) {
                        // Si está inactivo, mostrar con asterisco
                            if ($type->published != 1) {
                                $this->tourismTypes[$key]->tourismtype_name .= '*';
                            }
                        }
                    }
                    echo JHTML::_('select.genericlist', $this->tourismTypes, 'tourismtypes[]', 'class="multiselect" multiple="multiple" size="8"', 'tourismtype_id', 'tourismtype_name', $selectedtourismTypes);
                    ?>
                </td>
            </tr>
		<tr>
			<td align="right" class="key">
				<label for="published">
					<?php echo JText::_('CP.STATE'); ?>: *
				</label>
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist', 'published', null, $data->published, JText::_('CP.ACTIVE'), JText::_('CP.INACTIVE'), false); ?>
			</td>
		</tr>
	</table>
<?php
echo $myTabs->endPanel();

// Mostrar listado de productos relacionados si es un registro ya creado
if ($data->supplement_id > 0) {
    echo $myTabs->startPanel(JText::_('CP.ROW_RELATED_PRODUCTS'), 'relatedProducts');
    if (empty($this->productList)) {
        echo JText::_('CP.ROW_RELATED_PRODUCTS_EMPTY');
    } else {
        $content = '';
        $i = 1;
        foreach ($this->productList as $row) {
            $content .= '<div class="relatedrows">' . $i . '. <a href="index.php?option=' . $option . '&view=' . $row->product_type_code . 
                '&task=edit&cid[]=' . $row->product_id . '">' . $row->product_type_name . ' - ' . $row->product_name . '</a></div>';
            $i++;
        }
        echo $content;
    }
    echo $myTabs->endPanel();
}
echo $myTabs->endPane();
?>
</div>
<div class="clr"></div>

<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="supplement_id" value="<?php echo $data->supplement_id; ?>" />
<input type="hidden" name="task" value="" />
</form>