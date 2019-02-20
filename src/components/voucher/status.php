<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have a voucher_id
if(!isset($VAR['voucher_id']) || !$VAR['voucher_id']) {
    force_page('voucher', 'search', 'warning_msg='._gettext("No Voucher ID supplied."));
}

// Update Voucher Status
if(isset($VAR['change_status'])){
    update_voucher_status($VAR['voucher_id'], $VAR['assign_status']);    
    force_page('voucher', 'status&voucher_id='.$VAR['voucher_id']);
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('voucher', 'delete', 'voucher_id='.$VAR['voucher_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     check_voucher_status_can_be_changed($VAR['voucher_id'])       );
$smarty->assign('voucher_status',              get_voucher_details($VAR['voucher_id'], 'status')             );
$smarty->assign('voucher_statuses',            get_voucher_statuses() );
$smarty->assign('allowed_to_delete',            check_voucher_can_be_deleted($VAR['voucher_id'])              );
$smarty->assign('voucher_selectable_statuses',     get_voucher_statuses(true) );

$BuildPage .= $smarty->fetch('voucher/status.tpl');