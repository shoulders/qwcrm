<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');
require(INCLUDES_DIR.'modules/payment.php');

// Check if we have a refund_id
if($refund_id == '') {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
    exit;
} 

// Build the page
$smarty->assign('refund_types', get_refund_types($db));
$smarty->assign('payment_methods', get_payment_manual_methods($db));
$smarty->assign('refund_details', get_refund_details($db, $refund_id));
$BuildPage .= $smarty->fetch('refund/details.tpl');