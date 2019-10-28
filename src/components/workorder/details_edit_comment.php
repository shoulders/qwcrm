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

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

// Check if we can edit the workorder comment
if(get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'is_closed')) {
    systemMessagesWrite('danger', _gettext("Cannot edit the comment of a closed Work Order."));
    force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
}

// If updated comment are submitted
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // update the workorder comment in the database
    update_workorder_comment(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['comment']);
    
    // load the workorder details page
    systemMessagesWrite('success', _gettext("Comment has been updated."));
    force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
    
}

// Build the page
$smarty->assign('comment', get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'comment'));