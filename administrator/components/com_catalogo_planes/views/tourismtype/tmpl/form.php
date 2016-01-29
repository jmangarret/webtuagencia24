<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
$data =& $this->data;
JHTML::_('behavior.modal', 'a.modal-button');
?>
<script type="text/javascript">

    //Funcion que recibe la respuesta del componente de media
    //Joomla.jInsertEditorText = function(tag, editor) {    
    function jInsertEditorText(tag, editor){
    	var src = jQuery(tag).attr('src');
    	if (src.indexOf('http://') < 0 && src.indexOf('https://') < 0) {
    		visibleURL = '<?php echo JURI::root();?>' + src;
    	} else {
    		visibleURL = src;
    	}
    	jQuery("#"+editor).attr("src", visibleURL);
    	jQuery("#image_hidden").val(src);
    }
    Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		// do field validation
		if (jQuery.trim(form.tourismtype_name.value) == "") {
			alert("<?php echo JText::_('CP.TOURISMTYPE_ERROR_EMPTY_NAME', true); ?>");
		} else {
			submitform(pressbutton);
		}
	}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
    <fieldset class="adminform">
     <legend><?php echo JText::_('CP.DETAILS'); ?></legend>
        <table class="admintable">
            <tr>
                <td align="right" class="key">
                    <label for="tourismtype_name">
                            <?php echo JText::_('CP.TOURISMTYPE_NAME'); ?>:*
                    </label>
                </td>
                <td>
                    <input class="text_area" type="text" name="tourismtype_name" id="tourismtype_name" size="32" maxlength="100" value="<?php echo htmlspecialchars($data->tourismtype_name, ENT_COMPAT, 'UTF-8'); ?>" />
                </td>
            </tr>
            <tr>
                <td align="right" class="key">
                    <label for="supplement_value">
                        <?php echo JText::_('CP.TOURISMTYPE_IMAGEURL'); ?>:
                    </label>
                </td>
            	<td>
            		<a rel="{handler: 'iframe', size: {x: 570, y: 450}}" href="<?php echo JURI::base(); ?>index.php?option=com_media&view=images&tmpl=component&e_name=imageurl" class="modal-button">
            			<img src="<?php echo JURI::base(); ?>components/com_media/images/folderup_32.png" />
            			<br /><?php echo JText::_('CP.PRODUCT_IMAGES_DESC'); ?>
            		</a><br/>
            		<img src="<?php echo ($this->data->image != "") ? JURI::root().$this->data->image : ""; ?>" id="imageurl"/>
            		<input type="hidden" name="image" id="image_hidden" value="<?php echo ($this->data->image != "") ? $this->data->image : ""; ?>"/>
            	</td>

            </tr>
			<tr>
				<td align="right" class="key">
					<label for="publishedqs">
						<?php echo JText::_('CP.PUBLISHEDQS'); ?>:*
					</label>
				</td>
				<td>
					<?php echo JHTML::_('select.booleanlist', 'publishedqs', null, $data->publishedqs, JText::_('CP.YES'), JText::_('CP.NO'), false); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label for="published">
						<?php echo JText::_('CP.STATE'); ?>:*
					</label>
				</td>
				<td>
					<?php echo JHTML::_('select.booleanlist', 'published', null, $data->published, JText::_('CP.ACTIVE'), JText::_('CP.INACTIVE'), false); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="tourismtype_id" value="<?php echo $data->tourismtype_id; ?>" />
<input type="hidden" name="task" value="" />
</form>