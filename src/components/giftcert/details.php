<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'user.php');

// Check if we have an giftcert_id
if(!isset($VAR['giftcert_id']) || !$VAR['giftcert_id']) {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
}

// Build the page
$smarty->assign('client_details',         get_client_details(get_giftcert_details($VAR['giftcert_id'], 'client_id'))               );
$smarty->assign('employee_display_name',    get_user_details(get_giftcert_details($VAR['giftcert_id'], 'employee_id'), 'display_name')   );
$smarty->assign('giftcert_statuses',        get_giftcert_statuses()                                                                      );
$smarty->assign('giftcert_details',         get_giftcert_details($VAR['giftcert_id'])                                                    );
$BuildPage .= $smarty->fetch('giftcert/details.tpl');