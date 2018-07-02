<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have a workorder_note_id
if(!isset($VAR['workorder_id']) || !$VAR['workorder_id']) {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Work Order Note ID supplied."));
}

// If record submitted for updating
if(isset($VAR['submit'])) {
    
    // update the workorder note
    update_workorder_note($VAR['workorder_note_id'], $VAR['note']);
    
    // load the workorder details page
    force_page('workorder', 'details&workorder_id='.$VAR['workorder_id'], 'information_msg='._gettext("The note has been updated."));
    
}   
    
// Build the page
$smarty->assign('workorder_note_details', get_workorder_note($VAR['workorder_note_id']));
$BuildPage .= $smarty->fetch('workorder/note_edit.tpl');
