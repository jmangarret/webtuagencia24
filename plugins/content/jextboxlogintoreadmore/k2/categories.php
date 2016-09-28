<?php
/**
 * @version		$Id: categories.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_SITE.'/plugins/content/jextboxlogintoreadmore/k2/base.php');

class K2ElementCategoriesL2RM extends K2Element
{

    function fetchElement($name, $value, &$node, $control_name)
    {

		$db = JFactory::getDbo();
		$db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_k2'");
		$is_enabled = $db->loadResult();
		if(!$is_enabled){
			return '<input type="text" name="" value="K2 is not installed or enabled" readonly>';
		}
		$db = JFactory::getDBO();
        $query = 'SELECT m.* FROM #__k2_categories m WHERE trash = 0 ORDER BY parent, ordering';
        $db->setQuery($query);
        $mitems = $db->loadObjectList();
        $children = array();
        if ($mitems)
        {
            foreach ($mitems as $v)
            {
                if (K2_JVERSION != '15')
                {
                    $v->title = $v->name;
                    $v->parent_id = $v->parent;
                }
                $pt = $v->parent;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        $mitems = array();
        $mitems[] = JHTML::_('select.option', '0', JText::_('K2_NONE_ONSELECTLISTS'));

        foreach ($list as $item)
        {
            $item->treename = JString::str_ireplace('&#160;', ' -', $item->treename);
            $mitems[] = JHTML::_('select.option', $item->id, $item->treename);
        }

        $attributes = 'class="inputbox"';
        if (K2_JVERSION != '15')
        {
            $attribute = K2_JVERSION == '25' ? $node->getAttribute('multiple') : $node->attributes()->multiple;
            if ($attribute)
            {
                $attributes .= ' multiple="multiple" size="10"';
            }
        }
        else
        {
            if ($node->attributes('multiple'))
            {
                $attributes .= ' multiple="multiple" size="10"';
            }
        }

        if (K2_JVERSION != '15')
        {
            $fieldName = $name;
        }
        else
        {
            $fieldName = $control_name.'['.$name.']';
            if ($node->attributes('multiple'))
            {
                $fieldName .= '[]';
            }
        }

        return JHTML::_('select.genericlist', $mitems, $fieldName, $attributes, 'value', 'text', $value);
    }

}

class JFormFieldCategories extends K2ElementCategoriesL2RM
{
    var $type = 'categoriesL2RM';
}

class JElementCategories extends K2ElementCategoriesL2RM
{
    var $_name = 'categoriesL2RM';
}
