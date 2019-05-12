<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'voucher.php');

if(isset($VAR['submit'])) {

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
    
    
    /* Prorata Calculations - Calculate net, tax, gross totals based on the prorata of payments against their parent transaction*/ 

    // Holding array for profit totals (prorata'ed where needed)
    $prorata_totals = array(
                        "invoice" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),                        
                        "refund" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "expense" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "otherincome" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00)                        
                        );
    
    // Run Prorata the records if appropriate
    if(QW_TAX_SYSTEM == 'none') {        
        // Do nothing       
    } elseif (QW_TAX_SYSTEM == 'sales_tax' || QW_TAX_SYSTEM == 'vat_standard' || QW_TAX_SYSTEM == 'vat_cash' || QW_TAX_SYSTEM == 'vat_flat_standard' || QW_TAX_SYSTEM == 'vat_flat_cash') {
        $prorata_totals = array_merge($prorata_totals, prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM));        
    }     
        
    /* Profit Calculations */
    
    // Use Prorata data as a base
    $profit_totals = $prorata_totals;   
    
    // Expired Vouchers - They are complete payments so are effectively prorata'd at 100% anyway so do not require proceesing, just adding
    $profit_totals['voucher_expired']['net'] = $voucher_stats['sum_expired_net'];
    $profit_totals['voucher_expired']['tax'] = $voucher_stats['sum_expired_tax'];
    $profit_totals['voucher_expired']['gross'] = $voucher_stats['sum_expired_gross'];
    
    // None - Straight profit and loss calculations
    if(QW_TAX_SYSTEM == 'none') {
        
        $profit_totals['invoice']['gross'] = $invoice_stats['sum_unit_gross'];        
        $profit_totals['refund']['gross'] = $refund_stats['sum_unit_gross'];
        $profit_totals['expense']['gross'] = $expense_stats['sum_unit_gross'];
        $profit_totals['otherincome']['gross'] = $otherincome_stats['sum_unit_gross'];        
        $profit_totals['profit'] = ($profit_totals['invoice']['gross'] + $profit_totals['otherincome']['gross'] + $profit_totals['voucher_expired']['gross']) - ($profit_totals['expense']['gross'] + $profit_totals['refund']['gross']);}
        
    // Sales Tax - Prorated Profit
    if (QW_TAX_SYSTEM == 'sales_tax_cash') {$profit_totals['profit'] = ($profit_totals['invoice']['net'] + $profit_totals['otherincome']['gross'] + $profit_totals['voucher_expired']['gross']) - ($profit_totals['expense']['gross'] + $profit_totals['refund']['gross']);}
       
    // VAT Standard - Prorated Profit
    if(QW_TAX_SYSTEM == 'vat_standard') {$profit_totals['profit'] = ($profit_totals['invoice']['net'] + $profit_totals['otherincome']['net'] + $profit_totals['voucher_expired']['net']) - ($profit_totals['expense']['net'] + $profit_totals['refund']['net']);}
        
    // VAT Cash - Prorated Profit
    if (QW_TAX_SYSTEM == 'vat_cash') {$profit_totals['profit'] = ($profit_totals['invoice']['net'] + $profit_totals['otherincome']['net'] + $profit_totals['voucher_expired']['net']) - ($profit_totals['expense']['net'] + $profit_totals['refund']['net']);}
        
    // VAT Flat Standard - Prorated Profit
    if(QW_TAX_SYSTEM == 'vat_flat_standard') {$profit_totals['profit'] = ($profit_totals['invoice']['net'] + $profit_totals['otherincome']['net'] + $profit_totals['voucher_expired']['net']) - ($profit_totals['expense']['net'] + $profit_totals['refund']['net']);}
        
    // VAT Flat Cash - Prorated Profit
    if(QW_TAX_SYSTEM == 'vat_flat_cash') {$profit_totals['profit'] = ($profit_totals['invoice']['net'] + $profit_totals['otherincome']['net'] + $profit_totals['voucher_expired']['net']) - ($profit_totals['expense']['net'] + $profit_totals['refund']['net']);}
      
    $smarty->assign('profit_totals', $profit_totals);
           
    /* Tax Calculations */
    
    // Holding array for tax totals (prorata'ed where needed)
    $tax_totals = array(
                "invoice"       =>  0.00,   
                "refund"        =>  0.00,
                "expense"       =>  0.00,   
                "otherincome"   =>  0.00,
                "total_in"      =>  0.00,   
                "total_out"     =>  0.00,   
                "balance"       =>  0.00,            
                "message"       =>  ''           
                ); 
    
    // None - No Tax to process
    if(QW_TAX_SYSTEM == 'none') {        
        // Do Nothing        
    }
        
    // Sales Tax - Prorated TAX
    if (QW_TAX_SYSTEM == 'sales_tax') {
        
        $tax_totals['invoice'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund'] = $prorata_totals['refund']['tax'];
        $tax_totals['expense'] = $prorata_totals['expense']['tax'];
        $tax_totals['otherincome'] = $prorata_totals['otherincome']['tax'];  
        
        $tax_totals['total_in'] = $tax_totals['invoice']  + $tax_totals['otherincome'];
        $tax_totals['total_out'] = $tax_totals['expense'] + $tax_totals['refund'];
        $tax_totals['balance'] = $tax_totals['total_out'] - $tax_totals['total_in'];
        
    }  
    
    // VAT Standard - Date based TAX
    if(QW_TAX_SYSTEM == 'vat_standard') {
        
        $tax_totals['invoice'] = $invoice_stats['sum_unit_tax'];        
        $tax_totals['otherincome'] = $otherincome_stats['sum_unit_tax'];  
        $tax_totals['expense'] = $expense_stats['sum_unit_tax'];
        $tax_totals['refund'] = $refund_stats['sum_unit_tax']; 
        
        $tax_totals['total_in'] = $tax_totals['invoice']  + $tax_totals['otherincome'];
        $tax_totals['total_out'] = $tax_totals['expense'] + $tax_totals['refund'];
        $tax_totals['balance'] = $tax_totals['total_out'] - $tax_totals['total_in'];
        
    }
    
    // VAT Cash - Prorated TAX
    if (QW_TAX_SYSTEM == 'vat_cash') {
         
        $tax_totals['invoice'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund'] = $prorata_totals['refund']['tax'];
        $tax_totals['expense'] = $prorata_totals['expense']['tax'];
        $tax_totals['otherincome'] = $prorata_totals['otherincome']['tax']; 
        
        $tax_totals['total_in'] = $tax_totals['invoice']  + $tax_totals['otherincome'];
        $tax_totals['total_out'] = $tax_totals['expense'] + $tax_totals['refund'];
        $tax_totals['balance'] = $tax_totals['total_out'] - $tax_totals['total_in'];
        
    }    
    
    // VAT Flat Standard - Date based NET x flat rate
    if(QW_TAX_SYSTEM == 'vat_flat_standard') {
        
        $tax_totals['invoice'] = $invoice_stats['sum_unit_tax'];        
        $tax_totals['otherincome'] = $otherincome_stats['sum_unit_tax'];  
        //$tax_totals['expense'] = $expense_stats['sum_unit_tax'];
        //$tax_totals['refund'] = $refund_stats['sum_unit_tax'];
        
        $tax_totals['invoice_net'] = $invoice_stats['sum_unit_net'];
        $tax_totals['otherincome_net'] = $otherincome_stats['sum_unit_net']; 
                
        $tax_totals['total_in'] = $tax_totals['invoice'] + $tax_totals['otherincome'];
        $tax_totals['total_out'] = ($tax_totals['invoice_net'] + $tax_totals['otherincome_net']) * (get_company_details('vat_flat_rate')/100); // Adjusted for flat rate
        $tax_totals['balance'] = $tax_totals['total_out'] - $tax_totals['total_in'];

    }
    
    // VAT Flat Cash - Prorated NET x flat rate
    if(QW_TAX_SYSTEM == 'vat_flat_cash') {
        $tax_totals['invoice'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund'] = $prorata_totals['refund']['tax'];
        //$tax_totals['expense'] = $prorata_totals['expense']['tax'];
        //$tax_totals['otherincome'] = $prorata_totals['otherincome']['tax'];
        
        $tax_totals['invoice_net'] = $prorata_totals['invoice']['net'];
        $tax_totals['otherincome_net'] = $prorata_totals['otherincome']['net']; 
                
        $tax_totals['total_in'] = $tax_totals['invoice'] + $tax_totals['otherincome'];
        $tax_totals['total_out'] = ($tax_totals['invoice_net'] + $tax_totals['otherincome_net']) * (get_company_details('vat_flat_rate')/100); // Adjusted for flat rate
        $tax_totals['balance'] = $tax_totals['total_out'] - $tax_totals['total_in'];
        
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
$smarty->assign('vat_flat_rate', get_company_details('vat_flat_rate') );
$BuildPage .= $smarty->fetch('report/financial.tpl');