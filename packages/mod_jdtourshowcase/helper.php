
<?php
/**
 * @package	JD profiler Module
 * @subpackage  mod_jdprofiler
 * @author	Joomdev.com
 * @copyright	Copyright (C) 2008 - 2019 Joomdev.com. All rights reserved
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
// Style Sheet
$doc->addStyleSheet(JURI::root().'media/mod_jdprofiler/assets/css/jd-profile-style.css');

class modJdToursrHelper {
    public function tours($tour,$limit,$order){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__jdprofiler_profiles');
        $query->Where($db->quoteName('state') . ' = '. $db->quote(1));
        $query->Where($db->quoteName('team') . ' = '. $db->quote($team));
        $user = JFactory::getUser();
		$accessLevel = $user->groups;   
		if($accessLevel){ 
			$isAdmin = $user->get('isRoot');
			if ($isAdmin) {
				 $accessLevel[] = '1,6';
			}else{
				 $accessLevel[] = '1';
			}
		 $accessLevel = array_unique($accessLevel);
		  $query->where('access IN ( '.implode(",", $accessLevel).')');
	  }
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
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }
}
