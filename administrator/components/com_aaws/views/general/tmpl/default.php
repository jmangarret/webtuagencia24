<?php
/**
 */
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task){
  if (document.formvalidator.isValid(document.id('adminForm')))
    Joomla.submitform(task, document.getElementById('adminForm'));
}
</script>

<form action="<?php echo $this->url; ?>" method="post" id="adminForm" name="adminForm" autocomplete="off" class="form-validate">
  <div id="config-document">
    <div class="noshow">
    <?php
      $fieldSets = $this->form->getFieldsets();
      foreach($fieldSets as $name => $fieldSet)
      {
          $this->tpl_name     = $name;
          $this->tpl_fieldSet = $fieldSet;
          echo $this->loadTemplate($name);
      }
    ?>
    </div>
  </div>
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="id" value="<?php echo $this->component->id ?>" />
  <input type="hidden" name="component" value="<?php echo $this->component->option ?>" />
  <input type="hidden" name="option" value="<?php echo $this->component->option ?>" />
</form>

