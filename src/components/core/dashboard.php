<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Decide which dashboard to show (employee or client)
if($user->login_is_employee) {

    // Prevent undefined variable errors
    \CMSApplication::$VAR['page_no'] = isset(\CMSApplication::$VAR['page_no']) ? \CMSApplication::$VAR['page_no'] : null;

    // Employee Workorders
    $smarty->assign('employee_workorders_assigned',          display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'assigned', $user->login_user_id)          );
    $smarty->assign('employee_workorders_waiting_for_parts', display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'waiting_for_parts', $user->login_user_id) );
    $smarty->assign('employee_workorders_scheduled',         display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'scheduled', $user->login_user_id)         );
    $smarty->assign('employee_workorders_with_client',       display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'with_client', $user->login_user_id)       );
    $smarty->assign('employee_workorders_on_hold',           display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'on_hold', $user->login_user_id)           );
    $smarty->assign('employee_workorders_management',        display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'management', $user->login_user_id)        );

    // Misc
    $smarty->assign('welcome_msg', display_welcome_msg());
    $smarty->assign('employee_workorder_stats', get_workorders_stats('current', null, null, $user->login_user_id));
    $smarty->assign('workorder_statuses', get_workorder_statuses());

    // Assign the correct version of this page
    $smarty->assign('page_version', 'employee');

} else {
    
    // Assign the correct version of this page
    $smarty->assign('page_version', 'client');
    
}