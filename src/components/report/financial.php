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

    /* Basic Statisitics */

    // Change dates to proper timestamps
    $start_date = date_to_timestamp($VAR['start_date']);    
    $end_date = date_to_timestamp($VAR['end_date']);    
    
    // Clients
    $smarty->assign('new_clients',                count_clients('all', $start_date, $end_date)                         );      
    
    // Workorders   
    $smarty->assign('wo_opened',                    count_workorders('opened', null, $start_date, $end_date)               );   
    $smarty->assign('wo_closed',                    count_workorders('closed', null, $start_date, $end_date)               );    
         
    // Invoices
    $smarty->assign('opened_invoices',              count_invoices('opened', null, $start_date, $end_date)                 );    
    $smarty->assign('pending_invoices',             count_invoices('pending', null, $start_date, $end_date)                );
    $smarty->assign('unpaid_invoices',              count_invoices('unpaid', null, $start_date, $end_date)                 );
    $smarty->assign('partially_paid_invoices',      count_invoices('partially_paid', null, $start_date, $end_date)         );
    $smarty->assign('paid_invoices',                count_invoices('paid', null, $start_date, $end_date)                   );
    
    /* Advanced Statistics */
    
    // Labour
    $smarty->assign('labour_different_items_count', count_labour_different_items($start_date, $end_date)                   );     
    $smarty->assign('labour_items_count',           sum_labour_items('qty', $start_date, $end_date)                        );     
    $smarty->assign('labour_sub_total',             sum_labour_items('sub_total', $start_date, $end_date)                  );   
   
    // Parts
    $smarty->assign('parts_different_items_count',  count_parts_different_items($start_date, $end_date)                    );    
    $smarty->assign('parts_items_count',            sum_parts_value('qty', $start_date, $end_date)                         );    
    $smarty->assign('parts_sub_total',              sum_parts_value('sub_total', $start_date, $end_date)                   );

    // Expense    
    $expense_net_amount     = sum_expenses_value('net_amount', $start_date, $end_date      );
    $expense_vat_amount     = sum_expenses_value('vat_amount', $start_date, $end_date      );
    $expense_gross_amount   = sum_expenses_value('gross_amount', $start_date, $end_date    );            
    $smarty->assign('expense_net_amount',   $expense_net_amount     );
    $smarty->assign('expense_vat_amount',   $expense_vat_amount     );
    $smarty->assign('expense_gross_amount', $expense_gross_amount   );

    // Refunds
    $refund_net_amount      = sum_refunds_value('net_amount', $start_date, $end_date       );
    $refund_vat_amount      = sum_refunds_value('vat_amount', $start_date, $end_date       );
    $refund_gross_amount    = sum_refunds_value('gross_amount', $start_date, $end_date     );    
    $smarty->assign('refund_net_amount',    $refund_net_amount      );
    $smarty->assign('refund_vat_amount',    $refund_vat_amount      );
    $smarty->assign('refund_gross_amount',  $refund_gross_amount    );
    
    /* Revenue Calculations */
    
    // Tax Type
    $smarty->assign('tax_type', get_company_details('tax_type')                                        );
    
    // Invoiced
    $invoice_sub_total          = sum_invoices_value('all', 'sub_total', $start_date, $end_date        );
    $invoice_discount_amount    = sum_invoices_value('all', 'discount_amount', $start_date, $end_date  );
    $invoice_net_amount         = sum_invoices_value('all', 'net_amount', $start_date, $end_date       );
    $invoice_tax_amount         = sum_invoices_value('all', 'tax_amount', $start_date, $end_date       );
    $invoice_gross_amount       = sum_invoices_value('all', 'gross_amount', $start_date, $end_date     );
    $received_monies            = sum_invoices_value('all', 'paid_amount', $start_date, $end_date      );
    $outstanding_balance        = sum_invoices_value('all', 'balance', $start_date, $end_date          );    
    $smarty->assign('invoice_sub_total',        $invoice_sub_total          );       
    $smarty->assign('invoice_discount_amount',  $invoice_discount_amount    ); 
    $smarty->assign('invoice_net_amount',       $invoice_net_amount         );
    $smarty->assign('invoice_tax_amount',       $invoice_tax_amount         );                            
    $smarty->assign('invoice_gross_amount',     $invoice_gross_amount       );
    $smarty->assign('received_monies',          $received_monies            );
    $smarty->assign('outstanding_balance',      $outstanding_balance        );

    // VAT    
    $vat_outlay     = $expense_vat_amount - $refund_vat_amount;
    $vat_invoiced   = $invoice_tax_amount;
    $vat_balance    = $invoice_tax_amount - ($expense_vat_amount - $refund_vat_amount);    
    $smarty->assign('vat_outlay',   $vat_outlay     ); 
    $smarty->assign('vat_invoiced', $vat_invoiced   );
    $smarty->assign('vat_balance',  $vat_balance    );    
    
    // Profit  || Profit = Invoiced - (Expenses - Refunds) 
    $smarty->assign('no_tax_profit',    $invoice_gross_amount - ($expense_gross_amount - $refund_gross_amount)  );
    $smarty->assign('sales_tax_profit', $invoice_net_amount - ($expense_gross_amount - $refund_gross_amount)    );
    $smarty->assign('vat_tax_profit',   $invoice_net_amount - ($expense_net_amount - $refund_net_amount)        );
    
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