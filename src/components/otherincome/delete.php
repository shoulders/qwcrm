<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'report.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('otherincome', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a otherincome_id
if(!isset(\QFactory::$VAR['otherincome_id']) || !\QFactory::$VAR['otherincome_id']) {
    systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
    force_page('otherincome', 'search');
} 

// Delete the otherincome function call
delete_otherincome(\QFactory::$VAR['otherincome_id']);

// Load the otherincome search page
force_page('otherincome', 'search', 'msg_success='._gettext("Otherincome deleted successfully."));
