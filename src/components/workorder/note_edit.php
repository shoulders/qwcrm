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

// Check if we have a workorder_note_id
if(!isset(\QFactory::$VAR['workorder_note_id']) || !\QFactory::$VAR['workorder_note_id']) {
    systemMessagesWrite('danger', _gettext("No Work Order Note ID supplied."));
    force_page('workorder', 'search');
}

// Get teh work order note details
$workorder_note_details = get_workorder_note_details(\QFactory::$VAR['workorder_note_id']);

// If record submitted for updating
if(isset(\QFactory::$VAR['submit'])) {    
    
    // update the workorder note
    update_workorder_note(\QFactory::$VAR['workorder_note_id'], \QFactory::$VAR['note']);
    
    // load the workorder details page
    systemMessagesWrite('success', _gettext("The note has been updated."));
    force_page('workorder', 'details&workorder_id='.$workorder_note_details['workorder_id']);
    
}   
    
// Build the page
$smarty->assign('workorder_note_details', $workorder_note_details);
