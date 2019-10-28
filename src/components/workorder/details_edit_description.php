<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

// Check if we can edit the workorder description
if(get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'is_closed')) {
    systemMessagesWrite('danger', _gettext("Cannot edit the description of a closed Work Order."));
    force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// If updated scope and description are submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // update the scope and description in the database
    update_workorder_scope_and_description(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['scope'], \CMSApplication::$VAR['description']);
    
    // load the workorder details page
    systemMessagesWrite('success', _gettext("Description has been updated."));
    force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);

}

// Build the page 
$smarty->assign('scope',          get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'scope')        );
$smarty->assign('description',    get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'description')  );  
