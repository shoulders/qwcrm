<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    $this->app->system->general->force_page('payment', 'search');
}   

// Check if payment can be edited
if(!$this->app->components->payment->check_payment_can_be_edited(\CMSApplication::$VAR['payment_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this payment because its status does not allow it."));
    $this->app->system->general->force_page('payment', 'details&payment_id='.\CMSApplication::$VAR['payment_id']);
}
    
// This is a dirty hack because QWcrm is not fully OOP yet
class UpdatePayment {
    
    private $VAR = null;
    private $type = null;    
    public static $payment_details = array();
    public static $payment_valid = true;
    public static $record_balance = null;
    
    function __construct(&$VAR) {
        
        // Set class variables
        $this->VAR = &$VAR;
        
        // Set Payment details
        self::$payment_details = $this->app->components->payment->get_payment_details($VAR['payment_id']);
        
        // Set the various payment type IDs
        $this->VAR['qpayment']['payment_id'] = self::$payment_details['payment_id'];
        $this->VAR['qpayment']['type'] = self::$payment_details['type'];
        $this->VAR['qpayment']['invoice_id'] = self::$payment_details['invoice_id'];
        $this->VAR['qpayment']['voucher_id'] = self::$payment_details['voucher_id'];
        $this->VAR['qpayment']['refund_id'] = self::$payment_details['refund_id'];
        $this->VAR['qpayment']['expense_id'] = self::$payment_details['expense_id'];
        $this->VAR['qpayment']['otherincome_id'] = self::$payment_details['otherincome_id'];
        
        // Set the payment type class
        $this->set_payment_type();
        
        // Prep/validate the data        
        $this->type->pre_process();
            
        // If the form is submitted
        if(isset($this->VAR['submit'])) {            
            
            // Process the update if valid
            if(self::$payment_valid) {  
                $this->type->update();
                self::$payment_details = $this->app->components->payment->get_payment_details($VAR['payment_id']);
            }
            
        }
               
    }
        
    function set_payment_type() {
        
        // Load the routines specific for the specific payment type
        switch($this->VAR['qpayment']['type']) {

            case 'invoice':
            require(COMPONENTS_DIR.'payment/types/invoice.php');
            break;

            case 'refund':
            require(COMPONENTS_DIR.'payment/types/refund.php');
            break;

            case 'expense':
            require(COMPONENTS_DIR.'payment/types/expense.php');
            break;

            case 'otherincome':
            require(COMPONENTS_DIR.'payment/types/otherincome.php');
            break;

            default:
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Invalid Payment Type."));
            $this->app->system->general->force_page('payment', 'search');
            break;

        }
        
        // Load and set the relevant class
        $this->type = new PType($this->VAR);
    
    }
    
}

// Instanciate Update Payment Class
$payment = new UpdatePayment(\CMSApplication::$VAR);

// Build the page
$this->app->smarty->assign('client_display_name',      $this->app->components->client->get_client_details(UpdatePayment::$payment_details['client_id'], 'display_name'));
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->get_user_details(UpdatePayment::$payment_details['employee_id'], 'display_name'));
$this->app->smarty->assign('payment_types',            $this->app->components->payment->get_payment_types()    );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->get_payment_methods('receive', 'enabled'));
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->get_payment_statuses() );
$this->app->smarty->assign('payment_details',          UpdatePayment::$payment_details);
$this->app->smarty->assign('record_balance',           UpdatePayment::$record_balance);
