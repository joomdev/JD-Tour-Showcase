
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

class modJdToursrHelper {
    public function tours($tour,$limit,$order){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__jdtoursshowcase_tours');
            $query->Where($db->quoteName('state') . ' = '. $db->quote(1));
            $query->Where($db->quoteName('tour_type') . ' = '. $db->quote($tour));
            if($order=="random"){
            $query->order('RAND() LIMIT '.$limit); 
            }elseif($order=="ordering"){
                $query->order($order);
                $query->setLimit($limit);
            }
            else{
                $query->order($order);
                $query->setLimit($limit);
            }
             $query;
            $db->setQuery($query);
            $results = $db->loadObjectList();
         return $results;
     }
}