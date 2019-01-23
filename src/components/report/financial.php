<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'report.php');

if(isset($VAR['submit'])) {

    /* Build Basic Data Set */

    // Change dates to proper timestamps
    $start_date = date_to_mysql_date($VAR['start_date']);    
    $end_date   = date_to_mysql_date($VAR['end_date']);    
    
    // Clients
    $smarty->assign('new_clients', count_clients($start_date, $end_date) );      
    
    // Workorders
    $smarty->assign('workorder_stats', get_workorders_stats('overall', $start_date, $end_date) );
             
    // Invoices
    $invoice_stats = get_invoices_stats('all', $start_date, $end_date);
    $smarty->assign('invoice_stats', $invoice_stats );       
       
    // Labour
    $smarty->assign('labour_stats', get_labour_stats($start_date, $end_date));   
   
    // Parts
    $smarty->assign('parts_stats', get_parts_stats($start_date, $end_date));
    
    // Expense    
    $expense_totals = get_expenses_totals($start_date, $end_date);    
    $smarty->assign('expense_totals', $expense_totals);    

    // Refunds
    $refund_totals = get_refunds_totals($start_date, $end_date);    
    $smarty->assign('refund_totals', $refund_totals);    
    
    // Otherincomes
    $otherincome_totals = get_otherincomes_totals($start_date, $end_date);    
    $smarty->assign('otherincome_totals',   $otherincome_totals);
    
    /* Revenue Calculations */   

    // VAT    
    $vat_total_in = $invoice_stats['sum_vat_tax_amount']  + $otherincome_totals['sum_vat_amount'];
    $vat_total_out = $expense_totals['sum_vat_amount'] + $refund_totals['sum_vat_amount'];
    $vat_balance = ($invoice_stats['sum_vat_tax_amount']  + $otherincome_totals['sum_vat_amount']) - ($expense_totals['sum_vat_amount'] + $refund_totals['sum_vat_amount']);    
    $vat_totals = array(
            "invoice"       =>  $invoice_stats['sum_vat_tax_amount'],   
            "otherincome"   =>  $otherincome_totals['sum_vat_amount'],   
            "expense"       =>  $expense_totals['sum_vat_amount'],   
            "refund"        =>  $refund_totals['sum_vat_amount'],   
            "total_in"      =>  $vat_total_in,   
            "total_out"     =>  $vat_total_out,   
            "balance"       =>  $vat_balance            
        );  
    $smarty->assign('vat_totals', $vat_totals );       
    
    // Profit
    $profit_no_tax_ = ($invoice_stats['sum_gross_amount'] + $otherincome_totals['sum_gross_amount']) - ($expense_totals['sum_gross_amount'] + $refund_totals['sum_gross_amount']);
    $profit_sales_tax = ($invoice_stats['sum_net_amount']   + $otherincome_totals['sum_gross_amount']) - ($expense_totals['sum_gross_amount'] + $refund_totals['sum_gross_amount']);
    $profit_vat_tax = ($invoice_stats['sum_net_amount']   + $otherincome_totals['sum_net_amount'])   - ($expense_totals['sum_net_amount']   + $refund_totals['sum_net_amount']);
    $profit_totals = array(
            "no_tax"      =>  $invoice_stats['sum_vat_tax_amount'],   
            "sales_tax"   =>  $otherincome_totals['sum_vat_amount'],   
            "vat_tax"     =>  $expense_totals['sum_vat_amount']         
        );
    $smarty->assign('profit_totals', $profit_totals);    
    
    /* Misc */ 
    
    // Company Tax Type
    $smarty->assign('tax_type', get_company_details('tax_type'));
    
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
$BuildPage .= $smarty->fetch('report/financial.tpl');