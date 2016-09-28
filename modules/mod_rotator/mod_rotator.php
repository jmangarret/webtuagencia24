<?php
/**
 * @file mod_rotator/mod_rotator.php
 * @defgroup _module Módulo
 * Archivo de entrada para el módulo de Rotadores. Usa la libreria de javascript
 * nivoSlider (http://nivo.dev7studios.com/)
 */
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'helper.php');
modRotatorHelper::init($params);
