<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = isset(\CMSApplication::$VAR['page_no']) ? \CMSApplication::$VAR['page_no'] : null;

// Check if we have an user_id
if(!isset(\CMSApplication::$VAR['user_id']) || !\CMSApplication::$VAR['user_id']) {
    systemMessagesWrite('danger', _gettext("No User ID supplied."));
    force_page('user', 'search');
}

// Build the page

$smarty->assign('user_details',             get_user_details(\CMSApplication::$VAR['user_id'])                                                                            );
$smarty->assign('client_display_name',      get_client_details(get_user_details(\CMSApplication::$VAR['user_id'], 'client_id'), 'client_display_name')                    );
$smarty->assign('usergroups',               get_usergroups()                                                                                             );
$smarty->assign('user_workorders',          display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'open', \CMSApplication::$VAR['user_id']));
$smarty->assign('user_locations',           get_user_locations());