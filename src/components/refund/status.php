<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'refund.php');

// Check if we have a refund_id
if(!isset($VAR['refund_id']) || !$VAR['refund_id']) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Voucher ID supplied."));
}

// Update Voucher Status
if(isset($VAR['change_status'])){
    update_refund_status($VAR['refund_id'], $VAR['assign_status']);    
    force_page('refund', 'status&refund_id='.$VAR['refund_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     false      );
$smarty->assign('refund_status',              get_refund_details($VAR['refund_id'], 'status')             );
$smarty->assign('refund_statuses',            get_refund_statuses() );
$smarty->assign('allowed_to_cancel',            false      );
$smarty->assign('allowed_to_delete',            check_refund_can_be_deleted($VAR['refund_id'])              );
$smarty->assign('refund_selectable_statuses',     get_refund_statuses(true) );

$BuildPage .= $smarty->fetch('refund/status.tpl');