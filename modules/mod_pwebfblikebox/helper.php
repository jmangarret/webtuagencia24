<?php
/**
* @version 1.0.10
* @package PWebFBLikeBox
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modPWebFBLikeBoxHelper
{
	protected static $params = null;
	
	
	public static function setParams(&$params) 
	{
		self::$params = $params;
	}
	
	
	public static function getParams() 
	{
		return self::$params;
	}
	
	
	protected static function getFacebookLocale() 
	{
		$locales = array('af_ZA', 'ar_AR', 'az_AZ', 'be_BY', 'bg_BG', 'bn_IN', 'bs_BA', 'ca_ES', 'cs_CZ', 'cx_PH', 'cy_GB', 'da_DK', 'de_DE', 'el_GR', 'en_GB', 'en_PI', 'en_UD', 'en_US', 'eo_EO', 'es_ES', 'es_LA', 'et_EE', 'eu_ES', 'fa_IR', 'fb_LT', 'fi_FI', 'fo_FO', 'fr_CA', 'fr_FR', 'fy_NL', 'ga_IE', 'gl_ES', 'gn_PY', 'he_IL', 'hi_IN', 'hr_HR', 'hu_HU', 'hy_AM', 'id_ID', 'is_IS', 'it_IT', 'ja_JP', 'jv_ID', 'ka_GE', 'km_KH', 'kn_IN', 'ko_KR', 'ku_TR', 'la_VA', 'lt_LT', 'lv_LV', 'mk_MK', 'ml_IN', 'ms_MY', 'nb_NO', 'ne_NP', 'nl_NL', 'nn_NO', 'pa_IN', 'pl_PL', 'ps_AF', 'pt_BR', 'pt_PT', 'ro_RO', 'ru_RU', 'si_LK', 'sk_SK', 'sl_SI', 'sq_AL', 'sr_RS', 'sv_SE', 'sw_KE', 'ta_IN', 'te_IN', 'th_TH', 'tl_PH', 'tr_TR', 'uk_UA', 'ur_PK', 'vi_VN', 'zh_CN', 'zh_HK', 'zh_TW');
		
		$lang = JFactory::getLanguage();
		$locale = str_replace('-', '_', $lang->getTag());
		
		return in_array($locale, $locales) ? $locale : 'en_US';
	}
	
	
	public static function displayLikeBox() 
	{
		$html = '';
		
		$params = self::getParams();
		
		if (!defined('MOD_PWEB_FBLIKEBOX_SDK')) 
		{
			define('MOD_PWEB_FBLIKEBOX_SDK', 1);
			
			if ($params->get('box_type', 'html5') != 'iframe')
			{
				if ($params->get('fb_root', 1))
				{
					$html .= '<div id="fb-root"></div>';
				}
				if ($params->get('fb_jssdk', 1)) 
				{
					$doc = JFactory::getDocument();
					$doc->addScriptDeclaration(
						'(function(d,s,id){'.
						'var js,fjs=d.getElementsByTagName(s)[0];'.
						'if(d.getElementById(id))return;'.
						'js=d.createElement(s);js.id=id;'.
						'js.src="//connect.facebook.net/'.self::getFacebookLocale().'/sdk.js#xfbml=1'.
						($params->get('fb_appid') ? '&appId='.$params->get('fb_appid') : '').
						'&version=v2.0";'.
						'fjs.parentNode.insertBefore(js,fjs);'.
						'}(document,"script","facebook-jssdk"));'
					);
				}
			}
		}
		
		$width = (int)$params->get('width');
		if (!$width OR $width == 300) 
			$width = null;
		
		//select output format
		switch ($params->get('box_type', 'html5'))
		{
			case 'html5':
				$html .= '<div class="fb-like-box" id="pwebfblikebox'.$params->get('id').'_html5"'
						.' data-href="'.$params->get('href').'"'
						.' data-stream="'.($params->get('show_stream') ? 'true' : 'false').'"'
						.(!$params->get('show_faces', 1) ? ' data-show-faces="false"' : '')
						.(!$params->get('show_header') ? ' data-header="false"' : '')
						.($width ? ' data-width="'.$width.'"' : '')
						.($params->get('height') ? ' data-height="'.(int)$params->get('height').'"' : '')
						.($params->get('colorscheme', 'light') != 'light' ? ' data-colorscheme="'.$params->get('colorscheme').'"' : '')
						.(!$params->get('show_border') ? ' data-show-border="false"' : '')
						.($params->get('force_wall') ? ' data-force-wall="true"' : '')
						.'></div>';
						
				break;
			case 'xfbml':
				$html .= '<fb:like-box id="pwebfblikebox'.$params->get('id').'_xfbml"'
						.' href="'.$params->get('href').'"'
						.' stream="'.($params->get('show_stream') ? 'true' : 'false').'"'
						.(!$params->get('show_faces', 1) ? ' show_faces="false"' : '')
						.(!$params->get('show_header') ? ' header="false"' : '')
						.($width ? ' width="'.$width.'"' : '')
						.($params->get('height') ? ' height="'.(int)$params->get('height').'"' : '')
						.($params->get('colorscheme', 'light') != 'light' ? ' colorscheme="'.$params->get('colorscheme').'"' : '')
						.(!$params->get('show_border') ? ' show_border="false"' : '')
						.($params->get('force_wall') ? ' force_wall="true"' : '')
						.'></fb:like-box>';
				break;
			case 'iframe':
				if (!($height = (int)$params->get('height')))
				{
					$show_faces  = $params->get('show_faces', 1);
					$show_stream = $params->get('show_stream');
					$show_header = $params->get('show_header');
					if (!$show_faces AND !$show_stream)
						$height = 63;
					elseif ($show_faces AND !$show_stream)
						$height = $show_header ? 290 : 258;
					elseif (!$show_faces AND $show_stream)
						$height = $show_header ? 427 : 395;
					elseif ($show_faces AND $show_stream)
						$height = $show_header ? 590 : 558;
				}
				$html .= '<iframe id="pwebfblikebox'.$params->get('id').'_iframe" src="//www.facebook.com/plugins/likebox.php?'
						.'href='.rawurlencode(urldecode($params->get('href')))
						.'&amp;stream='.($show_stream ? 'true' : 'false')
						.(!$show_faces ? '&amp;show_faces=false' : '')
						.(!$show_header ? '&amp;header=false' : '')
						.($width ? '&amp;width='.$width : '')
						.'&amp;height='.$height
						.($params->get('colorscheme', 'light') != 'light' ? '&amp;colorscheme='.$params->get('colorscheme') : '')
						.(!$params->get('show_border') ? '&amp;show_border=false' : '')
						.($params->get('force_wall') ? '&amp;force_wall=true' : '')
						.'&amp;locale='.self::getFacebookLocale()
						.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden;'
						.' width:'.$width.'px;'
						.' height:'.$height.'px;"'
						.' allowTransparency="true"></iframe>';
		}

		return $html;
	}
	
	
	public static function getTrackSocialScript()
	{
		if (defined('PWEB_FB_EVENT_SUBSCRIBE')) return null;
		
		$params = self::getParams();
		if (!$params->get('track_social', 2) OR $params->get('box_type', 'html5') == 'iframe') return null;
		
		define('PWEB_FB_EVENT_SUBSCRIBE', 1);
				
		$script = 
		'if(typeof window.fbAsyncInit=="function")window.fbAsyncInitPweb=window.fbAsyncInit;'.
		'window.fbAsyncInit=function(){'.
			'FB.Event.subscribe("edge.create",function(u){'.
				($params->get('track_social') == 3 
					? 'if(typeof ga!="undefined")ga("send","social","facebook","like",u)'
					: ($params->get('track_social') == 2
						? 'if(typeof _gaq!="undefined")_gaq.push(["_trackSocial","facebook","like",u])'
						: 'if(typeof pageTracker!="undefined")pageTracker._trackSocial("facebook","like",u)'
					)
				).
				($params->get('debug') ? ';console.log("facebook like: "+u)' : '').
			'});'.
			'FB.Event.subscribe("edge.remove",function(u){'.
				($params->get('track_social') == 3 
					? 'if(typeof ga!="undefined")ga("send","social","facebook","unlike",u)'
					: ($params->get('track_social') == 2
						? 'if(typeof _gaq!="undefined")_gaq.push(["_trackSocial","facebook","unlike",u])'
						: 'if(typeof pageTracker!="undefined")pageTracker._trackSocial("facebook","unlike",u)'
					)
				).
				($params->get('debug') ? ';console.log("facebook unlike: "+u)' : '').
			'});'.
			'if(typeof window.fbAsyncInitPweb=="function")window.fbAsyncInitPweb.apply(this,arguments)'.
		'};';

		return $script;
	}
	
	
	public static function getTrackSocialOnClick()
	{
		$params = self::getParams();
		
		return ($params->get('track_social') == 3 
			? 'if(typeof ga!=\'undefined\')ga(\'send\',\'trackSocial\',\'facebook\',\'visit\')'
			: ($params->get('track_social') == 2 
				? 'if(typeof _gaq!=\'undefined\')_gaq.push([\'_trackSocial\',\'facebook\',\'visit\'])'
				: 'if(typeof pageTracker!=\'undefined\')pageTracker._trackSocial(\'facebook\',\'visit\')'
			)
		);
	}
	
	
	public static function getDebugScript()
	{
		$params = self::getParams();
		if (!$params->get('debug')) return null;
		
		$module_id = $params->get('id');
		
		$script = '
var pwebFBLikeBox'.$module_id.'Debug = {
	msg: [],
	display: function() { alert("Perfect Facebook Like Box Sidebar Debug: \r\n"+this.msg.join(" \r\n")) }
};
(function(){
	if (typeof jQuery != "undefined") {
		try {
			if (!(typeof $ == "undefined" || (typeof $ == "function" && $("#pwebfblikebox'.$module_id.'") == null)))
				pwebFBLikeBox'.$module_id.'Debug.msg.push("jQuery.noConflict() mode is not loaded.");
		} catch(err) {console.log(err)}
	}
	
	if (typeof MooTools != "undefined") {
		try {
			var mooVer = MooTools.version.split(".");
			if (!(parseInt(mooVer[0]) == 1 && ((parseInt(mooVer[1]) == 4 && parseInt(mooVer[2]) >= 5) || (parseInt(mooVer[1]) == 3 && parseInt(mooVer[2]) >= 2)))) {
				pwebFBLikeBox'.$module_id.'Debug.msg.push("MooTools version: "+MooTools.version+". Load version 1.3.2 or 1.4.5 or newer.");
				
				var found = false;
				var scripts = document.getElementsByTagName("script");
				for (var i = 0; i < scripts.length; i++) {
					if (typeof scripts[i].src != "undefined") {
						if (scripts[i].src.indexOf("/media/system/js/mootools-core.js") != -1 ||
							scripts[i].src.indexOf("/media/system/js/mootools-core-uncompressed.js") != -1) {
							found = true;
							break; 
						}
					}
				}
				if (!found) pwebFBLikeBox'.$module_id.'Debug.msg.push("Joomla! Core MooTools JavaScript file is removed by template or some system plugin.");
			}
		} catch(err) {console.log(err)}
		try {
			if (!(typeof document.id == "function" && document.id("pwebfblikebox'.$module_id.'")))
				pwebFBLikeBox'.$module_id.'Debug.msg.push("Joomla! MooTools is overridden by other JavaScript library.");
		} catch(err) {console.log(err)}
	}
	else pwebFBLikeBox'.$module_id.'Debug.msg.push("Joomla! Core MooTools is not loaded or broken by other JavaScript library.");
	
	if (pwebFBLikeBox'.$module_id.'Debug.msg.length) setTimeout("pwebFBLikeBox'.$module_id.'Debug.display()", 1000);
})();
';
		return $script;
	}
}
