<?php
/**
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.modelform');

/**
 */
class AawsModelGeneral extends JModelForm
{

    private $form      = null;

    private $component = null;


    public function getForm($data = array(), $loadData = true)
    {
        JForm::addFormPath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_aaws'.DS.'forms');

        $this->form = $this->loadForm(
            'com_aaws.general',
            'general',
            array('control' => 'jform', 'load_data' => $loadData),
            false,
            '/form'
        );

        if(empty($this->form))
            return false;

        $this->getComponent();

		if ($this->form && $this->component->params)
			$this->form->bind($this->component->params);

        return $this->form;
    }

    public function getComponent()
    {
        if($this->component == null)
        {
            $option          = JRequest::getCmd('option');
            $this->component = JComponentHelper::getComponent($option);
        }

        return $this->component;
    }

    public function save($data)
    {
        $table = JTable::getInstance('extension');

        // Load the previous Data
        if (!$table->load($data['id']))
        {
            $this->setError($table->getError());
            return false;
        }

        unset($data['id']);

        // Bind the data.
        if (!$table->bind($data))
        {
            $this->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check())
        {
            $this->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store())
        {
            $this->setError($table->getError());
            return false;
        }

        // Clean the component cache.
        $this->cleanCache('_system');

        return true;
    }

}
