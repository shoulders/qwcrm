<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a workorder_note_id
if($VAR['workorder_note_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Work Order Note ID supplied."));
    exit;
}

// If record submitted for updating
if(isset($VAR['submit'])) {
    
    // update the workorder note
    update_workorder_note($db, $VAR['workorder_note_id'], date_to_timestamp($VAR['date']), $VAR['note']);
    
    // load the workorder details page
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'information_msg='.gettext("The note has been updated."));
    exit;
    
}   
    
// Build the page
$smarty->assign('workorder_note_details', get_workorder_note($db, $VAR['workorder_note_id']));
$BuildPage .= $smarty->fetch('workorder/note_edit.tpl');
