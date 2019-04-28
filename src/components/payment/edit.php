<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have an payment_id
if(!isset($VAR['payment_id']) || !$VAR['payment_id']) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
}   

// Check if payment can be edited
if(!check_payment_can_be_edited($VAR['payment_id'])) {
    force_page('payment', 'details&payment_id='.$VAR['payment_id'], 'warning_msg='._gettext("You cannot edit this payment because its status does not allow it."));
}
    
// This is a dirty hack because QWcrm is not fully OOP yet
class UpdatePayment {
    
    private $VAR = null;
    private $type = null;    
    public static $payment_details = array();
    public static $payment_validated = false;
    public static $record_balance = null;
    
    function __construct(&$VAR) {
        
        // Set class variables
        $this->VAR = &$VAR;
        
        // Set Payment details
        self::$payment_details = get_payment_details($VAR['payment_id']);
        
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
            if(self::$payment_validated) {  
                $this->type->update();
                self::$payment_details = get_payment_details($VAR['payment_id']);
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
            force_page('payment', 'search', 'warning_msg='._gettext("Invalid Payment Type."));
            break;

        }
        
        // Load and set the relevant class
        $this->type = new PType($this->VAR);
    
    }
    
}

// Instanciate Update Payment Class
$payment = new UpdatePayment($VAR);

// Build the page
$smarty->assign('client_display_name',      get_client_details(UpdatePayment::$payment_details['client_id'], 'display_name'));
$smarty->assign('employee_display_name',    get_user_details(UpdatePayment::$payment_details['employee_id'], 'display_name'));
$smarty->assign('payment_types',            get_payment_types()    );
$smarty->assign('payment_methods',          get_payment_methods('receive', 'enabled'));
$smarty->assign('payment_statuses',         get_payment_statuses() );
$smarty->assign('payment_details',          UpdatePayment::$payment_details);
$smarty->assign('record_balance',           UpdatePayment::$record_balance);
$BuildPage .= $smarty->fetch('payment/edit.tpl');