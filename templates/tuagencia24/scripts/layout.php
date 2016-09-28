<?php

$doc = JFactory::getDocument();






$user = JFactory::getUser();



// Head
$doc->setGenerator('Social Impulse');
$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/main.css');
$doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/main.js');

// Layout
$col_logo = 'col-6';
$col_info = 'col-6';
$col_top_b = 'col-6';
$col_top_c = 'col-6';
$col_menu_footer = 'col-9';
$col_info_footer = 'col-3';
$col_bottom_b = 'col-7';
$col_bottom_c = 'col-5';
$col_side_a = 'col-3';
$col_side_b = 'col-3';
$col_main = 'col-6';

if ($this->countModules('logo') && !$this->countModules('info')) {
	$col_logo = 'col-12';
}

if (!$this->countModules('logo') && $this->countModules('info')) {
	$col_info = 'col-12';
}

if ($this->countModules('top-b') && !$this->countModules('top-c')) {
	$col_top_b = 'col-12';
}

if (!$this->countModules('top-b') && $this->countModules('top-c')) {
	$col_top_c = 'col-12';
}

if ($this->countModules('menu-footer') && !$this->countModules('info-footer')) {
	$col_menu_footer = 'col-12';
}

if (!$this->countModules('menu-footer') && $this->countModules('info-footer')) {
	$col_info_footer = 'col-12';
}

if ($this->countModules('bottom-b') && !$this->countModules('bottom-c')) {
	$col_bottom_b = 'col-12';
}

if (!$this->countModules('bottom-b') && $this->countModules('bottom-c')) {
	$col_bottom_c = 'col-12';
}

if ($this->countModules('side-a') && !$this->countModules('side-b')) {
	$col_side_a = 'col-3';
	$col_main = 'col-9';
}

if (!$this->countModules('side-a') && $this->countModules('side-b')) {
	$col_side_b = 'col-3';
	$col_main = 'col-9';
}

if (!$this->countModules('side-a') && !$this->countModules('side-b')) {
	$col_main = 'col-12';
}


?>