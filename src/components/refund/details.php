<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'payment.php');

// Check if we have a refund_id
if(!isset(\QFactory::$VAR['refund_id']) || !\QFactory::$VAR['refund_id']) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
} 

// Payment Details
$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                             ); 
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, 'refund', null, null, null, null, null, \QFactory::$VAR['refund_id']));

// Build the page
$refund_details = get_refund_details(\QFactory::$VAR['refund_id']);
$smarty->assign('refund_statuses', get_refund_statuses()  );
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('refund_details', $refund_details);
$smarty->assign('vat_tax_codes', get_vat_tax_codes() );
$smarty->assign('client_display_name', get_client_details($refund_details['client_id'], 'display_name'));

\QFactory::$BuildPage .= $smarty->fetch('refund/details.tpl');