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

// Prevent undefined variable errors
\CMSApplication::$VAR['assign_to_employee'] = isset(\CMSApplication::$VAR['assign_to_employee']) ? \CMSApplication::$VAR['assign_to_employee'] : null;

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    force_page('client', 'search');
}

// If a workorder is submitted
if(isset(\CMSApplication::$VAR['submit'])){
    
    // insert the submitted workorder and return it's id
    \CMSApplication::$VAR['workorder_id'] = insert_workorder(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['scope'], \CMSApplication::$VAR['description'], \CMSApplication::$VAR['comment']);

    // If workorder is to be assigned to an employee
    if(\CMSApplication::$VAR['assign_to_employee'] === '1') {       
        assign_workorder_to_employee(\CMSApplication::$VAR['workorder_id'], $user->login_user_id);  
    }
    
    // load the workorder details page
    systemMessagesWrite('success', _gettext("New Work Order created."));
    force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
        
}

// Build the page
$smarty->assign('client_display_name', get_client_details(\CMSApplication::$VAR['client_id'], 'display_name'));