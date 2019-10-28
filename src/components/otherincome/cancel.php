<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('otherincome', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    systemMessagesWrite('danger', _gettext("No Otherincome ID supplied."));
    force_page('otherincome', 'search');
} 

// Cancel the otherincome function call
cancel_otherincome(\CMSApplication::$VAR['otherincome_id']);

// Load the otherincome search page
force_page('otherincome', 'search', 'msg_success='._gettext("Otherincome cancelled successfully."));
