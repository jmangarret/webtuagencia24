<?php

/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003 - 2013, Think Network GmbH, Konstanz
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * @package joomfish
 * @subpackage Models
 *
 */
defined('_JEXEC') or die('Restricted access');

/**
 * filters translations based on creation/modification date of original
 *
 */
class translationChangedFilter extends translationFilter
{

	public function __construct($contentElement)
	{
		$this->filterNullValue = -1;
		$this->filterType = "lastchanged";
		$this->filterField = $contentElement->getFilter("changed");
		list($this->_createdField, $this->_modifiedField) = explode("|", $this->filterField);
		parent::__construct($contentElement);

	}

	public function createFilter()
	{
		if (!$this->filterField)
			return "";
		$filter = "";
		if ($this->filter_value != $this->filterNullValue && $this->filter_value == 1)
		{
			// translations must be created after creation date so no need to check this!
			$filter = "( c.$this->_modifiedField>0 AND jfc.modified < c.$this->_modifiedField)";
		}
		else if ($this->filter_value != $this->filterNullValue)
		{
			$filter = "( ";
			$filter .= "( c.$this->_modifiedField>0 AND jfc.modified >= c.$this->_modifiedField)";
			$filter .= " OR ( c.$this->_modifiedField=0 AND jfc.modified >= c.$this->_createdField)";
			$filter .= " )";
		}

		return $filter;

	}

	public function createFilterHTML()
	{
		$db = JFactory::getDBO();

		if (!$this->filterField)
			return "";
		$ChangedOptions = array();
		$ChangedOptions[] = JHTML::_('select.option', -1, JText::_('FILTER_BOTH'));
		$ChangedOptions[] = JHTML::_('select.option', 1, JText::_('ORIGINAL_NEWER'));
		$ChangedOptions[] = JHTML::_('select.option', 0, JText::_('TRANSLATION_NEWER'));

		$ChangedList = array();
		$ChangedList["title"] = JText::_('TRANSLATION_AGE');
		$ChangedList["html"] = JHTML::_('select.genericlist', $ChangedOptions, $this->filterType . '_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value);

		return $ChangedList;

	}

}