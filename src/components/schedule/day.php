<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/schedule.php');
require(INCLUDES_DIR.'components/user.php');
require(INCLUDES_DIR.'components/workorder.php');

// Check the workorder status - We don't want to schedule/reschedule a workorder if it's closed
if(isset($VAR['workorder_id'])) { 
    
    // If the workorder is closed, remove the workorder_id preventing further schedule creation for this workorder_id
    if(get_workorder_details($db, $VAR['workorder_id'], 'is_closed')) {        
        $smarty->assign('warning_msg', _gettext("Can not set a schedule for closed work orders - Work Order ID").' '.$VAR['workorder_id']);
        unset($VAR['workorder_id']);
    }
    
}

// if no selected_employee, use the logged in user
if(!$VAR['employee_id']) {$VAR['employee_id'] = $user->login_user_id;}

// Assign the variables
$smarty->assign('start_year',               $VAR['start_year']                                                                                                 );
$smarty->assign('start_month',              $VAR['start_month']                                                                                                );
$smarty->assign('start_day',                $VAR['start_day']                                                                                                  );
$smarty->assign('selected_date',            timestamp_to_calendar_format(convert_year_month_day_to_timestamp($VAR['start_year'], $VAR['start_month'], $VAR['start_day']))    );
$smarty->assign('employees',                get_active_users($db, 'employees')                                                                          );  
$smarty->assign('current_schedule_date',    convert_year_month_day_to_timestamp($VAR['start_year'], $VAR['start_month'], $VAR['start_day'])                                  );
$smarty->assign('calendar_matrix',          build_calendar_matrix($db, $VAR['start_year'], $VAR['start_month'], $VAR['start_day'], $VAR['employee_id'], $VAR['workorder_id']));
$smarty->assign('selected_employee',        $VAR['employee_id']                                                                                              );

// Build the page
$BuildPage .= $smarty->fetch('schedule/day.tpl');