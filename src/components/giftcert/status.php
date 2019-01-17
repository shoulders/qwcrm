<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have a giftcert_id
if(!isset($VAR['giftcert_id']) || !$VAR['giftcert_id']) {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
}

// Update Giftcert Status
if(isset($VAR['change_status'])){
    update_giftcert_status($VAR['giftcert_id'], $VAR['assign_status']);    
    force_page('giftcert', 'status&giftcert_id='.$VAR['giftcert_id']);
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('giftcert', 'delete', 'giftcert_id='.$VAR['giftcert_id']);
}

/* Remove dormant giftcert statuses (for now) */

// Get statuses
$statuses = get_giftcert_statuses();

// Unset unwanted status
//unset($statuses[0]);    // 'unused'  
unset($statuses[1]);      // 'redeemed'  
//unset($statuses[2]);    //  'suspended' 
unset($statuses[3]);    // 'expired'   
unset($statuses[4]);      // 'refunded'
unset($statuses[5]);      // 'cancelled'
unset($statuses[6]);      // 'deleted'

//  Remaps the array ID's - Because of how smarty works you need to maintain the array internal number system
foreach($statuses as $status) {
    $edited_statuses[] = $status;
}        

// This is not needed for the template
unset ($statuses);

/* -- */

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     check_giftcert_status_can_be_changed($VAR['giftcert_id'])       );
$smarty->assign('giftcert_status',              get_giftcert_details($VAR['giftcert_id'], 'status')             );
$smarty->assign('giftcert_statuses',            get_giftcert_statuses() );
$smarty->assign('allowed_to_cancel',            check_giftcert_can_be_cancelled($VAR['giftcert_id'])         );
$smarty->assign('allowed_to_refund',            check_giftcert_can_be_refunded($VAR['giftcert_id'])              );
$smarty->assign('giftcert_edited_statuses',     $edited_statuses );

$BuildPage .= $smarty->fetch('giftcert/status.tpl');