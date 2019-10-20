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
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!update_schedule(\QFactory::$VAR)) {        
        
        $smarty->assign('start_date',       \QFactory::$VAR['start_date']                                                  );       
        $smarty->assign('start_time',       \QFactory::$VAR['StartTime']['Time_Hour'].":".\QFactory::$VAR['StartTime']['Time_Minute'] );                
        $smarty->assign('end_date',         \QFactory::$VAR['end_date']                                                    );        
        $smarty->assign('end_time',         \QFactory::$VAR['EndTime']['Time_Hour'].":".\QFactory::$VAR['EndTime']['Time_Minute']     );
        $smarty->assign('note',             \QFactory::$VAR['note']                                                        );        
        $smarty->assign('active_employees', get_active_users('employees')                                       );                      
            
    } else {       
        
        /* Load the schedule day with the updated schedule item        
        \QFactory::$VAR['start_year']            = date('Y', date_to_timestamp(\QFactory::$VAR['start_date'])  );
        \QFactory::$VAR['start_month']           = date('m', date_to_timestamp(\QFactory::$VAR['start_date'])  );
        \QFactory::$VAR['start_day']             = date('d', date_to_timestamp(\QFactory::$VAR['start_date'])  );    
    
        // Load the schedule day with the updated schedule item
        force_page('schedule', 'day', 'start_year='.\QFactory::$VAR['start_year'].'&start_month='.\QFactory::$VAR['start_month'].'&start_day='.\QFactory::$VAR['start_day'].'&employee_id='.\QFactory::$VAR['employee_id'].'&workorder_id='.\QFactory::$VAR['workorder_id'].'&information_msg='._gettext("Schedule Successfully Updated"));
        */
        
        // Load the workorder page
        force_page('schedule', 'details&schedule_id='.\QFactory::$VAR['schedule_id']);
        
    }

// If edit schedule form is loaded, get schedule item from the database and assign
} else {
    
    // Get the Schedule Record
    $schedule_details = get_schedule_details(\QFactory::$VAR['schedule_id']);
    
    $smarty->assign('employee_id',      $schedule_details['employee_id']    );    
    $smarty->assign('client_id',        $schedule_details['client_id']      );
    $smarty->assign('workorder_id',     $schedule_details['workorder_id']   );
    $smarty->assign('start_date',       $schedule_details['start_time']     );       
    $smarty->assign('start_time',       $schedule_details['start_time']     );         
    $smarty->assign('end_date',         $schedule_details['end_time']       );         
    $smarty->assign('end_time',         $schedule_details['end_time']       );   
    $smarty->assign('note',             $schedule_details['note']           );
    $smarty->assign('active_employees', get_active_users('employees')       );
    
}

// Build the page
\QFactory::$BuildPage .= $smarty->fetch('schedule/edit.tpl');