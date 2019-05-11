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
    
    /* VAT Calculations */ // this only works for standard accounting becasue it is done on the day of invoice not on payment date
    
    if(preg_match('/^vat_/', QW_TAX_SYSTEM)) {
        $vat_total_in = $invoice_stats['sum_unit_tax']  + $otherincome_stats['sum_unit_tax'];
        $vat_total_out = $expense_stats['sum_unit_tax'] + $refund_stats['sum_unit_tax'];
        $vat_balance = ($invoice_stats['sum_unit_tax']  + $otherincome_stats['sum_unit_tax']) - ($expense_stats['sum_unit_tax'] + $refund_stats['sum_unit_tax']);

        // Tell the user who the balance is owed to
        if($vat_balance < 0) {
            $vat_note = _gettext("The Tax Man owes you this amount.");
        } elseif ($vat_balance == 0) { 
            $vat_note = _gettext("There is nothing to pay."); 
        } else { 
            $vat_note = _gettext("You owe the Tax Man this amount.");  
        }

        $vat_totals = array(
                "invoice"       =>  $invoice_stats['sum_unit_tax'],   
                "otherincome"   =>  $otherincome_stats['sum_unit_tax'],   
                "expense"       =>  $expense_stats['sum_unit_tax'],   
                "refund"        =>  $refund_stats['sum_unit_tax'],   
                "total_in"      =>  $vat_total_in,   
                "total_out"     =>  $vat_total_out,   
                "balance"       =>  abs($vat_balance),            
                "note"          =>  $vat_note            
            );  
        $smarty->assign('vat_totals', $vat_totals );       
    }
    
    
    
    
    
    
    
    /* Sales Tax Calculations */  // needs to be done in the profit prorata
    
    if(QW_TAX_SYSTEM == 'sales_tax') {
        $sales_tax_totals = array(
                "invoice"       =>  $invoice_stats['sum_unit_tax']                         
            );  
        $smarty->assign('sales_tax_totals', $sales_tax_totals );
    }
    
    /* Profit */
    
    $profit_totals = array(
                        "invoice" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "voucher_expired" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "refund" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "expense" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "otherincome" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00)
                        );
    
    // Expired Vouchers are common, add here
    $profit_totals['voucher_expired']['net'] = $voucher_stats['sum_expired_net'];
    $profit_totals['voucher_expired']['tax'] = $voucher_stats['sum_expired_tax'];
    $profit_totals['voucher_expired']['gross'] = $voucher_stats['sum_expired_gross'];
    
    // Straight profit and loss calculations
    if(QW_TAX_SYSTEM == 'none') {
        
        $profit_totals['invoice']['gross'] = $invoice_stats['sum_unit_gross'];        
        $profit_totals['refund']['gross'] = $refund_stats['sum_unit_gross'];
        $profit_totals['expense']['gross'] = $expense_stats['sum_unit_gross'];
        $profit_totals['otherincome']['gross'] = $otherincome_stats['sum_unit_gross'];
        $profit_totals['profit'] = ($invoice_stats['sum_unit_gross'] + $otherincome_stats['sum_unit_gross'] + $voucher_stats['sum_expired_gross']) - ($expense_stats['sum_unit_gross'] + $refund_stats['sum_unit_gross']);
    }
        
    // Prorata Calculations - both revenue and tax due is prorataed
    if (QW_TAX_SYSTEM == 'sales_tax' || QW_TAX_SYSTEM == 'vat_cash') {
        $profit_totals = array_merge($profit_totals, prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM));
    }    
    
    //the revenue is prorataed but the vat is not. that is due on the invoice date
    if(QW_TAX_SYSTEM == 'vat_standard') {
        $profit_totals = prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM);
    }
    
    // revenue might be prorated but the vat is not. turnover * vat_flat_rate = vat liability for the period
    if(QW_TAX_SYSTEM == 'vat_flat') {
        $profit_totals = prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM);
    }
    
    
    
    
    $profit_sales_tax = ($invoice_stats['sum_unit_net'] + $otherincome_stats['sum_unit_gross']) - ($expense_stats['sum_unit_gross'] + $refund_stats['sum_unit_gross']);
    $profit_vat_tax = ($invoice_stats['sum_unit_net']   + $otherincome_stats['sum_unit_net'])   - ($expense_stats['sum_unit_net']   + $refund_stats['sum_unit_net']);
    $aprofit_totals = array(
            "none"      =>  $invoice_stats['sum_unit_tax'],   
            "sales_tax"   =>  $otherincome_stats['sum_unit_tax'],   
            "vat_tax"     =>  $expense_stats['sum_unit_tax']         
                );
      
    
    
    
    
    
    
    
    
    
    /*if(QW_TAX_SYSTEM == 'none') {
        $profit_totals['gross'] = ($invoice_stats['sum_unit_gross'] + $otherincome_stats['sum_unit_gross'] + $voucher_stats['sum_expired_gross']) - ($expense_stats['sum_unit_gross'] + $refund_stats['sum_unit_gross']);
    } else
    
    if(QW_TAX_SYSTEM == 'sales_tax') {
        
        $profit_totals = array_merge($profit_totals, prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM));

    }
    
    if(QW_TAX_SYSTEM == 'vat_standard') {
        $profit_totals = prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM);
    }
    
    if(QW_TAX_SYSTEM == 'vat_flat') {
        $profit_totals = prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM);
    }
    
    if(QW_TAX_SYSTEM == 'vat_cash') {
        $profit_totals = prorata_payments_against_records($start_date, $end_date, QW_TAX_SYSTEM);
    }*/
    
    
    
    
    $smarty->assign('profit_totals', $profit_totals);
    ////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    
    
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
$BuildPage .= $smarty->fetch('report/financial.tpl');