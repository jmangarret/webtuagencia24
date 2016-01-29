<?php
/**
 * @file com_asom/admin/controller.php
 * @ingroup _comp_adm
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsomViewEditAir extends JView
{

    /// Contiene el ID de la orden que se va a editar (Se obtiene desde el controlador)
    public $id = 0;

    public function display($tpl = null)
    {
        $model = $this->getModel();
        $data  = $model->getData($this->id);
                
        $this->toolbar();

        if($data)
        {
            $this->assign('data'  , $data);
            $this->assign('url'   , $model->getUrl());
            $this->assign('status', $this->dataStatus());

            parent::display($tpl);
        }
        else
            parent::display('error');

    }

    /**
     * @brief Crea la barra de herramientas de la vista actual.
     */
    public function toolbar()
    {
        JToolBarHelper::title( JText::_( 'AOM_ORDERS_EDIT' ), 'module.png' );
        JToolBarHelper::save('orders.save');
        JToolBarHelper::apply('orders.apply');
        JToolBarHelper::cancel('orders.cancel');
    }

    public function dataStatus()
    {
        $data  = array();
        $model = $this->getModel();

        $statuses = $model->getStatuses();

        foreach($statuses as $status)
            $data[$status->id] = array('name' => $status->name, 'color' => $status->color);

        return $data;
    }

    public function transformArrayValues($info)
    {
        $result = array();

        foreach($info->values as $type => $values)
        {
            foreach($values as $tax => $value)
            {
                if(!isset($result[$tax]))
                    $result[$tax] = array();

                $result[$tax][$type] = $value;
            }
        }

        $data             = new stdClass();
        $data->passengers = $info->passengers;
        $data->values     = $result;

        return $data;
    }
    
    public function filterStatus($id_estatus)
    {
        $model = $this->getModel();

        $data = $model->getStatuses($id_estatus);

        $options = array(JHTML::_('select.option', '', JText::_('AOM_SELECT')));
        foreach($data as $key => $value)
        {   
            $options[] = JHTML::_('select.option', $value->id, $value->name);
        }
        $attribs  = 'class="inputbox" id="status" ';
        //$attribs .= 'onchange="document.adminForm.submit();"';

        return JHTML::_('select.genericlist', $options, 'status', $attribs, 'value', 'text', $id_estatus);
    }
    
    

}
