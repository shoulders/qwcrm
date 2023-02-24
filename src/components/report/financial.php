<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

if(isset(\CMSApplication::$VAR['start_date'], \CMSApplication::$VAR['end_date']))
{
    // Get the company VAT Flat Rate
    $vat_flat_rate = $this->app->components->company->getRecord('vat_flat_rate');
        
    // Update all Voucher expiry statuses
    $this->app->components->voucher->checkAllVouchersForExpiry();
    
    // Update all Creditnote expiry statuses
    $this->app->components->creditnote->checkAllCreditnotesForExpiry();
    
    /* Get Data Sets */

    // Change dates to proper timestamps
    $start_date = $this->app->system->general->dateToMysqlDate(\CMSApplication::$VAR['start_date']);    
    $end_date   = $this->app->system->general->dateToMysqlDate(\CMSApplication::$VAR['end_date']);    
    
    // Get get stats for calculations (below)
    $invoice_stats = $this->app->components->report->getInvoicesStats('all', $start_date, $end_date, QW_TAX_SYSTEM);        
    $voucher_stats = $this->app->components->report->getVouchersStats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $refund_stats = $this->app->components->report->getRefundsStats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $expense_stats = $this->app->components->report->getExpensesStats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    $otherincome_stats = $this->app->components->report->getOtherincomesStats('all', $start_date, $end_date, QW_TAX_SYSTEM);
    $payment_stats = $this->app->components->report->getPaymentsStats('all', $start_date, $end_date, QW_TAX_SYSTEM);    
    
    // Assign stats to Template variables
    $this->app->smarty->assign('client_stats', $this->app->components->report->getClientsStats('current', $start_date, $end_date));      
    $this->app->smarty->assign('workorder_stats', $this->app->components->report->getWorkordersStats('historic', $start_date, $end_date));            
    $this->app->smarty->assign('invoice_stats', $invoice_stats);       
    $this->app->smarty->assign('voucher_stats', $voucher_stats);    
    $this->app->smarty->assign('refund_stats', $refund_stats);   
    $this->app->smarty->assign('expense_stats', $expense_stats);    
    $this->app->smarty->assign('otherincome_stats', $otherincome_stats);
    $this->app->smarty->assign('payment_stats', $payment_stats);   
        
    /* Profit and Turnover Calculations */
    
    // Holding array for prorata totals (prorata'ed where needed)     
    $profit_totals = array(
                        "invoice" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00), 
                        //"voucher" => array("spv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00), "mpv" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00)),  (not currently used)
                        "refund" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "expense" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),
                        "otherincome" => array("net" => 0.00, "tax" => 0.00, "gross" => 0.00),                        
                        "turnover" => array("net" => 0.00, "gross" => 0.00),
                        "profit" => 0.00
                        );
    
    // Prorata Calculations Calculate NET/TAX/GROSS totals based on the prorata of payments against their parent transaction (if appropriate)
    if (QW_TAX_SYSTEM == 'sales_tax_cash' || QW_TAX_SYSTEM == 'vat_cash' || QW_TAX_SYSTEM == 'vat_flat_cash')
    {
        $prorata_totals = $this->app->components->report->revenuePaymentsProratedAgainstRecords($start_date, $end_date, QW_TAX_SYSTEM);
    }     
        
    // No Tax - Straight profit and loss calculations     
    if(QW_TAX_SYSTEM == 'no_tax')
    {        
        $profit_totals['invoice']['gross'] = $payment_stats['sum_invoice'];  // Voucher payments have already been removed   
        $profit_totals['refund']['gross'] = $payment_stats['sum_refund'];
        $profit_totals['otherincome']['gross'] = $payment_stats['sum_otherincome'];
        $profit_totals['expense']['gross'] = $payment_stats['sum_expense'];
        
        $profit_totals['turnover']['gross'] = ($payment_stats['sum_invoice'] + $payment_stats['sum_otherincome']) - $payment_stats['sum_refund'];
        $profit_totals['profit'] = $profit_totals['turnover']['gross'] - $payment_stats['sum_expense'];
    }
            
    // Sales Tax (Cash Basis) - Prorated Turnover / Prorated Profit
    if (QW_TAX_SYSTEM == 'sales_tax_cash')
    {
        $profit_totals['invoice']['net'] = $prorata_totals['invoice']['net'];        
        $profit_totals['refund']['net'] = $prorata_totals['refund']['net'];
        $profit_totals['otherincome']['gross'] = $prorata_totals['otherincome']['gross'];
        $profit_totals['expense']['gross'] = $prorata_totals['expense']['gross']; 
        
        $profit_totals['turnover']['net'] = ($prorata_totals['invoice']['net'] + $prorata_totals['otherincome']['gross']) - $prorata_totals['refund']['net'];
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $prorata_totals['expense']['gross'];        
    }
       
    // VAT Standard Accounting (UK) - Record Based Turnover / Record Based Profit
    if(QW_TAX_SYSTEM == 'vat_standard')
    {
        $profit_totals['invoice']['net'] = $invoice_stats['sum_unit_net'];        
        $profit_totals['refund']['net'] = $refund_stats['sum_unit_net'];
        $profit_totals['otherincome']['net'] = $otherincome_stats['sum_unit_net'];
        $profit_totals['expense']['net'] = $expense_stats['sum_unit_net'];  
        
        $profit_totals['turnover']['net'] = ($invoice_stats['sum_unit_net'] + $otherincome_stats['sum_unit_net']) - $refund_stats['sum_unit_net'];
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $expense_stats['sum_unit_net'];        
    }
    
    // VAT Cash Accounting (UK) - Prorated Turnover / Prorated Profit
    if(QW_TAX_SYSTEM == 'vat_cash')
    {
        $profit_totals['invoice']['net'] = $prorata_totals['invoice']['net'];        
        $profit_totals['refund']['net'] = $prorata_totals['refund']['net'];
        $profit_totals['otherincome']['net'] = $prorata_totals['otherincome']['net'];
        $profit_totals['expense']['net'] = $prorata_totals['expense']['net'];         
        
        $profit_totals['turnover']['net'] = ($prorata_totals['invoice']['net'] + $prorata_totals['otherincome']['net']) - $prorata_totals['refund']['net'];  
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $prorata_totals['expense']['net'];        
    }
    
    // VAT Flat Rate (Basic turnover) (UK) - Record Based Turnover / Record Based Profit (this only takes into account the transactions and not payments so there is no double taxation issue)
    if(QW_TAX_SYSTEM == 'vat_flat_basic')
    {
        $profit_totals['invoice']['gross'] = $invoice_stats['sum_unit_gross'];        
        $profit_totals['refund']['gross'] = $refund_stats['sum_unit_gross'];
        $profit_totals['otherincome']['gross'] = $otherincome_stats['sum_unit_gross'];
        $profit_totals['expense']['gross'] = $expense_stats['sum_unit_gross']; 
        
        $profit_totals['turnover']['gross'] = ($invoice_stats['sum_unit_gross'] + $otherincome_stats['sum_unit_gross']) - $refund_stats['sum_unit_gross'];
        $vat_liability = ($profit_totals['turnover']['gross']) * ($vat_flat_rate/100);
        $profit_totals['turnover']['net'] = $profit_totals['turnover']['gross'] - $vat_liability;
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $expense_stats['sum_unit_gross'];
    }
        
    // VAT Flat Rate (Cash Based Turnover) (UK) - Prorated Turnover / Prorated Profit - Vouchers (Redeemed vouchers do not appear in turnover/prorata totals so there is no double taxation issue)
    // This makes you pay Flat Rate VAT for a voucher when you pay the invoice, not when the voucher is redeemed (I suppose that fits with turnover x Flat Rate)
    if(QW_TAX_SYSTEM == 'vat_flat_cash')
    {     
        $profit_totals['invoice']['gross'] = $prorata_totals['invoice']['gross'];        
        $profit_totals['refund']['gross'] = $prorata_totals['refund']['gross'];
        $profit_totals['otherincome']['gross'] = $prorata_totals['otherincome']['gross'];
        $profit_totals['expense']['gross'] = $prorata_totals['expense']['gross'];
        
        $profit_totals['turnover']['gross'] = ($prorata_totals['invoice']['gross'] + $prorata_totals['otherincome']['gross']) - $prorata_totals['refund']['gross'];
        $vat_liability = ($profit_totals['turnover']['gross']) * ($vat_flat_rate/100);
        $profit_totals['turnover']['net'] = $profit_totals['turnover']['gross'] - $vat_liability;
        $profit_totals['profit'] = $profit_totals['turnover']['net'] - $prorata_totals['expense']['gross'];
    }
    
    $this->app->smarty->assign('profit_totals', $profit_totals);
           
    /* Tax Calculations */
    
    // Holding array for tax totals (prorata'ed where needed)
    $tax_totals = array(
                "invoice" => array("net" => 0.00, "tax" => 0.00),                 
                "refund" => array("net" => 0.00, "tax" => 0.00),
                "expense" => array("net" => null, "tax" => 0.00),
                "otherincome" => array("net" => 0.00, "tax" => 0.00),
                "total_in"      =>  0.00,   
                "total_out"     =>  0.00,   
                "balance"       =>  0.00,            
                "message"       =>  ''
                );
    
    // No Tax - No Tax to process
    if(QW_TAX_SYSTEM == 'no_tax')
    {        
        // Do Nothing        
    }
        
    // Sales Tax (Cash Basis)- Prorated TAX
    if (QW_TAX_SYSTEM == 'sales_tax_cash')
    {        
        $tax_totals['invoice']['tax'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund']['tax'] = $prorata_totals['refund']['tax'];         
        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax'];
        $tax_totals['total_out'] = $tax_totals['refund']['tax'];
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];        
    }  
    
    // VAT Standard Accounting (UK) - Record Based TAX
    if(QW_TAX_SYSTEM == 'vat_standard')
    {        
        $tax_totals['invoice']['tax'] = $invoice_stats['sum_unit_tax'];        
        $tax_totals['otherincome']['tax'] = $otherincome_stats['sum_unit_tax'];  
        $tax_totals['expense']['tax'] = $expense_stats['sum_unit_tax'];
        $tax_totals['refund']['tax'] = $refund_stats['sum_unit_tax']; 
        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax']  + $tax_totals['otherincome']['tax'];
        $tax_totals['total_out'] = $tax_totals['expense']['tax'] + $tax_totals['refund']['tax'];
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];        
    }
    
    // VAT Cash Accounting (UK) - Prorated TAX
    if (QW_TAX_SYSTEM == 'vat_cash')
    {         
        $tax_totals['invoice']['tax'] = $prorata_totals['invoice']['tax'];
        $tax_totals['refund']['tax'] = $prorata_totals['refund']['tax'];
        $tax_totals['expense']['tax'] = $prorata_totals['expense']['tax'];
        $tax_totals['otherincome']['tax'] = $prorata_totals['otherincome']['tax']; 
        
        $tax_totals['total_in'] = $tax_totals['invoice']['tax']  + $tax_totals['otherincome']['tax'];
        $tax_totals['total_out'] = $tax_totals['expense']['tax'] + $tax_totals['refund']['tax'];
        $tax_totals['balance'] = $tax_totals['total_in'] - $tax_totals['total_out'];        
    }    
    
    // VAT Flat Rate (Basic turnover) (UK) - Gross Turnover x Flat Rate
    if(QW_TAX_SYSTEM == 'vat_flat_basic')
    {
        $tax_totals['balance'] = $vat_liability;  // Calculated above in 'Profit and Turnover Calculations' section
    }
    
    // VAT Flat Rate (Cash Based Turnover) (UK) - Gross Turnover x Flat Rate
    if(QW_TAX_SYSTEM == 'vat_flat_cash')
    {
        $tax_totals['balance'] = $vat_liability;  // Calculated above in 'Profit and Turnover Calculations' section
    }
    
    // Tell the user who the balance is owed to
    if($tax_totals['balance'] < 0) { $message = _gettext("The Tax Man owes you this amount."); }
    elseif ($tax_totals['balance'] == 0) {$message = _gettext("There is nothing to pay.");} 
    else { $message = _gettext("You owe the Tax Man this amount.");}
    $tax_totals['message'] = $message;     

    // Remove negative number (if present)
    $tax_totals['balance'] = abs($tax_totals['balance']);     
    
    $this->app->smarty->assign('tax_totals', $tax_totals);  

    /* Misc */ 
    
    // Set vat_flat_rate
    $this->app->smarty->assign('vat_flat_rate', $vat_flat_rate);
    
    // Enable Report Section
    $this->app->smarty->assign('enable_report_section', true);
    
    /* Logging */
    
    // Log activity
    $this->app->system->general->writeRecordToActivityLog(_gettext("Financial report run for the date range").': '.\CMSApplication::$VAR['start_date'].' - '.\CMSApplication::$VAR['end_date']);
 
// If no submit just load basic page
}
else
{    
    // Set the report section to hidden - Prevents undefined variable error
    $this->app->smarty->assign('enable_report_section', false);
    
    // Load company finacial year dates
    $start_date = $this->app->components->company->getRecord('year_start'); 
    $end_date   = $this->app->components->company->getRecord('year_end');    
}

// Build the page
$this->app->smarty->assign('start_date', $start_date);
$this->app->smarty->assign('end_date', $end_date);
$this->app->smarty->assign('tax_systems', $this->app->components->company->getTaxSystems());