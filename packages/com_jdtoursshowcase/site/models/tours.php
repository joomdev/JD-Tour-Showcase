<?php

/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

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
				'id', 'a.id',
				'title', 'a.title',
				'tour_type', 'a.tour_type',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'tour_image', 'a.tour_image',
				'price', 'a.price',
				'discount_value', 'a.discount_value',
				'duration', 'a.duration',
				'destination', 'a.destination',
				'gallery', 'a.gallery',
				'tour_description', 'a.tour_description',
				'facilities_description', 'a.facilities_description',
				'facilities_features', 'a.facilities_features',
				'schedule_title', 'a.schedule_title',
				'schedule_description', 'a.schedule_description',
				'hits', 'a.hits',
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
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
      $app  = Factory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) Factory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

            // List state information.
            parent::populateState($ordering, $direction);

            $app = Factory::getApplication();

            $ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
            $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

            $this->setState('list.ordering', $ordering);
            $this->setState('list.direction', $direction);

            $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
            $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

            if ($limit == 0)
            {
                $limit = $app->get('list_limit', 0);
            }

            $this->setState('list.limit', $limit);
            $this->setState('list.start', $start);
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
				$item = JFactory::getApplication()->getMenu()->getActive();
				$ordering = 'recent';
				if ($item) {
					$ordering = $item->params->get('ordering', 'recent');
				}
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
            
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		$query->select('tour_type.title as tour_type');
		$query->join('LEFT', '#__jdtoursshowcase_tour_type AS `tour_type` ON `tour_type`.id = a.`tour_type`');
		//$query->join('LEFT', 'FIND_IN_SET(#__jdtoursshowcase_tour_type.id, a.`tour_type`)');
            
		$query->where('a.state = 1');

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
				$query->where('( a.title LIKE ' . $search . ' )');
                }
            }
            

		// Filtering tour_type
		$filter_tour_type = $this->state->get("filter.tour_type");
		if ($filter_tour_type != '') {
			$query->where('FIND_IN_SET(' . $db->quote($filter_tour_type) . ', ' . $db->quoteName('a.tour_type') . ')');
		}

            // Add the list ordering clause.
				switch ($ordering) {
					case 'recent':
						$orderCol  = "a.id";
						$orderDirn = "DESC";
						break;
					case 'oldest':
						$orderCol  = "a.id";
						$orderDirn = "ASC";
						break;
					case 'mosthits':
						$orderCol  = "a.hits";
						$orderDirn = "DESC";
						break;
					case 'leasthits':
						$orderCol  = "a.hits";
						$orderDirn = "ASC";
						break;
					default:
						$orderCol  = $this->state->get('list.ordering', "a.id");
						$orderDirn = $this->state->get('list.direction', "ASC");
				}

			//Global ordering parameters
			$jinput = JFactory::getApplication()->input;
			$app = JFactory::getApplication();
			$currentMenuId = $app->getMenu()->getActive()->id;
			$menuitem   = $app->getMenu()->getItem($currentMenuId);
			$mparams = $menuitem->query; 
			
			//echo "<pre>";
			//print_r($mparams);
			
			
			
			if(isset($mparams['order']) && !empty($mparams['order'])){
				$OrderBy = explode(" ",$mparams['order']);
				$OrderByCol = $OrderBy[0];
				$OrderByDir = $OrderBy[1];
				
			}else{
				$OrderByCol = 'a.id';
				$OrderByDir = 'ASC' ;
			}
				
			
			// Add the list ordering clause.
			$orderCol  = $this->state->get('list.ordering', $OrderByCol);
			$orderDirn = $this->state->get('list.direction', $OrderByDir);

            if ($orderCol && $orderDirn)
            {
                $query->order($db->escape($orderCol . ' ' . $orderDirn));
            }
 
            return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		// foreach ($items as $item)
		// {

		// 	// Get the title of every option selected
		// 	$options      = explode(',', $item->tour_type);
		// 	$options_text = array();

		// 	foreach ((array) $options as $option)
		// 	{
		// 		$options_text[] = JText::_('COM_JDTOURSSHOWCASE_TOURS_TOUR_TYPE_OPTION_' . strtoupper($option));
		// 	}

		// 	$item->tour_type = !empty($options_text) ? implode(',', $options_text) : $item->tour_type;
		// }

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_JDTOURSSHOWCASE_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
