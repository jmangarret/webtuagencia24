<?php
/**
* @version 1.0.1
* @package PWebFBLikeBox
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

/**
 * Perfect-Web Tpis.
 *
 * @since		1.6
 */
class JFormFieldPwebTip extends JFormField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$html = '';
		
		$app = JFactory::getApplication();
		
		$module_id = $app->input->getInt('id', 0);
		if ($module_id == 0) 
		{
			$module_cid = $app->input->get('cid', array(), 'array');
			if (count($module_cid)) $module_id = (int)$module_cid[0];
		}
		
		if ($module_id > 0) 
		{
			switch ($this->element['tip'])
			{
				case 'code':
					$html = 
					'<pre style="float:left;margin:7px 0;font-size:11px">&lt;a href=&quot;javascript:pwebFBLikeBox'.$module_id.'.toggleBox()&quot;&gt;Click here&lt;/a&gt;</pre>';
					break;
			}
		}

		return $html;
	}
}