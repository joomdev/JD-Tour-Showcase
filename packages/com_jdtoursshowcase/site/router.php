<?php

/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright   Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JLoader::registerPrefix('Jdtoursshowcase', JPATH_SITE . '/components/com_jdtoursshowcase/');

/**
 * Class JdtoursshowcaseRouter
 *
 * @since  3.3
 */
class JdtoursshowcaseRouter extends JComponentRouterBase
{
	/**
	 * Build method for URLs
	 * This method is meant to transform the query parameters into a more human
	 * readable form. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();
		$view     = null;

		if (isset($query['task']))
		{
			$taskParts  = explode('.', $query['task']);
			$segments[] = implode('/', $taskParts);
			$view       = $taskParts[0];
			unset($query['task']);
		}

		if (isset($query['view']))
		{
			$segments[] = $query['view'];
			$view = $query['view'];
			
			unset($query['view']);
		}

		if (isset($query['id']))
		{
			if ($view !== null)
			{
				$model      = JdtoursshowcaseHelpersJdtoursshowcase::getModel($view);
				if($model !== null)
				{
					$item       = $model->getItem($query['id']);
					$alias      = $model->getAliasFieldNameByView($view);
					$segments[] = (isset($alias)) ? $item->alias : $query['id'];
				}
			}
			else
			{
				$segments[] = $query['id'];
			}

			unset($query['id']);
		}

		return $segments;
	}

	/**
	 * Parse method for URLs
	 * This method is meant to transform the human readable URL back into
	 * query parameters. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$vars = array();

		// View is always the first element of the array
		$vars['view'] = array_shift($segments);
		$model        = JdtoursshowcaseHelpersJdtoursshowcase::getModel($vars['view']);

		while (!empty($segments))
		{
			$segment = array_pop($segments);

			// If it's the ID, let's put on the request
			if (is_numeric($segment))
			{
				$vars['id'] = $segment;
			}
			else
			{
				$id = $model->getItemIdByAlias(str_replace(':', '-', $segment));
				if (!empty($id))
				{
					$vars['id'] = $id;
				}
				else
				{
					$vars['task'] = $vars['view'] . '.' . $segment;
				}
			}
		}

		return $vars;
	}

	protected static $lookup;
	
	public static function getTourRoute($id) {
		
		$needles = array(
				'tour' => array((int)$id),
				'tours'  => array(0)
		);
	
		
		//Create the link
		$link = 'index.php?option=com_jdtoursshowcase&view=tour&id='.$id;
		
		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		
		return $link;
	}

	public static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_jdtoursshowcase');
			$items		= $menus->getItems('component_id', $component->id);
			if (count($items)) {
                foreach ($items as $item)
                {
                    if (isset($item->query) && isset($item->query['view']))
                    {
                        $view = $item->query['view'];
                        if (!isset(self::$lookup[$view])) {
                            self::$lookup[$view] = array();
                        }
                        
                        self::$lookup[$view][0] = $item->id;
                    }
                }
            }
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					if (is_array($ids)) {
						foreach($ids as $id)
						{
							if (isset(self::$lookup[$view][$id])) {
								return self::$lookup[$view][$id];
							}
						}
					} else if (isset(self::$lookup[$view][$ids])) {
						return self::$lookup[$view][$ids];
					}
				}
			}
		}

		return null;
	}
}
