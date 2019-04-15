<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* Pre-Processing */

/* Processing */

// Build additional information column
$VAR['qpayment']['additional_info'] = build_additional_info_json($VAR['qpayment']['bank_transfer_reference']);    

// Insert the payment with the calculated information
insert_payment($VAR['qpayment']);

// Assign Success message
$smarty->assign('information_msg', _gettext("Bank Transfer payment added successfully"));

/* Post-Processing */