CREATE TABLE IF NOT EXISTS `#__rsmonials` ( `id` int(11) NOT NULL AUTO_INCREMENT, `fname` varchar(50) NOT NULL, `lname` varchar(50) NOT NULL, `about` text NOT NULL, `location` varchar(255) NOT NULL, `website` varchar(255) NOT NULL, `email` varchar(100) NOT NULL, `comment` text NOT NULL, `date` date NOT NULL, `status` int(1) NOT NULL, PRIMARY KEY (`id`) );

DROP TABLE IF EXISTS `#__rsmonials_param`;

CREATE TABLE IF NOT EXISTS `#__rsmonials_param` ( `id` int(11) NOT NULL AUTO_INCREMENT, `param_name` varchar(100) NOT NULL, `param_description` text NOT NULL, `param_value` text NOT NULL, `ordering` int(3) NOT NULL DEFAULT '100', PRIMARY KEY (`id`) );
		  
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'admin_name', 'Name of Administrator', 'Site Administrator', 1);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'admin_email', 'Email Address of Administrator - This will use for sending and receiving Email', 'admin@yoursite.com', 2);

INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_desc', 'Set "false" to hide the description text appeared just agter the page title in front end. Set "true" to display', 'true', 3);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'login_to_submit_testimonial', 'Here you can specify who can post testimonials/comments. If you wish anyone can post testimonials then set the value of this field to "false". But if you wish only registered/logged in users can post comment then set the value to "true" here.', 'false', 4);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_single_name_field', 'Set "false" to display two name fields (i.e. ''First Name'' and ''Last Name''). Set "true" to display single name field (i.e. ''Your Name'')', 'true', 5);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_about', 'Set "false" to hide "About You" field in front end. Set "true" to display', 'true', 6);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_location', 'Set "false" to hide "Your Location" field in front end. Set "true" to display', 'true', 7);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_website', 'Set "false" to hide "Your Website" field in front end. Set "true" to display', 'true', 8);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_image', 'Set "true" to enable image/picture uploading. Set "false" to disable.', 'true', 9);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'image_max_width', 'If you enabled "show_image", then please set the maximum allowed width of image (in pixel).', '500', 10);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'image_max_height', 'If you enabled "show_image", then please set the maximum allowed height of image (in pixel).', '500', 11);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'image_max_size', 'If you enabled "show_image", then please set the maximum allowed size of image to optimize server load(in kb).', '250', 12);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_captcha', 'Set "false" to hide "Captcha" field in front end. Set "true" to display', 'true', 13);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'use_recaptcha', 'Set "true" if you wish to use "ReCaptcha Library". To enable "ReCaptcha" you need to obtain ReCaptcha "Public API Key" and "Private API Key" from "www.google.com/recaptcha"\r\n\r\nSet "false" to use the component default captcha image.\r\n\r\nWe recommend you to enable ReCaptcha.', 'false', 14);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'recaptcha_public_key', 'If you are using "ReCaptcha" then please enter "ReCaptcha Public API Key" here.', '', 15);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'recaptcha_private_key', 'If you are using "ReCaptcha" then please enter "ReCaptcha Private API Key" here.', '', 16);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'recaptcha_theme', 'Four themes are currently available for ReCaptcha (Red, BlackGlass, White, Clean).\r\n\r\nEnter "1" to enable "Red Theme". Enter "2" to enable "BlackGlass Theme". Enter "3" to enable "White Theme". Enter "4" to enable "Clean Theme".', '1', 17);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'auto_approval', 'Set "false" if you wish to review all new testimonial first and then approve manually. Set "true" if you wish new testimonials will approve and start to dispaly instantly after submission', 'false', 18);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'show_pagination', 'Set "true" to show pagination in front end. Set "false" to show all testimonials in a single page', 'true', 19);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'pagination', 'Set how many testimonials you wish to display in a page. This will only work if you set (show_pagination = true)', '10', 20);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'pagination_alignment', 'Set the text alignment of the pagination => left, right or center', 'center', 21);
		
INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'admin_email_alert', 'Set "true" if you wish to receive one autogenerated email for each and every new testimonial posting. Set "false" to not receive autogenerated email', 'false', 22);
		
DROP TABLE IF EXISTS `#__rsmonials_param_style`;
		  
CREATE TABLE IF NOT EXISTS `#__rsmonials_param_style` ( `id` int(11) NOT NULL AUTO_INCREMENT, `param_name` varchar(100) NOT NULL, `param_description` text NOT NULL, `param_value` text NOT NULL, `ordering` int(3) NOT NULL DEFAULT '100', PRIMARY KEY (`id`) );
		  
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_border', 'Border Style of each Testimonial block:\r\n\r\nExample 1: 1px solid #cccccc\r\nExample 2: 2px dotted #0000ff\r\nExample 3: 1px dashed #006600', '1px solid #dedede', 1);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_background_color', 'Background Color of Testimonial Block:\r\n\r\nExample: #CCCCCC', '#FFFFFF', 2);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_rounded_corner', 'Set "true" to get rounded corner of each testimonial block. Set "false" to get square block.\r\n\r\nNote: Rounded corner will not work in IE (upto IE 8). IE not supports it. From IE 9 it will work. IE9 is till to launch.', 'false', 3);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_rounded_corner_radius', 'If you enabled "Rounded Corner", then you can set the Radius of the rounded corner (in pixel). By default it is 10. ', '10', 4);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_enable_gradient', 'If you wish to use Gradient set this to "true", otherwise "False".\r\n\r\nNote: Gradient will not work in IE (upto IE 8). IE not supports it. From IE 9 it will work. IE9 is till to launch.', 'false', 5);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_gradient_start_color', 'If you enabled "Gradient" then please set the start color of the gradient here.\r\n\r\nExample: #F7F7F7', '#F7F7F7', 6);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_gradient_end_color', 'If you enabled "Gradient" then please set the start color of the gradient here.\r\n\r\nExample: #FFFFFF', '#FFFFFF', 7);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_quotation_image_style', 'Enter "0" to "disable quotation image".\r\nEnter "1" to use "square type quotation image".\r\nEnter "2" to use "round type quotation image".', '2', 8);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_default_image', 'If you enabled the image upload option in your testimonial form, then here you can set the default image.\r\n\r\nEnter "0" to "not display any image if someone do not upload his/her image".\r\nEnter "1" to use "Gray User image if there is no image".\r\nEnter "2" to use "Black User (Male) image if there is no image".\r\nEnter "3" to use "Black User (Female) image if there is no image".', '1', 9);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_image_position', 'If you enabled the image upload option in your testimonial form, then here you can set the default image position.\r\n\r\nEnter "1" to "Display image in left side of testimonial".\r\nEnter "2" to "Display image in right side of testimonial".\r\nEnter "3" to "Display image in alternate side of testimonial (one in left, next one in right and so on...)".', '1', 10);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_image_display_width', 'Display width of the testimonial image (in pixel).', '125', 11);
		
INSERT INTO `#__rsmonials_param_style` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'testimonial_block_show_date', 'Enter "true" to display the date of submission of the testimonial.\r\nEnter "false" to not display the date of submission of the testimonial.', 'true', 12);