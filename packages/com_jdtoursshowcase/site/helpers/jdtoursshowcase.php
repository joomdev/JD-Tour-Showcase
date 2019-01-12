<?php

/**
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('JdtoursshowcaseHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jdtoursshowcase' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'jdtoursshowcase.php');

/**
 * Class JdtoursshowcaseFrontendHelper
 *
 * @since  1.6
 */
class JdtoursshowcaseHelpersJdtoursshowcase
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_jdtoursshowcase/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_jdtoursshowcase/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'JdtoursshowcaseModel');
		}

		return $model;
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function canUserEdit($item)
    {
        $permission = false;
        $user       = JFactory::getUser();

        if ($user->authorise('core.edit', 'com_jdtoursshowcase'))
        {
            $permission = true;
        }
        else
        {
            if (isset($item->created_by))
            {
                if ($user->authorise('core.edit.own', 'com_jdtoursshowcase') && $item->created_by == $user->id)
                {
                    $permission = true;
                }
            }
            else
            {
                $permission = true;
            }
        }

        return $permission;
	}
	


	function hits($hits,$id){
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		// Fields to update.
		$fields = array(
			$db->quoteName('hits') . ' = ' . $db->quote($hits)
		);

		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('id') . ' = '.$id, 
		);

		$query->update($db->quoteName('#__jdtoursshowcase_tours'))->set($fields)->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();
	}
	function tour_type($id){
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);


		$query->select($db->quoteName(array('title')));
		$query->from($db->quoteName('#__jdtoursshowcase_tour_type'));
		$query->where($db->quoteName('id') . ' = '. $db->quote($id));
		$query->order('ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		
		return $result = $db->loadResult();
		 

	}
	
}
