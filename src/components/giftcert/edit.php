<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/giftcert.php');
require(INCLUDES_DIR.'components/payment.php');

// Check if we have an giftcert_id
if(!isset($VAR['giftcert_id']) || !$VAR['giftcert_id']) {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
}

// Check if giftcert payment method is enabled
if(!check_payment_method_is_active('gift_certificate')) {
    force_page('index.php', null,'warning_msg='._gettext("Gift Certificate payment method is not enabled. Goto Payment Options and enable Gift Certificates there."));
}

// Check if giftcert redeemed - if so, it cannot be updated
if(check_giftcert_redeemed($VAR['giftcert_id'])) {
    force_page('giftcert', 'details&giftcert_id='.$VAR['giftcert_id'], 'warning_msg='._gettext("You cannot edit this Gift Certificate because it has been redeemed."));
}

// if information submitted
if(isset($VAR['submit'])) {
    
    // Create a new gift certificate
    update_giftcert($VAR['giftcert_id'], date_to_timestamp($VAR['date_expires']), $VAR['amount'], $VAR['status'], $VAR['note']);

    // Load the new Gift Certificate's Details page
    force_page('giftcert', 'details&giftcert_id='.$VAR['giftcert_id']);    

} else {
    
    // Build the page    
    $smarty->assign('customer_details', get_customer_details(get_giftcert_details($VAR['giftcert_id'], 'customer_id')));    
    $smarty->assign('giftcert_details', get_giftcert_details($VAR['giftcert_id']));
    $BuildPage .= $smarty->fetch('giftcert/edit.tpl');
}