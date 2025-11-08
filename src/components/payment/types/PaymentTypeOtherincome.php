<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class PaymentTypeOtherincome extends PaymentType
{
    public $otherincome_details = array();

    public function __construct()
    {
        parent::__construct();

        // Set class variables
        Payment::$payment_details['type'] = 'other_income';
        $this->otherincome_details = $this->app->components->otherincome->getRecord($this->VAR['qpayment']['otherincome_id']); //only needed for smarty?

        // Set Payment direction
        $this->VAR['qpayment']['direction'] = 'credit';

        // Disable Unwanted Payment Methods
        Payment::$disabledMethods[] = 'creditnote';
        Payment::$disabledMethods[] = 'voucher';

        // For logging and insertRecord()
        Payment::$payment_details['client_id'] = \CMSApplication::$VAR['qpayment']['client_id'] = null;
        Payment::$payment_details['invoice_id'] = \CMSApplication::$VAR['qpayment']['invoice_id'] = null;

        // Set intial record balance
        Payment::$record_balance = (float) $this->otherincome_details['balance'];

        // Assign Payment Type specific template variables
        $this->app->smarty->assign('payment_active_methods', $this->app->components->payment->getMethods('receive', true, Payment::$disabledMethods));
        $this->app->smarty->assign('otherincome_details', $this->otherincome_details);
        $this->app->smarty->assign('otherincome_statuses', $this->app->components->otherincome->getStatuses());
    }

    // Pre-Processing
    public function preProcess()
    {
        parent::preProcess();

        // New
        if(Payment::$action === 'new')
        {
            // Do nothing
        }

        // Edit
        if(Payment::$action === 'edit')
        {
           // Do nothing
        }

        // Cancel
        if(Payment::$action === 'cancel')
        {
            // Do nothing
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Do nothing
        }

        return;
    }

    // Processing
    public function process()
    {
        parent::process();

        // Recalculate record totals
        $this->app->components->otherincome->recalculateTotals($this->VAR['qpayment']['otherincome_id']);

        // Refresh the record data
        $this->otherincome_details = $this->app->components->otherincome->getRecord($this->VAR['qpayment']['otherincome_id']);
        Payment::$record_balance = (float) $this->otherincome_details['balance'];

        $this->app->smarty->assign('otherincome_details', $this->otherincome_details);

        // New
        if(Payment::$action === 'new')
        {
            // Do nothing
        }

        // Edit
        if(Payment::$action === 'edit')
        {
            // Do nothing
        }

        // Cancel
        if(Payment::$action === 'cancel')
        {
            // Do nothing
        }

        // Delete
        if(Payment::$action === 'delete')
        {
            // Do nothing
        }

        return;
    }

    // Post-Processing
    public function postProcess()
    {
        parent::postProcess();

        // Different actions depending on success
        if(Payment::$payment_successful)
        {
            // If the balance has been cleared
            if(Payment::$record_balance == 0)
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("The balance has been cleared."));
                $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.$this->VAR['otherincome_id']);
            }

            // New
            if(Payment::$action === 'new')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment added successfully and Other Income").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
                // No forcepage, this will reload the new payment page
            }

            // Edit
            if(Payment::$action === 'edit')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment updated successfully and Other Income").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('payment', 'details&payment_id='.Payment::$payment_details['payment_id']);
            }

            // Cancel
            if(Payment::$action === 'cancel')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment cancelled successfully and Other Income").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
            }

            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->variables->systemMessagesWrite('success', _gettext("Payment deleted successfully and Other Income").' '.$this->VAR['qpayment']['otherincome_id'].' '._gettext("has been updated to reflect this change."));
                $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
            }

        }

        else
        {
            // The same page will be reloaded unless specified here, error messages is handled by methof

            // New
            if(Payment::$action === 'new')
            {
                // Do nothing
            }

            // Edit
            if(Payment::$action === 'edit')
            {
                // Do nothing
            }

            // Cancel
            if(Payment::$action === 'cancel')            {

                $this->app->system->page->forcePage('otherincome', 'status&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
            }

            // Delete
            if(Payment::$action === 'delete')
            {
                $this->app->system->page->forcePage('otherincome', 'status&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
            }
        }

        return;
    }

    // General payment checks
    private function checkPaymentAllowed()
    {
        $state_flag = parent::checkPaymentAllowed();

        // Is on a different tax system
        if($this->otherincome_details['tax_system'] != QW_TAX_SYSTEM) {
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The otherincome cannot receive a payment because it is on a different tax system."));
            $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.$this->VAR['qpayment']['otherincome_id']);
            $state_flag = false;
        }

        return $state_flag;
    }

    // Build Buttons
    public function buildButtons()
    {
        parent::buildButtons();

        // Submit
        if($this->otherincome_details['balance'] > 0) {
            Payment::$buttons['submit']['allowed'] = true;
            Payment::$buttons['submit']['url'] = null;
            Payment::$buttons['submit']['title'] = _gettext("Submit Payment");
        }

        // Cancel
        if(!$this->otherincome_details['balance'] == 0) {
            if($this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'new') || $this->app->system->security->checkPageAccessedViaQwcrm('otherincome', 'details')) {
                Payment::$buttons['cancel']['allowed'] = true;
                Payment::$buttons['cancel']['url'] = 'index.php?component=otherincome&page_tpl=details&otherincome_id='.$this->VAR['qpayment']['otherincome_id'];
                Payment::$buttons['cancel']['title'] = _gettext("Cancel");
            }
        }

        // Return To Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['returnToRecord']['allowed'] = true;
            Payment::$buttons['returnToRecord']['url'] = 'index.php?component=otherincome&page_tpl=details&otherincome_id='.$this->VAR['qpayment']['otherincome_id'];
            Payment::$buttons['returnToRecord']['title'] = _gettext("Return to Record");
        }

        // Add New Record
        if($this->app->system->security->checkPageAccessedViaQwcrm('payment', 'new')) {
            Payment::$buttons['addNewRecord']['allowed'] = true;
            Payment::$buttons['addNewRecord']['url'] = 'index.php?component=otherincome&page_tpl=new';
            Payment::$buttons['addNewRecord']['title'] = _gettext("Add New Other Income Record");
        }

    }
}
