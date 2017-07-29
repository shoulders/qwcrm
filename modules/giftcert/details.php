<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/user.php');

// Check if we have an giftcert_id
if($giftcert_id == '') {
    force_page('giftcert', 'search', 'warning_msg='.gettext("No Gift Certificate ID supplied."));
    exit;
}

// Build the page
$smarty->assign('customer_details',         get_customer_details($db, get_giftcert_details($db, $giftcert_id, 'customer_id'))               );
$smarty->assign('employee_display_name',    get_user_details($db, get_giftcert_details($db, $giftcert_id, 'employee_id'), 'display_name')   );
$smarty->assign('giftcert_details',         get_giftcert_details($db, $giftcert_id)                                                         );
$BuildPage .= $smarty->fetch('giftcert/details.tpl');