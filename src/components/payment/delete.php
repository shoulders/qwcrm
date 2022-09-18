<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('payment', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    $this->app->system->page->forcePage('payment', 'search');
}   

// Check if payment can be deleted
if(!$this->app->components->payment->checkRecordAllowsDelete(\CMSApplication::$VAR['payment_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot delete this payment because its status does not allow it."));
    $this->app->system->page->forcePage('payment', 'details&payment_id='.\CMSApplication::$VAR['payment_id']);
}

// Build the Payment Environment
$this->app->components->payment->buildPaymentEnvironment('delete');

// Process the payment
$this->app->components->payment->processPayment();
