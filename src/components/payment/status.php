<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'payment.php');

// Check if we have a payment_id
if(!isset($VAR['payment_id']) || !$VAR['payment_id']) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
}

// Update Payment Status
if(isset($VAR['change_status'])){
    update_payment_status($VAR['payment_id'], $VAR['assign_status']);    
    force_page('payment', 'status&payment_id='.$VAR['payment_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',        false       );
$smarty->assign('payment_status',                  get_payment_details($VAR['payment_id'], 'status')             );
$smarty->assign('payment_statuses',                get_payment_statuses() );
$smarty->assign('allowed_to_cancel',               false     );
$smarty->assign('allowed_to_delete',               check_payment_can_be_deleted($VAR['payment_id'])              );
$smarty->assign('payment_selectable_statuses',     get_payment_statuses(true) );

$BuildPage .= $smarty->fetch('payment/status.tpl');