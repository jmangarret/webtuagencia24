<?php
/**
 * @file mod_random_planes/mod_random_planes.php
 * @defgroup _module Módulo
 * Archivo de entrada para el módulo
 *
 * @mainpage Módulo de Carrusel de Planes Aleatorios
 * @section Introducción
 *
 * Este módulo, le agrega al sitio un carrusel de planes con pestañas,
 * totalmente configurable. Para lograr el objetivo se usa la libreria de
 * jQuery "JCarrusel", además de programar la interacción de las pestañas.
 */
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).DS.'helper.php');
modRandomPlanesHelper::init($params);
