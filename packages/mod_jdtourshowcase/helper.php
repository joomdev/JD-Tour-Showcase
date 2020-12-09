
<?php
/**
 * @package	JD tour Module
 * @subpackage  tour
 * @author	JoomDev
 * @copyright	Copyright (C) 2008 -  2019 Joomdev.com. All rights reserved
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$doc = JFactory::getDocument();

if ($params->get('load_fontawesome', 0)) {
    $doc->addStyleSheet('https://use.fontawesome.com/releases/v5.6.3/css/all.css');
}

class modJdToursrHelper {
    public function tours($tours, $limit, $order) {
        if (is_array($tours)) {
            $tours = implode(",", $tours);
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__jdtoursshowcase_tours');
            $query->where($db->quoteName('state') . ' = ' . $db->quote(1));
            $query->where($db->quoteName('tour_type') . " IN ($tours)");
            if ($order == "random") {
                $query->order('RAND() LIMIT ' . $limit);
            } elseif ($order == "ordering") {
                $query->order($order);
                $query->setLimit($limit);
            } else {
                $query->order($order);
                $query->setLimit($limit);
            }

            $db->setQuery($query);
            $results = $db->loadObjectList();
            return $results;
        }
    }

    public function tour($tours)
    {
        if (isset($tours)) {
            $slides = [];
            foreach ($tours as $tour) {
                $slides[] = $tour->tour;
            }
            $slides = implode(",", $slides);

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__jdtoursshowcase_tours');
            $query->where($db->quoteName('state') . ' = ' . $db->quote(1));
            $query->where($db->quoteName('ordering') . " IN ($slides)");

            $db->setQuery($query);
            $results = $db->loadObjectList();

            return $results;
        }
    }
}