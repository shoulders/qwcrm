<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/report.php');

if(isset($VAR['submit'])) {

    /* General Section */

    // Change dates to proper timestamps
    $start_date = date_to_timestamp($VAR['start_date']);    
    $end_date = date_to_timestamp($VAR['end_date']);    
    
    // Customers
    $smarty->assign('new_customers',                count_customers($db, 'all', $start_date, $end_date)                         );      
    
    // Workorders   
    $smarty->assign('wo_opened',                    count_workorders($db, 'open', null, $start_date, $end_date)                 );   
    $smarty->assign('wo_closed',                    count_workorders($db, 'closed', null, $start_date, $end_date)               );    
         
    // Invoices
    $smarty->assign('new_invoices',                 count_invoices($db, 'all', null, $start_date, $end_date)                    );    
    $smarty->assign('paid_invoices',                count_invoices($db, 'paid', null, $start_date, $end_date)                   );
    
    // Labour
    $smarty->assign('labour_different_items_count', count_labour_different_items($db, $start_date, $end_date)                   );     
    $smarty->assign('labour_items_count',           sum_labour_items($db, 'qty', $start_date, $end_date)                        );     
    $smarty->assign('labour_sub_total',             sum_labour_items($db, 'sub_total', $start_date, $end_date)                  );   
   
    // Parts
    $smarty->assign('parts_different_items_count',  count_parts_different_items($db, $start_date, $end_date)                    );    
    $smarty->assign('parts_count',                  sum_parts_value($db, 'qty', $start_date, $end_date)                         );    
    $smarty->assign('parts_sub_total',              sum_parts_value($db, 'sub_total', $start_date, $end_date)                   );

    // Expense                      
    $smarty->assign('expense_gross_amount',         sum_expenses_value($db, 'gross_amount', $start_date, $end_date)             );  
    $smarty->assign('expense_tax_amount',           sum_expenses_value($db, 'tax_amount', $start_date, $end_date)               );   
    $smarty->assign('expense_net_amount',           sum_expenses_value($db, 'net_amount', $start_date, $end_date)               ); 

    // Refunds                  
    $smarty->assign('refund_gross_amount',          sum_refunds_value($db, 'gross_amount', $start_date, $end_date)              ); 
    $smarty->assign('refund_tax_amount',            sum_refunds_value($db, 'tax_amount', $start_date, $end_date)                );  
    $smarty->assign('refund_net_amount',            sum_refunds_value($db, 'net_amount', $start_date, $end_date)                );
    
    // Revenue
    $smarty->assign('invoice_sub_total',            sum_invoices_value($db, 'sub_total', 'all', $start_date, $end_date)         );       
    $smarty->assign('invoice_discount_amount',      sum_invoices_value($db, 'discount_amount', 'all', $start_date, $end_date)   ); 
    $smarty->assign('invoice_net_amount',           sum_invoices_value($db, 'net_amount', 'all', $start_date, $end_date)        );
    $smarty->assign('invoice_tax_amount',           sum_invoices_value($db, 'tax_amount', 'all', $start_date, $end_date)        );                            
    $smarty->assign('invoice_gross_amount',         sum_invoices_value($db, 'gross_amount', 'all', $start_date, $end_date)      );
    $smarty->assign('received_monies',              sum_invoices_value($db, 'paid_amount', 'all', $start_date, $end_date)       );
    $smarty->assign('outstanding_balance',          sum_invoices_value($db, 'balance', 'all', $start_date, $end_date)           );

    /* Calculations Section */
 
    // Taxable Profit = Invoiced - (Expenses - Refunds) 
    
    // Profit (Net)    
    $smarty->assign('taxable_profit_net',           sum_invoices_value($db, 'net_amount', 'all', $start_date, $end_date) - (sum_expenses_value($db, 'net_amount', $start_date, $end_date) - sum_refunds_value($db, 'net_amount', $start_date, $end_date))       );    
    
    // Profit (Gross)                                                       
    $smarty->assign('taxable_profit_gross',         sum_invoices_value($db, 'gross_amount', 'all', $start_date, $end_date) - (sum_expenses_value($db, 'gross_amount', $start_date, $end_date) - sum_refunds_value($db, 'gross_amount', $start_date, $end_date)) );
    
    // VAT (Tax)                 
    $smarty->assign('vat_paid',                     sum_expenses_value($db, 'tax_amount', $start_date, $end_date) - sum_refunds_value($db, 'tax_amount', $start_date, $end_date)                                                                              ); 
    $smarty->assign('vat_received',                 sum_invoices_value($db, 'tax_amount', 'all', $start_date, $end_date)  ); 
    $smarty->assign('vat_balance',                  sum_invoices_value($db, 'tax_amount', 'all', $start_date, $end_date) - (sum_expenses_value($db, 'tax_amount', $start_date, $end_date) - sum_refunds_value($db, 'tax_amount', $start_date, $end_date))     );    
    
    
} else {
    
    // Load company finacial year dates
    $start_date = get_company_details($db, 'year_start'); 
    $end_date = get_company_details($db, 'year_end'); 
    
}

// Build the page
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date', $end_date);
$BuildPage .= $smarty->fetch('report/financial.tpl');