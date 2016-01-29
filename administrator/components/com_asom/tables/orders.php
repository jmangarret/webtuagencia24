<?php
/**
 * @file com_sales/admin/tables/sl_gds.php
 * @ingroup _comp_adm
 * Archivo que contiene la descripcion de la tabla de reservas.
 */
defined('_JEXEC') or die("Invalid access");

/**
 * @brief Clase que mapea la tabla reservas de la base de datos
 */
class JTableOrders extends JTable
{

    /**
     * @brief Constructor de la clase
     * @param resource $db Conexion activa de la base de datos.
     */
    public function __construct(&$db)
    {
        parent::__construct('#__aom_orders', 'id', $db);
    }

    /**
     * @brief Verifica que los datos sean validos
     */
    public function check()
    {
        // Check if the order already exists mams.827

        // Se valida que el correo sea valido
        if(!JMailHelper::isEmailAddress($this->email))
        {
			$this->setError(JText::_('ASOM_EMAIL_ERROR'));
			return false;
        }

        // Se valida el valor total de la orden, el cual debe coincidir con el detalle de la misma
        /*if($this->total != ($this->fare + $this->taxes + $this->fare_ta + $this->taxes_ta))
        {
			$this->setError(JText::_('ASOM_TOTAL_ERROR'));
			return false;
        }*/
        $mivalor = ($this->fare + $this->taxes + $this->fare_ta + $this->taxes_ta);
        if( (int)$this->total != (int)$mivalor)
        {
           $this->setError(JText::_('ASOM_TOTAL_ERROR'));
           return false;
        }

        // Si es una orden nueva y el campo estado esta vacio se coloca el por defecto
        if($this->id == 0 && $this->status == null)
        {
            $db    = $this->getDBO();
            $query = $db->getQuery(true);

            $query->select('id');
            $query->from('#__aom_statuses');
            $query->where('default_status = 1');

            $db->setQuery($query);
            $status = $db->loadResult();
            if($status == '')
            {
                $this->setError(JText::_('ASOM_DEFAULT_STATUS'));
                return false;
            }

            $this->status = $status;
        }

        // Se coloca la fecha del sistema
        if($this->id == 0 ){
            $date         = JFactory::getDate();
            $this->fecsis = $date->toSql();
        }
        return true;
    }

}
