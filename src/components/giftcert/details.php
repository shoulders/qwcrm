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
require(INCLUDES_DIR.'components/user.php');

// Check if we have an giftcert_id
if($VAR['giftcert_id'] == '') {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
    exit;
}

// Build the page
$smarty->assign('customer_details',         get_customer_details($db, get_giftcert_details($db, $VAR['giftcert_id'], 'customer_id'))               );
$smarty->assign('employee_display_name',    get_user_details($db, get_giftcert_details($db, $VAR['giftcert_id'], 'employee_id'), 'display_name')   );
$smarty->assign('giftcert_details',         get_giftcert_details($db, $VAR['giftcert_id'])                                                         );
$BuildPage .= $smarty->fetch('giftcert/details.tpl');