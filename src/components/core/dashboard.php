<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Decide which dashboard to show (employee or client)
if($this->app->user->login_is_employee) {

    // Prevent undefined variable errors
    \CMSApplication::$VAR['page_no'] = isset(\CMSApplication::$VAR['page_no']) ? \CMSApplication::$VAR['page_no'] : null;

    // Employee Workorders
    $this->app->smarty->assign('employee_workorders_assigned',          $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'assigned', $this->app->user->login_user_id)          );
    $this->app->smarty->assign('employee_workorders_waiting_for_parts', $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'waiting_for_parts', $this->app->user->login_user_id) );
    $this->app->smarty->assign('employee_workorders_scheduled',         $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'scheduled', $this->app->user->login_user_id)         );
    $this->app->smarty->assign('employee_workorders_with_client',       $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'with_client', $this->app->user->login_user_id)       );
    $this->app->smarty->assign('employee_workorders_on_hold',           $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'on_hold', $this->app->user->login_user_id)           );
    $this->app->smarty->assign('employee_workorders_management',        $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'management', $this->app->user->login_user_id)        );

    // Misc
    $this->app->smarty->assign('welcome_msg', $this->app->components->core->getWelcomeMsg());
    $this->app->smarty->assign('employee_workorder_stats', $this->app->components->report->getWorkordersStats('current', null, null, $this->app->user->login_user_id));
    $this->app->smarty->assign('workorder_statuses', $this->app->components->workorder->getStatuses());

    // Assign the correct version of this page
    $this->app->smarty->assign('page_version', 'employee');

} else {
    
    // Assign the correct version of this page
    $this->app->smarty->assign('page_version', 'client');
    
}