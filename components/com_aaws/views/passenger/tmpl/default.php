<div id="passenger_main">
    <div class="waiting">
        <div class="loading"></div>
        <div class="title-1"><?php echo JText::_('COM_AAWS_PASSENGER_TITLE_1'); ?></div>
        <div class="title-2"><?php echo JText::_('COM_AAWS_PASSENGER_TITLE_2'); ?></div>
        <div class="error-label" style="display:none;"><?php echo JText::_('COM_AAWS_ERROR_LABEL'); ?></div>
    </div>
</div>

<div id="overlay" class="overlay"></div>
 
<div id="tos" class="modal modalAir" style="display:none;">
  <a href="#" class="closeBtn"></a>
  <?php if($this->tos_id): ?>
    <iframe src="index.php?option=com_content&view=article&tmpl=component&id=<?php echo $this->tos_id; ?>">
    </iframe>
  <?php else: ?>
    <p>EMPTY TOS</p>
  <?php endif; ?>
 </div>
