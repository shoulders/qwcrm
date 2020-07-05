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

// Load the Type and Method classes (files only, no store)
\CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/types/'); 
//\CMSApplication::classFilesLoad(COMPONENTS_DIR.'payment/methods/');

// Set Action Type
Payment::$action = 'cancel';
        
// Set Payment details
Payment::$payment_details = $this->app->components->payment->getRecord(\CMSApplication::$VAR['payment_id']);

// Set Payment into [qpayment]
$this->app->components->payment->buildQpaymentArray();

// Set the payment type class (Capitlaise the first letter, Workaround: removes underscores, these might go when i go full PSR-1)
$typeClassName = 'PaymentType'.ucfirst(str_replace('_', '', \CMSApplication::$VAR['qpayment']['type']));
$paymentType = new $typeClassName;

// Run the type specific cancel routines
$paymentType->cancelRecord();