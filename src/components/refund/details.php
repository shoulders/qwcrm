<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'payment.php');

// Check if we have a refund_id
if(!isset($VAR['refund_id']) || !$VAR['refund_id']) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
} 

// Build the page
$smarty->assign('refund_statuses', get_refund_statuses()  );
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('vat_tax_codes', get_vat_tax_codes() );
$smarty->assign('payment_methods', get_payment_methods('send'));
$smarty->assign('refund_details', get_refund_details($VAR['refund_id']));
$BuildPage .= $smarty->fetch('refund/details.tpl');