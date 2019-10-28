<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'invoice.php');
require(CINCLUDES_DIR.'refund.php');
require(CINCLUDES_DIR.'report.php');
require(CINCLUDES_DIR.'voucher.php');
require(CINCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('refund', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a refund_id
if(!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id']) {
    systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    force_page('refund', 'search');
} 

// Delete the refund function call
delete_refund(\CMSApplication::$VAR['refund_id']);

// Load the refund search page
force_page('refund', 'search', 'msg_success='._gettext("Refund deleted successfully."));
