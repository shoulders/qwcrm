<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors (from checkboxes)
$checkboxes = array('bank_transfer', 'card', 'cash', 'cheque', 'direct_debit', 'voucher', 'other', 'paypal');
foreach($checkboxes as $checkbox) {     
    \CMSApplication::$VAR['qform'][$checkbox]['send']     = \CMSApplication::$VAR['qform'][$checkbox]['send']    ?? '0';
    \CMSApplication::$VAR['qform'][$checkbox]['receive']  = \CMSApplication::$VAR['qform'][$checkbox]['receive'] ?? '0';
    \CMSApplication::$VAR['qform'][$checkbox]['enabled']  = \CMSApplication::$VAR['qform'][$checkbox]['enabled'] ?? '0';     
}

// If changes submited
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Update enabled payment methods (checkboxes)
    $this->app->components->payment->updateMethodsStatuses(\CMSApplication::$VAR['qform']['payment_methods']);
    
    // Update Payment details
    $this->app->components->payment->updateOptions(\CMSApplication::$VAR['qform']);

    // Assign success message    
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment Options Updated.") );
    
    // Log activity 
    $this->app->system->general->writeRecordToActivityLog(_gettext("Payment Options Updated."));
    
}

// Build the page
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods() );
$this->app->smarty->assign('payment_options',          $this->app->components->payment->getOptions() );
