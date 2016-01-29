<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: router.php 2012-09-10 18:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Function to convert a system URL to a SEF URL
 */
function Catalogo_planesBuildRoute(&$query) {
  //die(print_r($query));
  $segments = array();
  if(isset($query['view'])){
    $segments[0] = $query['view'];
    unset( $query['view'] );
  }
  if(isset($query['layout'])){
    $segments[1] = $query['layout'];
    unset( $query['layout'] );
  }
  if(isset($query['productId'])){
    $segments[2] = $query['productId'];
    unset( $query['productId'] );
  }
  return $segments;
}
/*
 * Function to convert a SEF URL back to a system URL
 */
function Catalogo_planesParseRoute($segments) {

  $vars = array();
  if(isset($segments[0]))
  $vars['view'] = $segments[0];
  if(isset($segments[1]))
  $vars['layout'] = $segments[1];
  if(isset($segments[2]))
  $vars['productId'] = $segments[2];

  return $vars;
}
?>