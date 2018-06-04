<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/user.php');
require(INCLUDES_DIR.'components/workorder.php');

// Check if we have an user_id
if($VAR['user_id'] == '') {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
}

// Build the page
$smarty->assign('user_workorders',          display_workorders($db, 'workorder_id', 'DESC', false, $VAR['page_no'], '25', null, null, 'open', $VAR['user_id'])    );
$smarty->assign('user_details',             get_user_details($db, $VAR['user_id'])                                                                         );
$smarty->assign('customer_display_name',    get_customer_details($db, get_user_details($db, $VAR['user_id'], 'customer_id'), 'customer_display_name')      );
$smarty->assign('usergroups',               get_usergroups($db)                                                                                     );
$BuildPage .= $smarty->fetch('user/details.tpl');