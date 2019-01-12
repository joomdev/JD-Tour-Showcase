<?php
/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jdtoursshowcase'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Jdtoursshowcase', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('JdtoursshowcaseHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'jdtoursshowcase.php');

$controller = JControllerLegacy::getInstance('Jdtoursshowcase');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
