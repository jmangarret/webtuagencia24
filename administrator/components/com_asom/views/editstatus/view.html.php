<?php
/**
 * @file com_asom/admin/controller.php
 * @ingroup _comp_adm
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsomViewEditStatus extends JView
{

    /// Contiene el ID de la orden que se va a editar (Se obtiene desde el controlador)
    public $id = 0;

    public function display($tpl = null)
    {
        $model = $this->getModel();
        $data  = $model->getData($this->id);

        $this->toolbar();

        if($this->task == 'edit' && $data->id == 0)
            parent::display('error');
        else
        {
            $this->assign('data', $data);
            $this->assign('url', $model->getUrl());
            parent::display($tpl);
        }

    }

    /**
     * @brief Crea la barra de herramientas de la vista actual.
     */
    public function toolbar()
    {
        JToolBarHelper::title( JText::_( 'AOM_STATUS_EDIT' ), 'module.png' );
        JToolBarHelper::save('statuses.save');
        JToolBarHelper::apply('statuses.apply');
        JToolBarHelper::cancel('statuses.cancel');
    }

}
