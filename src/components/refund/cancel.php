<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->check_page_accessed_via_qwcrm('refund', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a refund_id
if(!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    $this->app->system->page->force_page('refund', 'search');
} 

// Cancel the refund function call
$this->app->components->refund->cancelRecord(\CMSApplication::$VAR['refund_id']);

// Load the refund search page
$this->app->system->page->force_page('refund', 'search', 'msg_success='._gettext("Refund cancelled successfully."));
