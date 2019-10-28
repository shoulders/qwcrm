<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'workorder.php');
require(CINCLUDES_DIR.'user.php');

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

// Get the Id of the employee assigned to the workorder
$assigned_employee_id = get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'employee_id');

// Update Work Order Status
if(isset(\CMSApplication::$VAR['change_status'])){
    update_workorder_status(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['assign_status']);    
    force_page('workorder', 'status&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// Assign Work Order to another employee
if(isset(\CMSApplication::$VAR['change_employee'])) {
    assign_workorder_to_employee(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['target_employee_id']);    
    force_page('workorder', 'status&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     check_workorder_status_can_be_changed(\CMSApplication::$VAR['workorder_id']) );
$smarty->assign('allowed_to_change_employee',   check_workorder_allowed_to_change_employee(\CMSApplication::$VAR['workorder_id']));
$smarty->assign('allowed_to_delete',            check_workorder_status_allows_for_deletion(\CMSApplication::$VAR['workorder_id'])  );
$smarty->assign('active_employees',             get_active_users('employees')                                     );
$smarty->assign('workorder_statuses',           get_workorder_statuses(true)                                      );
$smarty->assign('workorder_status',             get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'status')             );
$smarty->assign('workorder_status_display_name',get_workorder_status_display_name(get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'status')));
$smarty->assign('assigned_employee_id',         $assigned_employee_id                                             );
$smarty->assign('assigned_employee_details',    get_user_details($assigned_employee_id)                           );