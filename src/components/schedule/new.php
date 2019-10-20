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
$VAR['note'] = isset($VAR['note']) ? $VAR['note'] : null; 

// Check if we have an employee_id
if(!isset($VAR['employee_id']) || !$VAR['employee_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No Employee ID supplied."));
}

// Check if we have a workorder_id
if(!isset($VAR['workorder_id']) || !$VAR['workorder_id']) {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}


// Get client_id
$VAR['client_id'] =  get_workorder_details($VAR['workorder_id'], 'client_id');

// If new schedule item submitted
if(isset($VAR['submit'])) {
    
    // If insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!insert_schedule($VAR)) {        
                 
        $smarty->assign('start_date',   $VAR['start_date']                                                  );       
        $smarty->assign('start_time',   $VAR['StartTime']['Time_Hour'].":".$VAR['StartTime']['Time_Minute'] );              
        $smarty->assign('end_date',     $VAR['end_date']                                                    );        
        $smarty->assign('end_time',     $VAR['EndTime']['Time_Hour'].":".$VAR['EndTime']['Time_Minute']     );
        $smarty->assign('note',         $VAR['note']                                                        );                
            
    } else {       
            
        // Load the schedule day with the updated schedule item        
        $VAR['start_year']            = date('Y', date_to_timestamp($VAR['start_date'])  );
        $VAR['start_month']           = date('m', date_to_timestamp($VAR['start_date'])  );
        $VAR['start_day']             = date('d', date_to_timestamp($VAR['start_date'])  );    
    
        // Load the schedule day with the newly submitted schedule item
        force_page('schedule', 'day', 'start_year='.$VAR['start_year'].'&start_month='.$VAR['start_month'].'&start_day='.$VAR['start_day'].'&employee_id='.$VAR['employee_id'].'&workorder_id='.$VAR['workorder_id'].'&information_msg='._gettext("Schedule Successfully Created"));
    }

// If new schedule form is intially loaded, load schedule item from the database and assign
} else {
    
    $smarty->assign('start_date',          convert_year_month_day_to_date($VAR['start_year'], $VAR['start_month'], $VAR['start_day'])    );
    $smarty->assign('start_time',          $VAR['start_time']   );    
    $smarty->assign('end_date',            convert_year_month_day_to_date($VAR['start_year'], $VAR['start_month'], $VAR['start_day'])    );      
    $smarty->assign('end_time',            $VAR['start_time']   ); 
    $smarty->assign('note',                $VAR['note']);  
    
}

// Build the page
\QFactory::$BuildPage .= $smarty->fetch('schedule/new.tpl');