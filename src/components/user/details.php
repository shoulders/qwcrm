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

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;

// Check if we have an user_id
if(!isset($VAR['user_id']) || !$VAR['user_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
}

// Build the page

$smarty->assign('user_details',             get_user_details($VAR['user_id'])                                                                         );
$smarty->assign('customer_display_name',    get_customer_details(get_user_details($VAR['user_id'], 'customer_id'), 'customer_display_name')      );
$smarty->assign('usergroups',               get_usergroups()                                                                                     );
$smarty->assign('user_workorders',          display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'open', $VAR['user_id']));
$BuildPage .= $smarty->fetch('user/details.tpl');