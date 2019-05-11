<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'report.php');

if(isset($VAR['submit'])) {

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
    $expense_stats = get_expenses_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $smarty->assign('expense_stats', $expense_stats);    
    
    // Otherincomes
    $otherincome_stats = get_otherincomes_stats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $smarty->assign('otherincome_stats', $otherincome_stats);    
    
    /* Revenue Calculations */   

    // VAT    
    $vat_total_in = $invoice_stats['sum_unit_tax']  + $otherincome_stats['sum_unit_tax'];
    $vat_total_out = $expense_stats['sum_unit_tax'] + $refund_stats['sum_unit_tax'];
    $vat_balance = ($invoice_stats['sum_unit_tax']  + $otherincome_stats['sum_unit_tax']) - ($expense_stats['sum_unit_tax'] + $refund_stats['sum_unit_tax']);    
    $vat_totals = array(
            "invoice"       =>  $invoice_stats['sum_unit_tax'],   
            "otherincome"   =>  $otherincome_stats['sum_unit_tax'],   
            "expense"       =>  $expense_stats['sum_unit_tax'],   
            "refund"        =>  $refund_stats['sum_unit_tax'],   
            "total_in"      =>  $vat_total_in,   
            "total_out"     =>  $vat_total_out,   
            "balance"       =>  $vat_balance            
        );  
    $smarty->assign('vat_totals', $vat_totals );       
    
    // Profit
    $profit_no_tax_ = ($invoice_stats['sum_unit_gross'] + $otherincome_stats['sum_unit_gross']) - ($expense_stats['sum_unit_gross'] + $refund_stats['sum_unit_gross']);
    $profit_sales_tax = ($invoice_stats['sum_unit_net']   + $otherincome_stats['sum_unit_gross']) - ($expense_stats['sum_unit_gross'] + $refund_stats['sum_unit_gross']);
    $profit_vat_tax = ($invoice_stats['sum_unit_net']   + $otherincome_stats['sum_unit_net'])   - ($expense_stats['sum_unit_net']   + $refund_stats['sum_unit_net']);
    $profit_totals = array(
            "no_tax"      =>  $invoice_stats['sum_unit_tax'],   
            "sales_tax"   =>  $otherincome_stats['sum_unit_tax'],   
            "vat_tax"     =>  $expense_stats['sum_unit_tax']         
        );
    $smarty->assign('profit_totals', $profit_totals);    
    
    /* Misc */ 
    
    // Company Tax Type
    //$smarty->assign('tax_system', QW_TAX_SYSTEM);
    
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
$BuildPage .= $smarty->fetch('report/financial.tpl');