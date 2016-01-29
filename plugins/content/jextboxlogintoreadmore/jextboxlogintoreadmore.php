<?php

/**
* @extension     JExtBOX Login to Read More
* @author        Galaa
* @publisher     JExtBOX - BOX of Joomla Extensions (www.jextbox.com)
* @authorUrl     www.galaa.mn
* @authorEmail   contact@galaa.mn
* @copyright     Copyright (C) 2011-2015 Galaa
* @license       This extension in released under the GNU/GPL License - http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

class plgContentJextboxLoginToReadMore extends JPlugin
{

	function onContentPrepare($context, &$article, &$params, $page = 0)
	{

		// Check view mode
		if(!in_array($context, array('com_content.article', 'com_content.category', 'com_content.featured', 'com_k2.item', 'com_k2.itemlist', 'com_k2.latest')))
		{
			return;
		}

		if(!isset($article->JextboxLoginToReadMore_applied))
		{
			$article->JextboxLoginToReadMore_applied = 1;
		}
		else
		{
			return;
		}

		// Search engine bot
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html
		if($this->isbot(strtolower($this->params->get('search_engine_bots'))))
		{
			return;
		}

		// Remove empty paragraphs
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html

		// Check excluded categories or articles
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html
			// Check exluded categories
			if(in_array($context, array('com_content.article', 'com_content.category', 'com_content.featured')))
			{
				// Joomla core Content component
				$categories = $this->params->get('categories', array());
			}
			elseif(in_array($context, array('com_k2.item', 'com_k2.itemlist', 'com_k2.latest')))
			{
				// K2 component
				$categories = $this->params->get('categories_k2', array());
			}
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html
			if(!in_array($article->catid, $categories))
			{
				return;
			}
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html

		// Modify content for guest or registered users
		$user = JFactory::getUser();
		if(!$user->guest)
		{ // full view granted for registred users
			if(in_array($context, array('com_content.article', 'com_content.category', 'com_content.featured')))
			{
				// nothing changed
			}
			elseif(in_array($context, array('com_k2.item', 'com_k2.itemlist', 'com_k2.latest')))
			{
				if($context == 'com_k2.item')
				{
					$article->fulltext = $article->introtext . $article->fulltext;
					$article->introtext = '';
					$article->text = '';
				}
				elseif($context == 'com_k2.itemlist')
				{ // category, blog, tag modes
					// nothing changed
				}
				elseif($context == 'com_k2.latest')
				{
					// nothing changed
				}
			}
		}
		else
		{ // Remind login or register to guest visitors
			// Prepare login message
			switch($this->params->get('login', 'default_login'))
			{
				case 'default_login':
					$login_url = "index.php?option=com_users&view=login";
					$login_text = strip_tags($this->params->get('login_text'));
					$login_title = $this->params->get('login_title');
					$login = "<a href=\"$login_url\" title=\"$login_title\">$login_text</a>";
					break;
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html
				case 'load_module':
					$module_name = trim($this->params->get('login_loadmodule'));
					if(empty($module_name)){
						$login_url = "index.php?option=com_users&view=login";
						$login_text = $this->params->get('login_text');
						$login_title = $this->params->get('login_title');
						$login = "<a href=\"$login_url\" title=\"$login_title\">$login_text</a>";
					}else{
						$login = '{loadmodule '.$module_name.'}';
					}
					break;
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html
			}
			// visibility of an article & login message
			switch($this->params->get('article_visibility', 'show_intro_text_only'))
			{
				case 'show_intro_text_only':
					$show_login_msg = !empty($article->fulltext);
					break;
		// HIDDEN FOR FREE VERSION. FULL VERSION @ http://jextbox.com/jextbox-login-to-read-more.html
				default:
					$show_login_msg = false;
			}
			// Combine a message
			if(in_array($context, array('com_content.article', 'com_content.category', 'com_content.featured')))
			{
				if($context != 'com_content.article')
				{
					$article->readmore = 0;
				}
				if($show_login_msg)
				{
					$article->introtext .= $login;
				}
				$article->text = $article->introtext;
				$article->fulltext = '';
			}
			elseif(in_array($context, array('com_k2.item', 'com_k2.itemlist', 'com_k2.latest')))
			{
				if($context == 'com_k2.item')
				{
					if($show_login_msg)
					{
						$article->introtext .= $login;
					}
				}elseif($context == 'com_k2.itemlist')
				{ // category, blog, tag modes
					$article->text = $article->introtext;
					if($show_login_msg)
					{
						$article->text .= $login;
					}
				}elseif($context == 'com_k2.latest')
				{
					if($show_login_msg)
					{
						$article->text .= $login;
					}
				}
				$article->fulltext = '';
			}
		}

	} // onContentPrepare

	private function isbot($search_bots)
	{ // Check search engine bot

		$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if(empty($search_bots))
		{
			$search_bots = 'googlebot,yahoo,msnbot,slurp,webcrawler,zyborg,scooter,stackrambler,aport,lycos,webalta,ia_archiver,fast';
		}
		$search_bots = explode(',', $search_bots);
		foreach($search_bots as $bot)
		{
			if (strpos($useragent, $bot) !== false)
			{
				return true;
			}
		}
		return false;

	} // isbot

} // class

?>
