
<?php
/**
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

class JDtourRoute
{
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
?>
