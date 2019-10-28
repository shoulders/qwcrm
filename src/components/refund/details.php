<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'company.php');
require(CINCLUDES_DIR.'refund.php');
require(CINCLUDES_DIR.'payment.php');

// Check if we have a refund_id
if(!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id']) {
    systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    force_page('refund', 'search');
} 

// Payment Details
$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                             ); 
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, 'refund', null, null, null, null, null, \CMSApplication::$VAR['refund_id']));

// Build the page
$refund_details = get_refund_details(\CMSApplication::$VAR['refund_id']);
$smarty->assign('refund_statuses', get_refund_statuses()  );
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('refund_details', $refund_details);
$smarty->assign('vat_tax_codes', get_vat_tax_codes() );
$smarty->assign('client_display_name', get_client_details($refund_details['client_id'], 'display_name'));