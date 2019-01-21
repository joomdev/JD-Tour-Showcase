<?php

/**
 * Hello World! Module Entry Point
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

if($app->input->get('option')=='com_jdtoursshowcase' && in_array($app->input->get('view','none'), $params->get('hide_on', array()))) return;

// Include the syndicate functions only once
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
require_once(JPATH_BASE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jdtoursshowcase'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'jdtoursshowcase.php');
require_once(JPATH_BASE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jdtoursshowcase'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');


$layout = $params->get('layout', 'default');
$tour = $params->get('tour', 'default');
$limit = $params->get('limit', '');
$sort = $params->get('sort', '');
$order = $params->get('order', '');
 $tourClass  = new modJdToursrHelper();
 $tours = $tourClass->tours($tour,$limit,$order);
         
require JModuleHelper::getLayoutPath('mod_jdtourshowcase', $layout);