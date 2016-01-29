<?php 
/**
 * @version		$Id: $
 * @author		Codextension
 * @package		Joomla!
 * @subpackage	Module
 * @copyright	Copyright (C) 2008 - 2012 by Codextension. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div>
	<iframe 
		src="http://www.facebook.com/plugins/likebox.php?
		href=<?php echo $url?>&amp;
		width=<?php echo $width;?>&amp;
		colorscheme=<?php echo $color;?>&amp;
		connections=<?php echo $connections;?>&amp;
		stream=<?php echo $stream;?>&amp;
		header=<?php echo $header;?>&amp;
		height=<?php echo $height;?>" 
		scrolling="no" 
		frameborder="0" 
		style="border:none; overflow:hidden; width:<?php echo $width;?>px;height:<?php echo $height;?>px;" 
		allowTransparency="true">
	</iframe>
</div>