<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
$VAR['workorder_id'] = isset($VAR['workorder_id']) ? $VAR['workorder_id'] : null;
$VAR['employee_id']  = isset($VAR['employee_id']) ? $VAR['employee_id'] : QFactory::getUser()->login_user_id;

// If no schedule year/month/day set, use today's date
$VAR['start_year']  = isset($VAR['start_year']) ? $VAR['start_year'] : date('Y');
$VAR['start_month'] = isset($VAR['start_month']) ? $VAR['start_month'] : date('m');
$VAR['start_day']   = isset($VAR['start_day']) ? $VAR['start_day'] : date('d');

// Check the workorder status - We don't want to schedule/reschedule a workorder if it's closed
if($VAR['workorder_id']) { 
    
    // If the workorder is closed, remove the workorder_id preventing further schedule creation for this workorder_id
    if(get_workorder_details($VAR['workorder_id'], 'is_closed')) {        
        $smarty->assign('warning_msg', _gettext("Can not set a schedule for closed work orders - Work Order ID").' '.$VAR['workorder_id']);
        unset($VAR['workorder_id']);
    }
    
}

// Build the page
$smarty->assign('start_year',               $VAR['start_year']                                                                                                 );
$smarty->assign('start_month',              $VAR['start_month']                                                                                                );
$smarty->assign('start_day',                $VAR['start_day']                                                                                                  );
$smarty->assign('selected_date',            timestamp_to_calendar_format(convert_year_month_day_to_timestamp($VAR['start_year'], $VAR['start_month'], $VAR['start_day']))    );
$smarty->assign('employees',                get_active_users('employees')                                                                                                    );  
$smarty->assign('current_schedule_date',    convert_year_month_day_to_timestamp($VAR['start_year'], $VAR['start_month'], $VAR['start_day'])                                  );
$smarty->assign('calendar_matrix',          build_calendar_matrix($VAR['start_year'], $VAR['start_month'], $VAR['start_day'], $VAR['employee_id'], $VAR['workorder_id'])     );
$smarty->assign('selected_employee',        $VAR['employee_id']                                                                                                              );
$BuildPage .= $smarty->fetch('schedule/day.tpl');