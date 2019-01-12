<?php

/**
 
 * @package    Com_Jdtoursshowcase
 * @author     JoomDev <info@joomdev.com>
 * @copyright  Copyright (C) 2019 Joomdev, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class JdtoursshowcaseViewTour extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = JdtoursshowcaseHelper::getActions();

		JToolBarHelper::title(JText::_('COM_JDTOURSSHOWCASE_TITLE_TOUR'), 'tour.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('tour.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('tour.save', 'JTOOLBAR_SAVE');
		}

		// If an existing item, can save to a copy.
		$total = $this->get_total();
			if($total < 50 ){
				if (!$checkedOut && ($canDo->get('core.create')))
				{
					JToolBarHelper::custom('tour.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				}

			// If an existing item, can save to a copy.
			if (!$isNew && $canDo->get('core.create'))
			{
				JToolBarHelper::save2copy('tour.save2copy', 'JTOOLBAR_SAVE_AS_COPY', false);
			}

			// Button for version control
			if ($this->state->params->get('save_history', 1) && $user->authorise('core.edit')) {
				JToolbarHelper::versions('com_jdtoursshowcase.tour', $this->item->id);
			}

			if (empty($this->item->id))
			{
				JToolBarHelper::cancel('tour.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				JToolBarHelper::cancel('tour.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}
	public function get_total()
	{
		  // Get a db connection.
		  $db = JFactory::getDbo();

		  // Create a new query object.
		  $query = $db->getQuery(true);

		  // Select all records from the user profile table where key begins with "custom.".
		  // Order it by the ordering field.
		  $query->select("count(*) as total");
		  $query->from($db->quoteName('#__jdtoursshowcase_tours'));

		  // Reset the query using our newly populated query object.
		  $db->setQuery($query);

		  // Load the results as a list of stdClass objects (see later for more options on retrieving data).
		  
			$results = $db->loadObject();
			return  $results->total;
	}
}