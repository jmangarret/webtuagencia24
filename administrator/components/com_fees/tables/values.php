<?php
/**
 */
defined('_JEXEC') or die("Invalid access");

/**
 */
class JTableValues extends JTable
{

    /**
     */
    public function __construct(&$db)
    {
        parent::__construct('#__fee_values', 'id', $db);
    }

}
