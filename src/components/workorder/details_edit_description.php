<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/workorder.php');

// Check if we have a workorder_id
if($VAR['workorder_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Check if we can edit the workorder description
if(get_workorder_details($VAR['workorder_id'], 'is_closed')) {
    force_page('workorder', 'details&workorder_id='.$VAR['workorder_id'], 'warning_msg='._gettext("Cannot edit the description of a closed Work Order."));
}

// If updated scope and description are submitted
if(isset($VAR['submit'])) {
    
    // update the scope and description in the database
    update_workorder_scope_and_description($VAR['workorder_id'], $VAR['scope'], $VAR['description']);
    
    // load the workorder details page
    force_page('workorder', 'details', 'workorder_id='.$VAR['workorder_id'].'&information_msg='._gettext("Description has been updated."));

}

// Build the page 
$smarty->assign('scope',          get_workorder_details($VAR['workorder_id'], 'scope')        );
$smarty->assign('description',    get_workorder_details($VAR['workorder_id'], 'description')  );    
$BuildPage .= $smarty->fetch('workorder/details_edit_description.tpl');
