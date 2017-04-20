<?php

require(INCLUDES_DIR.'modules/report.php');

if(isset($VAR['submit'])){

    /* General Section */

    // Change dates to proper timestamps and assign
    $start_date = date_to_timestamp($VAR['start_date']);    
    $end_date = date_to_timestamp($VAR['end_date']);
    $smarty->assign('start_date', $start_date);
    $smarty->assign('end_date', $end_date);

    // Count open work orders in selected period
    $smarty->assign('wo_opened', count_open_workorders_in_seleceted_period($db, $start_date, $end_date));

    // Count closed work orders in selected period    
    $smarty->assign('wo_closed', count_open_workorders_in_selected_period($db, $start_date, $end_date));

    // Count New Customers in selected period    
    $smarty->assign('new_customers', count_new_customers_in_selected_period($db, $start_date, $end_date));

    // Count Total Customers in QWcrm    
    $smarty->assign('total_customers', count_total_customers_in_qwcrm($db));

    // Count Created Invoices in selected period   
    $smarty->assign('new_invoices', count_created_invoices_in_selected_period($db, $start_date, $end_date));

    // Count Paid Invoices in selected period
    $smarty->assign('paid_invoices' , count_paid_invoices_in_selected_period($db, $start_date, $end_date));

    /* Parts Section */

    // Count Total number of different part items ordered in selected period
    $smarty->assign('parts_different_items_count', count_total_number_of_different_part_items_ordered_in_selected_period($db, $start_date, $end_date));

    // Sum Total quantity of part items ordered in selected period    
    $smarty->assign('parts_items_sum', sum_total_quantity_of_part_items_ordered_in_selected_period($db, $start_date, $end_date));

    // Sum Parts Sub Total in selected period
    $smarty->assign('parts_sub_total_sum', $parts_sub_total_sum);

    /* Labour Section */

    // Count Total number of different labour items in selected period    
    $smarty->assign('labour_different_items_count', count_total_number_of_different_labour_items_in_selected_period($db, $start_date, $end_date));

    // Sum Total quantity of labour items in selected period    
    $smarty->assign('labour_items_sum', sum_total_quantity_of_labour_items_in_selected_period($db, $start_date, $end_date));

    // Sum Labour Sub Totals in selected period    
    $smarty->assign('labour_sub_total_sum', sum_labour_sub_totals_in_selected_period($db, $start_date, $end_date));

    /* Expense Section */

    // Sum Expenses Net Amount in selected period
    $smarty->assign('expense_net_amount_sum', sum_expenses_net_amount_in_selected_period($db, $start_date, $end_date));    

    // Sum Expenses Tax Amount in selected period    
    $smarty->assign('expense_tax_amount_sum', sum_expenses_tax_amount_in_selected_period($db, $start_date, $end_date));

    // Sum Expenses Gross Amount in selected period    
    $expense_gross_amount_sum = sum_expenses_gross_amount_in_selected_period($db, $start_date, $end_date);
    $smarty->assign('expense_gross_amount_sum', $expense_gross_amount_sum);    

    /* Refunds section */

    // Sum Refunds Net Amount in selected period    
    $smarty->assign('refund_net_amount_sum', sum_refunds_net_amount_in_selected_period($db, $start_date, $end_date));

    // Sum Refunds Tax Amount in selected period    
    $smarty->assign('refund_tax_amount_sum', sum_refunds_tax_amount_in_selected_period($db, $start_date, $end_date));

    // Sum Refunds Gross Amount in selected period    
    $refund_gross_amount_sum = sum_refunds_gross_amount_in_selected_period($db, $start_date, $end_date);
    $smarty->assign('refund_gross_amount_sum', $refund_gross_amount_sum);

    /* invoice section */

    // Sum of Invoice Sub totals (invoice before tax, discounts and shipping is added) in selected period    
    $smarty->assign('invoice_sub_total_sum', sum_of_invoice_sub_totals_before_tax_and_disacounts_are_added_in_selected_period($db, $start_date, $end_date));

    // Sum of discount amounts in selected period    
    $smarty->assign('invoice_discount_sum', sum_of_discount_amounts_in_selected_period($db, $start_date, $end_date));

    // Sum of TAX amounts in selected period   
    $smarty->assign('invoice_tax_sum', sum_of_tax_amounts_in_selected_period($db, $start_date, $end_date));

    // Sum of Invoice Total Amounts (Gross) in selected period
    $invoice_amount_sum = sum_of_invoice_total_amounts_gross_in_selected_period($db, $start_date, $end_date);
    $smarty->assign('invoice_amount_sum', $invoice_amount_sum);


    /* Calculations */
 
    // Taxable profit for the selected period ---->  Taxable Profit = Invoiced - (Expenses - Refunds) 
    $taxable_profit_amount = $invoice_amount_sum - ($expense_gross_amount_sum - $refund_gross_amount_sum);
    $smarty->assign('taxable_profit_amount', $taxable_profit_amount);
    
}

$BuildPage .= $smarty->fetch('report/financial.tpl');