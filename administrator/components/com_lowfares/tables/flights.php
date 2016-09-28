<?php
/**
 */
defined('_JEXEC') or die("Invalid access");

/**
 */
class JTableFlights extends JTable
{

    /**
     */
    public function __construct(&$db)
    {
        parent::__construct('#__lf_flights', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }

}
