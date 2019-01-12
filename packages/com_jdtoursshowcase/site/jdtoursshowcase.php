<?php
/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright   2019 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Jdtoursshowcase', JPATH_COMPONENT);
JLoader::register('JdtoursshowcaseController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Jdtoursshowcase');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
