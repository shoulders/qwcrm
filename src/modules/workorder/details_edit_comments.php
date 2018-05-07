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

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
    exit;
}

// Check if we can edit the workorder comments
if(get_workorder_details($db, $workorder_id, 'is_closed')) {
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'warning_msg='._gettext("Cannot edit the comments of a closed Work Order."));
    exit;
}

// If updated comments are submitted
if(isset($VAR['submit'])) {
    
    // update the workorder comments in the database
    update_workorder_comments($db, $workorder_id, $VAR['comments']);
    
    // load the workorder details page
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'&information_msg='._gettext("Comments have been updated."));
    exit;
    
}

// Build the page
$smarty->assign('comments', get_workorder_details($db, $workorder_id, 'comments'));
$BuildPage .= $smarty->fetch('workorder/details_edit_comments.tpl');