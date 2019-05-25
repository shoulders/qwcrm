<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

if(isset($VAR['submit'])) {

    // Get the company VAT FLAT RATE
    $vat_flat_rate = get_company_details('vat_flat_rate');
        
    // Update all Voucher expiry statuses
    check_all_vouchers_for_expiry();
    
    /* Build Basic Data Set */

    // Change dates to proper timestamps
    $start_date = date_to_mysql_date($VAR['start_date']);    
    $end_date   = date_to_mysql_date($VAR['end_date']);    
    
    // Clients
    $smarty->assign('client_stats', get_clients_stats('basic', $start_date, $end_date)  );      
    
    // Workorders
    $smarty->assign('workorder_stats', get_workorders_stats('historic', $start_date, $end_date) );
             
    // Invoices
    $invoice_stats = get_invoices_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);
    $smarty->assign('invoice_stats', $invoice_stats );       
        
    // Vouchers
    $voucher_stats = get_vouchers_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);
    $smarty->assign('voucher_stats', $voucher_stats);
       
    // Payments
    $payment_stats = get_payments_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $smarty->assign('payment_stats', $payment_stats);   
    
    // Refunds
    $refund_stats = get_refunds_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $smarty->assign('refund_stats', $refund_stats);   
        
    // Expense    
    $expense_stats = get_expenses_stats('revenue', $start_date, $end_date, QW_TAX_SYSTEM);    
    $smarty->assign('expense_stats', $expense_stats);    
    
    // Otherincomes
    $otherincome_stats = get_otherincomes_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $smarty->assign('otherincome_stats', $otherincome_stats);    
    
    
    /* Profit and Turnover Calculations */
    
    // Holding array for prorta totals (prorata'ed where needed)     
    $prorata_totals = array(
                        "invoice" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00), 
                        "voucher_mpv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),                        
                        "refund" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "expense" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "otherincome" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),                        
                        "turnover" => array("net" => 0.00, "gross" => 0.00),
                        "profit" => 0.00
                        );
    
    // Prorata Calculations Calculate net, tax, gross totals based on the prorata of payments against their parent transaction (if appropriate)
    if(QW_TAX_SYSTEM == 'no_tax') {        
        // Do nothing       
    } elseif (QW_TAX_SYSTEM == 'sales_tax_cash' || QW_TAX_SYSTEM == 'vat_standard' || QW_TAX_SYSTEM == 'vat_cash' || QW_TAX_SYSTEM == 'vat_flat_basic' || QW_TAX_SYSTEM == 'vat_flat_cash') {
        $prorata_totals = array_merge($prorata_totals, prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM));        
    }     
        
    // Use Prorata array as a base (renaming it here to make the calculations below easier to understand)
    $profit_totals = $prorata_totals;   
    
    // No Tax - Straight profit and loss calculations     
    if(QW_TAX_SYSTEM == 'no_tax') {
        $profit_totals['invoice']['gross'] = $payment_stats['sum_invoice'];        
        $profit_totals['refund']['gross'] = $payment_stats['sum_refund'];
        $profit_totals['otherincome']['gross'] = $payment_stats['sum_otherincome'];
        $profit_totals['expense']['gross'] = $payment_stats['sum_expense'];        
        $profit_totals['turnover']['gross'] = ($payment_stats['sum_invoice'] + $payment_stats['sum_otherincome']) - $payment_stats['sum_refund'];
        $profit_totals['profit'] = $profit_totals['turnover']['gross'] - $payment_stats['sum_expense'];
    }
            
    // Sales Tax (Cash Basis) - Prorated Turnover / Prorated Profit
    if (QW_TAX_SYSTEM == 'sales_tax_cash') {
        $profit_totals['turnover']['net'] = ($prorata_totals['invoice']['net'] + $prorata_totals['otherincome']['gross']) - $prorata_totals['refund']['net'];
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $prorata_totals['expense']['gross'];        
    }
       
    // VAT Standard Accounting (UK) - Record Based Turnover / Record Based Profit
    if(QW_TAX_SYSTEM == 'vat_standard') {
        $profit_totals['turnover']['net'] = ($invoice_stats['sum_unit_net'] + $otherincome_stats['sum_unit_net']) - $refund_stats['sum_unit_net'];
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $expense_stats['sum_unit_net'];        
    }
    
    // VAT Cash Accounting (UK) - Prorated Turnover / Prorated Profit
    if(QW_TAX_SYSTEM == 'vat_cash') {
        $profit_totals['turnover']['net'] = ($prorata_totals['invoice']['net'] + $prorata_totals['otherincome']['net']) - $prorata_totals['refund']['net'];  
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $prorata_totals['expense']['net'];        
    }
    
    // VAT Flat Rate (Basic turnover) (UK) - Record Based Turnover / Record Based Profit
    if(QW_TAX_SYSTEM == 'vat_flat_basic') {       
        $profit_totals['turnover']['gross'] = ($invoice_stats['sum_unit_gross'] + $otherincome_stats['sum_unit_gross']) - $refund_stats['sum_unit_gross'];
        $vat_liability = $profit_totals['turnover']['gross'] * ($vat_flat_rate/100);
        $profit_totals['turnover']['net'] = $profit_totals['turnover']['gross'] - $vat_liability;
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $expense_stats['sum_unit_gross'];
    }
    
    // VAT Flat Rate (Cash based turnover) (UK) - Prorated Turnover / Prorated Profit
    if(QW_TAX_SYSTEM == 'vat_flat_cash') {
        $profit_totals['turnover']['gross'] = ($prorata_totals['invoice']['gross'] + $prorata_totals['otherincome']['gross']) - $prorata_totals['refund']['gross'];
        $vat_liability = $profit_totals['sales']['gross'] * ($vat_flat_rate/100);
        $profit_totals['turnover']['net'] = $profit_totals['turnover']['gross'] - $vat_liability;
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $prorata_totals['expense']['gross'];
   }
    
    $smarty->assign('profit_totals', $profit_totals);
           
    /* Tax Calculations */
    
    // Holding array for tax totals (prorata'ed where needed)
    $tax_totals = array(
                "invoice" => array("net" => 0.00, "tax" => 0.00), 
                "voucher_mpv" => array("net" => 0.00, "tax" => null),
                "refund" => array("net" => 0.00, "tax" => 0.00),
                "expense" => array("net" => null, "tax" => 0.00),
                "otherincome" => array("net" => 0.00, "tax" => 0.00),
                "total_in"      =>  0.00,   
                "total_out"     =>  0.00,   
                "balance"       =>  0.00,            
                "message"       =>  ''
                );
    
    // No Tax - No Tax to process
    if(QW_TAX_SYSTEM == 'no_tax') {        
        // Do Nothing        
    }
        
    // Sales Tax (Cash Basis)- Prorated TAX
    if (QW_TAX_SYSTEM == 'sales_tax_cash') {
        
        $tax_totals['invoice']['tax'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund']['tax'] = $prorata_totals['refund']['tax'];         
        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax'];
        $tax_totals['total_out'] = $tax_totals['refund']['tax'];
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];
        
    }  
    
    // VAT Standard Accounting (UK) - Record Based TAX
    if(QW_TAX_SYSTEM == 'vat_standard') {
        
        $tax_totals['invoice']['tax'] = $invoice_stats['sum_unit_tax'];        
        $tax_totals['otherincome']['tax'] = $otherincome_stats['sum_unit_tax'];  
        $tax_totals['expense']['tax'] = $expense_stats['sum_unit_tax'];
        $tax_totals['refund']['tax'] = $refund_stats['sum_unit_tax']; 
        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax']  + $tax_totals['otherincome']['tax'];
        $tax_totals['total_out'] = $tax_totals['expense']['tax'] + $tax_totals['refund']['tax'];
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];
        
    }
    
    // VAT Cash Accounting (UK) - Prorated TAX
    if (QW_TAX_SYSTEM == 'vat_cash') {
         
        $tax_totals['invoice']['tax'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund']['tax'] = $prorata_totals['refund']['tax'];
        $tax_totals['expense']['tax'] = $prorata_totals['expense']['tax'];
        $tax_totals['otherincome']['tax'] = $prorata_totals['otherincome']['tax']; 
        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax']  + $tax_totals['otherincome']['tax'];
        $tax_totals['total_out'] = $tax_totals['expense']['tax'] + $tax_totals['refund']['tax'];
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];
        
    }    
    
    // VAT Flat Rate (Basic turnover) (UK) - Gross Turnover x Flat Rate
    if(QW_TAX_SYSTEM == 'vat_flat_basic') {
                
        $tax_totals['balance'] = $profit_totals['turnover']['gross'] * ($vat_flat_rate/100);
        
        /*$tax_totals['invoice']['tax'] = $invoice_stats['sum_unit_tax']; 
        $tax_totals['refund']['tax'] = $refund_stats['sum_unit_tax'];        
        $tax_totals['otherincome']['tax'] = $otherincome_stats['sum_unit_tax'];          
        
        $tax_totals['invoice']['net'] = $invoice_stats['sum_unit_net'];
        $tax_totals['refund']['net'] = $refund_stats['sum_unit_net'];
        $tax_totals['otherincome']['net'] = $otherincome_stats['sum_unit_net'];
        $tax_totals['voucher_mpv']['net'] = $voucher_stats['sum_voucher_mpv_unit_net']; 
                
        $tax_totals['total_in'] = $tax_totals['invoice']['tax'] + $tax_totals['otherincome']['tax'] - $tax_totals['refund']['tax'];
        $tax_totals['total_out'] = (($tax_totals['invoice']['net'] + $tax_totals['otherincome']['net']) - ($tax_totals['refund']['net'] + $tax_totals['voucher_mpv']['net'])) * ($vat_flat_rate/100); // Adjusted for flat rate
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];*/

    }
    
    // VAT Flat Rate (Cash based turnover) (UK) - Gross Turnover x Flat Rate
    if(QW_TAX_SYSTEM == 'vat_flat_cash') {
        
        $tax_totals['balance'] = $profit_totals['turnover']['gross'] * ($vat_flat_rate/100);
        
        /*$tax_totals['invoice']['tax'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund']['tax'] = $prorata_totals['refund']['tax'];        
        $tax_totals['otherincome']['tax'] = $prorata_totals['otherincome']['tax'];
        
        $tax_totals['invoice']['net'] = $prorata_totals['invoice']['net'];
        $tax_totals['refund']['net'] = $prorata_totals['refund']['net']; 
        $tax_totals['otherincome']['net'] = $prorata_totals['otherincome']['net'];         
        $tax_totals['voucher_mpv']['net'] = $prorata_totals['voucher_mpv']['net'];
                        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax'] + $tax_totals['otherincome']['tax'] - $tax_totals['refund']['tax'];
        $tax_totals['total_out'] = (($tax_totals['invoice']['net'] + $tax_totals['otherincome']['net']) - ($tax_totals['refund']['net'] + $tax_totals['voucher_mpv']['net'])) * ($vat_flat_rate/100); // Adjusted for flat rate
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];*/
        
    }
    
    // Tell the user who the balance is owed to
    if($tax_totals['balance'] < 0) { $message = _gettext("The Tax Man owes you this amount."); }
    elseif ($tax_totals['balance'] == 0) {$message = _gettext("There is nothing to pay.");} 
    else { $message = _gettext("You owe the Tax Man this amount.");}
    $tax_totals['message'] = $message;     

    // Remove negative number (if present)
    $tax_totals['balance'] = abs($tax_totals['balance']);     
    
    $smarty->assign('tax_totals', $tax_totals);  

    /* Misc */ 
    
    // Enable Report Section
    $smarty->assign('enable_report_section', true);
    
    /* Logging */
    
    // Log activity
    write_record_to_activity_log(_gettext("Financial report run for the date range").': '.$VAR['start_date'].' - '.$VAR['end_date']);
    
} else {
    
    // Prevent undefined variable errors
    $smarty->assign('enable_report_section', false);
    
    // Load company finacial year dates
    $start_date = get_company_details('year_start'); 
    $end_date   = get_company_details('year_end'); 
    
}

// Build the page
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date', $end_date);
$smarty->assign('tax_systems', get_tax_systems() );
$smarty->assign('vat_flat_rate', $vat_flat_rate);
$BuildPage .= $smarty->fetch('report/financial.tpl');