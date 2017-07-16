<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/user.php');

// Make sure there is a giftcert_id
if($giftcert_id == '') {
    force_page('core', 'error&error_msg=No Customer ID&menu=1&type=database');
    exit;
}

$smarty->assign('customer_details',         get_customer_details($db, get_giftcert_details($db, $giftcert_id, 'CUSTOMER_ID'))               );
$smarty->assign('employee_display_name',    get_user_details($db, get_giftcert_details($db, $giftcert_id, 'EMPLOYEE_ID'), 'display_name')   );
$smarty->assign('giftcert_details',         get_giftcert_details($db, $giftcert_id)                                                         );
$BuildPage .= $smarty->fetch('giftcert/details.tpl');