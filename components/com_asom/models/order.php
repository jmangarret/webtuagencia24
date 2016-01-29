<?php
/**
 * @file com_geoplanes/admin/models/geoplanes.php
 * @ingroup _compadmin
 * Clase que administra los datos relativos a la administración del componente,
 * como el filtrado de registros, las columnas y sus ordenes,
 * cantidad de registros visibles por pantalla, entre otros.
 */

defined('_JEXEC') or die("Invalid access");

class AsomModelOrder extends JModel
{
 
    private $_order = null;

    private $_orderID = null;


    private function _getOrder()
    {
        if($this->_order == null)
        {
            // Directorio que contiene la libreria que consta de interfaces y clases para
            // integrarse con el AmadeuS Order Manager
            $library = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_asom'.DS.'library';

            // Se registra el directorio, para que dinamicamente cargue las clases necesarias.
            JLoader::registerPrefix('Asom', $library);

            $this->_order = new AsomClassOrder($this->_orderID);
        }

        return $this->_order;
    }

    public function setOrderID($order)
    {
        $this->_orderID = (int) $order;
    }

    public function getOrder()
    {
        $this->_getOrder();

        return $this->_order->getOrder();
    }

    public function getSource()
    {
        $this->_getOrder();

        return $this->_order->getSource();
    }

public function getAditional()
    {
        $this->_getOrder();

        return $this->_order->getAditional();
    }
public function getHistorial()
    {
        $this->_getOrder();

        return $this->_order->getHistorial();
    }
}
