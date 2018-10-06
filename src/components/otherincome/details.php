<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'payment.php');

// Check if we have a otherincome_id
if(!isset($VAR['otherincome_id']) || !$VAR['otherincome_id']) {
    force_page('otherincome', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
} 

// Build the page
$smarty->assign('otherincome_types', get_otherincome_types());
$smarty->assign('payment_methods', get_payment_purchase_methods());
$smarty->assign('otherincome_details', get_otherincome_details($VAR['otherincome_id']));
$BuildPage .= $smarty->fetch('otherincome/details.tpl');