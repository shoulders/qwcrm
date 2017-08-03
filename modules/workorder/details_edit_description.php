<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// If updated scope and description are submitted
if(isset($VAR['submit'])) {
    
    // update teh scope and description in the database
    update_workorder_scope_and_description($db, $workorder_id, $VAR['workorder_scope'], $VAR['workorder_description']);
    
    // load the workorder details page
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.gettext("Description has been updated."));
    exit;

}

// Build the page 
$smarty->assign('scope',          get_workorder_details($db, $workorder_id, 'scope')       );
$smarty->assign('description',    get_workorder_details($db, $workorder_id, 'resolution')  );    
$BuildPage .= $smarty->fetch('workorder/details_edit_description.tpl');
