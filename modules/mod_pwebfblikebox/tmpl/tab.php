<?php
/**
* @version 1.0.5
* @package PWebFBLikeBox
* @copyright © 2013 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die('Restricted access');

$moduleclass 	= 'pwebfblikebox-'. $params->get('align') 
				. ($params->get('style_radius') ? ' pwebfblikebox-radius' : '')
				. ($params->get('style_shadow') ? ' pwebfblikebox-shadow' : '')
				. ' tab' 
				. ' '. htmlspecialchars($params->get('moduleclass_sfx'));
$style 			= $params->get('top', -1) >= 0 ? ' style="top:'.(int)$params->get('top').'px"' : '';
$style_tab 		= ($background = $params->get('background')) ? ' style="background-color:'.$background.';border-color:'.$background.'"' : '';
$track 			= modPWebFBLikeBoxHelper::getTrackSocialOnClick();
$onclick 		= $track ? ' onclick="'.$track.'"' : '';

?>
<!-- PWebFBLikeBox -->
<div id="pwebfblikebox<?php echo $module->id; ?>" class="pwebfblikebox <?php echo $moduleclass; ?>"<?php echo $style.($params->get('rtl') ? ' dir="rtl"' : ''); ?>>
	<a class="pwebfblikebox_tab <?php echo $params->get('tab'); ?>" href="<?php echo $params->get('href'); ?>" target="_blank" rel="nofollow"<?php echo $style_tab . $onclick; ?>></a>
</div>
<!-- PWebFBLikeBox end -->