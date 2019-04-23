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
if(!isset($VAR['refund_id']) || !$VAR['refund_id']) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
} 

// Build the page
$refund_details = get_refund_details($VAR['refund_id']);
$smarty->assign('refund_statuses', get_refund_statuses()  );
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('refund_details', $refund_details);
$smarty->assign('client_display_name', get_client_details($refund_details['client_id'], 'display_name'));

$BuildPage .= $smarty->fetch('refund/details.tpl');