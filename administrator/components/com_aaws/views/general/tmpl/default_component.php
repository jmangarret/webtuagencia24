<?php
/**
 * @file com_quicksearch/admin/views/quicksearch/tmpl/default.php
 * @ingroup _comp_adm
 * HTML de la vista de administración del componente
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="width-40 fltlft">
  <div class="width-100">
    <fieldset class="adminform">
      <legend><?php echo JText::_($this->tpl_fieldSet->label); ?></legend>
      <ul class="config-option-list">
      <?php
      foreach($this->form->getFieldset($this->tpl_name) as $field)
      {
          echo '<li>';
          if (!$field->hidden)
              echo $field->label;

          echo $field->input;
          echo '</li>';
      }
      ?>
      </ul>
    </fieldset>
  </div>
</div>

