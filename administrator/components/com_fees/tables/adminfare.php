<?php
/**
 */
defined('_JEXEC') or die("Invalid access");

/**
 */
class JTableAdminFare extends JTable
{

    /**
     */
    public function __construct(&$db)
    {
        parent::__construct('#__fee_adminfare', 'id', $db);
    }

}
