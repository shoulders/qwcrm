<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['workorder_id'] = isset(\CMSApplication::$VAR['workorder_id']) ? \CMSApplication::$VAR['workorder_id'] : null;
\CMSApplication::$VAR['employee_id']  = isset(\CMSApplication::$VAR['employee_id']) ? \CMSApplication::$VAR['employee_id'] : \Factory::getUser()->login_user_id;

// If no schedule year/month/day set, use today's date
\CMSApplication::$VAR['start_year']  = isset(\CMSApplication::$VAR['start_year']) ? \CMSApplication::$VAR['start_year'] : date('Y');
\CMSApplication::$VAR['start_month'] = isset(\CMSApplication::$VAR['start_month']) ? \CMSApplication::$VAR['start_month'] : date('m');
\CMSApplication::$VAR['start_day']   = isset(\CMSApplication::$VAR['start_day']) ? \CMSApplication::$VAR['start_day'] : date('d');

// Check the workorder status - We don't want to schedule/reschedule a workorder if it's closed
if(\CMSApplication::$VAR['workorder_id']) { 
    
    // If the workorder is closed, remove the workorder_id preventing further schedule creation for this workorder_id
    if(get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'is_closed')) {        
        systemMessagesWrite('danger', _gettext("Can not set a schedule for closed work orders - Work Order ID").' '.\CMSApplication::$VAR['workorder_id']);
        unset(\CMSApplication::$VAR['workorder_id']);
    }
    
}

// Build the page
$smarty->assign('start_year',               \CMSApplication::$VAR['start_year']                                                                                                 );
$smarty->assign('start_month',              \CMSApplication::$VAR['start_month']                                                                                                );
$smarty->assign('start_day',                \CMSApplication::$VAR['start_day']                                                                                                  );
$smarty->assign('selected_date',            timestamp_to_calendar_format(convert_year_month_day_to_timestamp(\CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day']))    );
$smarty->assign('employees',                get_active_users('employees')                                                                                                    );  
$smarty->assign('current_schedule_date',    convert_year_month_day_to_timestamp(\CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day'])                                  );
$smarty->assign('calendar_matrix',          build_calendar_matrix(\CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day'], \CMSApplication::$VAR['employee_id'], \CMSApplication::$VAR['workorder_id'])     );
$smarty->assign('selected_employee',        \CMSApplication::$VAR['employee_id']                                                                                                              );