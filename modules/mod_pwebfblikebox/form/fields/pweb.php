<?php
/**
* @version 1.0.8
* @package PWebFBLikeBox
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

JFormHelper::loadFieldClass('Radio');

/**
 * Perfect-Web
 *
 * @since		1.6
 */
class JFormFieldPweb extends JFormFieldRadio
{
	protected $extension = 'mod_pwebfblikebox';
	protected $documentation = 'http://www.perfect-web.co/joomla/facebook-like-box-sidebar/documentation';
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$doc = JFactory::getDocument();
		
		// add documentation toolbar button
		if (version_compare(JVERSION, '3.0.0') == -1) {
			$button = '<a href="'.$this->documentation.'" style="font-weight:bold;border-color:#025A8D;background-color:#DBE4E9;" target="_blank"><span class="icon-32-help"> </span> '.JText::_('MOD_PWEBFBLIKEBOX_DOCUMENTATION').'</a>';
		} else {
			$button = '<a href="'.$this->documentation.'" class="btn btn-small btn-info" target="_blank"><i class="icon-support"> </i> '.JText::_('MOD_PWEBFBLIKEBOX_DOCUMENTATION').'</a>';
		}
		$bar = JToolBar::getInstance();
		$bar->appendButton('Custom', $button, $this->extension.'-docs');
		
		// add script
		$doc->addScript(JUri::root(true).'/media/mod_pwebfblikebox/js/admin.js');
		JText::script('MOD_PWEBFBLIKEBOX_WIDTH_MESSAGE');
		
		// add feed script
		if ($this->value)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('manifest_cache');
			$query->from('#__extensions');
			$query->where('type = "module"');
			$query->where('element = "mod_pwebfblikebox"');
			$db->setQuery($query);
			
			try {
				$manifest_str = $db->loadResult();
			} catch (RuntimeException $e) {
				$manifest_str = null;
			}
			$manifest = new JRegistry($manifest_str);
			
			$doc->addScriptDeclaration(
				'(function(){'.
				'var pw=document.createElement("script");pw.type="text/javascript";pw.async=true;'.
				'pw.src="https://www.perfect-web.co/index.php?option=com_pwebshop&view=updates&format=raw&extension='.$this->extension.'&version='.$manifest->get('version', '1.0.0').'&jversion='.JVERSION.'&host='.urlencode(JUri::root()).'";'.
				'var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(pw,s);'.
				'})();'
			);
		}
		
		return parent::getInput();
	}
}