<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have a schedule_id
if(!isset(\QFactory::$VAR['schedule_id']) || !\QFactory::$VAR['schedule_id']) {
    force_page('schedule', 'search', 'warning_msg='._gettext("No Schedule ID supplied."));
}

// If new schedule item submitted
if(isset(\QFactory::$VAR['submit'])) { 
        
    // Add missing Time variables to 'qform' (smarty workaround)
    \QFactory::$VAR['qform']['StartTime'] = \QFactory::$VAR['StartTime'];
    \QFactory::$VAR['qform']['EndTime'] = \QFactory::$VAR['EndTime'];
    
    // Add missing Time variables in DATETIME format
    \QFactory::$VAR['qform']['start_time'] = smartytime_to_otherformat('datetime', \QFactory::$VAR['qform']['start_date'], \QFactory::$VAR['StartTime']['Time_Hour'], \QFactory::$VAR['StartTime']['Time_Minute'], '0', '24');
    \QFactory::$VAR['qform']['end_time']   = smartytime_to_otherformat('datetime', \QFactory::$VAR['qform']['end_date'], \QFactory::$VAR['EndTime']['Time_Hour'], \QFactory::$VAR['EndTime']['Time_Minute'], '0', '24');
    
    /* This manually builds a 'Time' string
    \QFactory::$VAR['qform']['start_time'] = \QFactory::$VAR['StartTime']['Time_Hour'].":".\QFactory::$VAR['StartTime']['Time_Minute'];
    \QFactory::$VAR['qform']['end_time'] = \QFactory::$VAR['EndTime']['Time_Hour'].":".\QFactory::$VAR['EndTime']['Time_Minute'];*/
    
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!update_schedule(\QFactory::$VAR['qform'])) {        
        
        // Build the page
        $smarty->assign('schedule_details', \QFactory::$VAR['qform']);
        $smarty->assign('active_employees', get_active_users('employees'));                      
            
    } else {       
        
        /* Load the schedule day with the updated schedule item        
        \QFactory::$start_year            = date('Y', date_to_timestamp(\QFactory::$VAR['qform']['start_date'])  );
        \QFactory::$start_month           = date('m', date_to_timestamp(\QFactory::$VAR['qform']['start_date'])  );
        \QFactory::$start_day             = date('d', date_to_timestamp(\QFactory::$VAR['qform']['start_date'])  );         
        force_page('schedule', 'day', 'start_year='.$start_year.'&start_month='.$start_month.'&start_day='.$start_day.'&employee_id='.\QFactory::$VAR['qform']['employee_id'].'&workorder_id='.\QFactory::$VAR['qform']['workorder_id'].'&information_msg='._gettext("Schedule Successfully Updated."));
        */
        
        // Load the updated schedule details page
        force_page('schedule', 'details&schedule_id='.\QFactory::$VAR['qform']['schedule_id'], 'information_msg='.gettext("Schedule Successfully Updated."));
        
    }

// If edit schedule form is loaded, get schedule item from the database and assign
} else {
    
    // Build the page       
    $smarty->assign('schedule_details', get_schedule_details(\QFactory::$VAR['schedule_id']));
    $smarty->assign('active_employees', get_active_users('employees')       );
    
}