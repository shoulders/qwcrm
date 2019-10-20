<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'workorder.php');
require(INCLUDES_DIR.'user.php');

// Prevent undefined variable errors
\QFactory::$VAR['assign_to_employee'] = isset(\QFactory::$VAR['assign_to_employee']) ? \QFactory::$VAR['assign_to_employee'] : null;

// Check if we have a client_id
if(!isset(\QFactory::$VAR['client_id']) || !\QFactory::$VAR['client_id']) {
    force_page('client', 'search', 'warning_msg='._gettext("No Client ID supplied."));
}

// If a workorder is submitted
if(isset(\QFactory::$VAR['submit'])){
    
    // insert the submitted workorder and return it's id
    \QFactory::$VAR['workorder_id'] = insert_workorder(\QFactory::$VAR['client_id'], \QFactory::$VAR['scope'], \QFactory::$VAR['description'], \QFactory::$VAR['comment']);

    // If workorder is to be assigned to an employee
    if(\QFactory::$VAR['assign_to_employee'] === '1') {       
        assign_workorder_to_employee(\QFactory::$VAR['workorder_id'], $user->login_user_id);  
    }
    
    // load the workorder details page
    force_page('workorder', 'details&workorder_id='.\QFactory::$VAR['workorder_id'], 'information_msg='._gettext("New Work Order created."));
        
}

// Build the page
$smarty->assign('client_display_name', get_client_details(\QFactory::$VAR['client_id'], 'display_name'));
\QFactory::$BuildPage .= $smarty->fetch('workorder/new.tpl');