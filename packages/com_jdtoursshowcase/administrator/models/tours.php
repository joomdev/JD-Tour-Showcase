<?php

/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Jdtoursshowcase records.
 *
 * @since  1.6
 */
class JdtoursshowcaseModelTours extends JModelList
{
    
        
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'title', 'a.`title`',
				'tour_type', 'a.`tour_type`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'tour_image', 'a.`tour_image`',
				'price', 'a.`price`',
				'duration', 'a.`duration`',
				'destination', 'a.`destination`',
				'gallery', 'a.`gallery`',
				'tour_description', 'a.`tour_description`',
				'facilities_description', 'a.`facilities_description`',
				'facilities_features', 'a.`facilities_features`',
				'schedule_title', 'a.`schedule_title`',
				'schedule_description', 'a.`schedule_description`',
				'hits', 'a.`hits`',
			);
		}

		parent::__construct($config);
	}

    
        
    
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering tour_type
		$this->setState('filter.tour_type', $app->getUserStateFromRequest($this->context.'.filter.tour_type', 'filter_tour_type', '', 'string'));

		// Filtering discount_value
		$this->setState('filter.discount_value', $app->getUserStateFromRequest($this->context.'.filter.discount_value', 'filter_discount_value', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_jdtoursshowcase');
 
		$this->setState('params', $params);

		parent::populateState("a.id", "ASC");

		// $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
		// $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

		// if ($limit == 0)
		// {
		// 	$limit = $app->get('list_limit', 0);
		// }

		// $this->setState('list.limit', $limit);
		// $this->setState('list.start', $start);
        
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
      return parent::getStoreId($id);
                
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{

		//$params = JComponentHelper::getParams('com_jdtoursshowcase');
		//echo $params->get ( 'test' );

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__jdtoursshowcase_tours` AS a');
                

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		$query->select('tour_type.title as tour_type');
		$query->join('LEFT', '#__jdtoursshowcase_tour_type AS `tour_type` ON `tour_type`.id = a.`tour_type`');
		//$query->join('LEFT', 'FIND_IN_SET(' . $db->quote($filter_tour_type) . ', ' . $db->quoteName('a.tour_type') . ')');
		 

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.title LIKE ' . $search . '  OR  a.tour_type LIKE ' . $search . '  OR  a.checked_out_time LIKE ' . $search . '  OR  a.tour_image LIKE ' . $search . '  OR  a.price LIKE ' . $search . '  OR  a.gallery LIKE ' . $search . ' )');
			}
		}
                

		// Filtering tour_type
		$filter_tour_type = $this->state->get("filter.tour_type");

		if ($filter_tour_type !== null && (is_numeric($filter_tour_type) || !empty($filter_tour_type)))
		{
			$query->where('FIND_IN_SET(' . $db->quote($filter_tour_type) . ', ' . $db->quoteName('a.tour_type') . ')');
		}

		// Filtering discount_value
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', "a.id");
		$orderDirn = $this->state->get('list.direction', "ASC");

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
                
		// foreach ($items as $oneItem)
		// {

		// 	// Get the title of every option selected.

		// 	$options      = explode(',', $oneItem->tour_type);

		// 	$options_text = array();

		// 	foreach ((array) $options as $option)
		// 	{
		// 		$options_text[] = JText::_('COM_JDTOURSSHOWCASE_TOURS_TOUR_TYPE_OPTION_' . strtoupper($option));
		// 	}

		// 	$oneItem->tour_type = !empty($options_text) ? implode(', ', $options_text) : $oneItem->tour_type;
		// }

		return $items;
	}
}
