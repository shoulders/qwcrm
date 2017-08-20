<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have an employee_id
if($employee_id == '') {
    force_page('user', 'search', 'warning_msg='.gettext("No Employee ID supplied."));
    exit;
}

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Get customer_id
$customer_id =  get_workorder_details($db, $workorder_id, 'customer_id');

// If new schedule item submitted
if(isset($VAR['submit'])) {
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!insert_schedule($db, $VAR['start_date'], $VAR['StartTime'], $VAR['end_date'], $VAR['EndTime'], $VAR['notes'], $employee_id, $customer_id, $workorder_id)) {        
                 
        $smarty->assign('start_date',   $VAR['start_date']                                                  );       
        $smarty->assign('start_time',   $VAR['StartTime']['Time_Hour'].":".$VAR['StartTime']['Time_Minute'] );              
        $smarty->assign('end_date',     $VAR['end_date']                                                    );        
        $smarty->assign('end_time',     $VAR['EndTime']['Time_Hour'].":".$VAR['EndTime']['Time_Minute']     );
        $smarty->assign('notes',        $VAR['notes']                                                       );                
            
    } else {       
            
        // Load the schedule day with the updated schedule item        
        $start_year            = date('Y', date_to_timestamp($VAR['start_date'])  );
        $start_month           = date('m', date_to_timestamp($VAR['start_date'])  );
        $start_day             = date('d', date_to_timestamp($VAR['start_date'])  );    
    
        // Load the schedule day with the newly submitted schedule item
        force_page('schedule', 'day', 'start_year='.$start_year.'&start_month='.$start_month.'&start_day='.$start_day.'&employee_id='.$employee_id.'&workorder_id='.$workorder_id.'&information_msg='.gettext("Schedule Successfully Created"));
        exit;
    }

// If new schedule form is intially loaded, load schedule item from the database and assign
} else {
    
    $smarty->assign('start_date',          convert_year_month_day_to_date($start_year, $start_month, $start_day)    );
    $smarty->assign('start_time',          $VAR['start_time']                                                       );    
    $smarty->assign('end_date',            convert_year_month_day_to_date($start_year, $start_month, $start_day)    );
    $smarty->assign('end_time',            $VAR['start_time']                                                       ); 
    
}

// Build the page
$BuildPage .= $smarty->fetch('schedule/new.tpl');