<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
\QFactory::$VAR['page_no'] = isset(\QFactory::$VAR['page_no']) ? \QFactory::$VAR['page_no'] : null;

// Check if we have an user_id
if(!isset(\QFactory::$VAR['user_id']) || !\QFactory::$VAR['user_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
}

// Build the page

$smarty->assign('user_details',             get_user_details(\QFactory::$VAR['user_id'])                                                                            );
$smarty->assign('client_display_name',      get_client_details(get_user_details(\QFactory::$VAR['user_id'], 'client_id'), 'client_display_name')                    );
$smarty->assign('usergroups',               get_usergroups()                                                                                             );
$smarty->assign('user_workorders',          display_workorders('workorder_id', 'DESC', false, '25', \QFactory::$VAR['page_no'], null, null, 'open', \QFactory::$VAR['user_id']));
$smarty->assign('user_locations',           get_user_locations());
\QFactory::$BuildPage .= $smarty->fetch('user/details.tpl');