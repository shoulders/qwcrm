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

// Prevent undefined variable errors
//\QFactory::$VAR['qform']['note'] = isset(\QFactory::$VAR['qform']['note']) ? \QFactory::$VAR['qform']['note'] : null; 

// Check if we have an employee_id
if(!isset(\QFactory::$VAR['employee_id']) || !\QFactory::$VAR['employee_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No Employee ID supplied."));
}

// Check if we have a workorder_id
if(!isset(\QFactory::$VAR['workorder_id']) || !\QFactory::$VAR['workorder_id']) {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Get client_id
\QFactory::$VAR['client_id'] =  get_workorder_details(\QFactory::$VAR['workorder_id'], 'client_id');

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
    
    // If insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!insert_schedule(\QFactory::$VAR['qform'])) {        
                 
       $smarty->assign('schedule_details', \QFactory::$VAR['qform']);
            
    } else {       
            
        // Break up the date into segments in the correct format
        $start_year            = date('Y', date_to_timestamp(\QFactory::$VAR['qform']['start_date'])  );
        $start_month           = date('m', date_to_timestamp(\QFactory::$VAR['qform']['start_date'])  );
        $start_day             = date('d', date_to_timestamp(\QFactory::$VAR['qform']['start_date'])  );
    
        // Load the schedule day with the newly submitted schedule item
        force_page('schedule', 'day', 'start_year='.$start_year.'&start_month='.$start_month.'&start_day='.$start_day.'&employee_id='.\QFactory::$VAR['qform']['employee_id'].'&workorder_id='.\QFactory::$VAR['qform']['workorder_id'].'&information_msg='._gettext("Schedule Successfully Created"));
        
        // Load the updated schedule details page
        //force_page('schedule', 'details&schedule_id='.\QFactory::$VAR['qform']['schedule_id'], 'information_msg='.gettext("Schedule Successfully Updated."));
        
    }

// If new schedule form is intially loaded, load schedule item from the database and assign
} else {
    
    // Build the page
    $schedule_details = array();
    $schedule_details['client_id']      = \QFactory::$VAR['client_id'];
    $schedule_details['employee_id']    = \QFactory::$VAR['employee_id'];
    $schedule_details['workorder_id']   = \QFactory::$VAR['workorder_id'];
    $schedule_details['start_date']     = convert_year_month_day_to_date(\QFactory::$VAR['start_year'], \QFactory::$VAR['start_month'], \QFactory::$VAR['start_day']);
    $schedule_details['start_time']     = \QFactory::$VAR['start_time'];
    $schedule_details['end_date']       = convert_year_month_day_to_date(\QFactory::$VAR['start_year'], \QFactory::$VAR['start_month'], \QFactory::$VAR['start_day']);
    $schedule_details['end_time']       = \QFactory::$VAR['start_time'];
    $schedule_details['note']           = null;    
    $smarty->assign('schedule_details', $schedule_details);
    
}