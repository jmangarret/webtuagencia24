<?php
/**
 */
defined('_JEXEC') or die("Invalid access");

/**
 */
class JTableGroups extends JTable
{

    /**
     */
    public function __construct(&$db)
    {
        parent::__construct('#__fee_groups', 'id', $db);
    }

}
