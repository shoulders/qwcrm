<?php

require_once ('include.php');
if(!xml2php('stats')) {
	$smarty->assign('error_msg',"Error in language file");
}

if(isset($VAR['submit'])){

// General Section

                        /* Change dates to proper timestamps */
                        $start_date = date_to_timestamp($db, $VAR['start_date']);
                        $smarty->assign('start_date', $start_date);

                        $end_date = date_to_timestamp($db, $VAR['end_date']);
                        $smarty->assign('end_date', $end_date);

                        /* Count open work orders in selected period */
                        $q = "SELECT count(*) AS count FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_OPEN_DATE  >= '$start_date' AND WORK_ORDER_OPEN_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $wo_opened = $rs->fields['count'];
                        $smarty->assign('wo_opened', $wo_opened);

                        /* Count closed work orders in selected period */
                        $q = "SELECT count(*) AS count FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_CLOSE_DATE  >= '$start_date' AND WORK_ORDER_CLOSE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $wo_closed = $rs->fields['count'];
                        $smarty->assign('wo_closed', $wo_closed);

                        /* Count New Customers in selected period */
                        $q = "SELECT count(*) AS count FROM ".PRFX."TABLE_CUSTOMER WHERE CREATE_DATE  >= '$start_date' AND CREATE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $new_customers = $rs->fields['count'];
                        $smarty->assign('new_customers', $new_customers);

                        /* Count Total Customers in MyITCRM */
                        $q = "SELECT COUNT(*) AS count FROM ".PRFX."TABLE_CUSTOMER";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $total_customers = $rs->fields['count'];
                        $smarty->assign('total_customers', $total_customers);

                        /* Count Created Invoices in selected period */
                        // $q = "SELECT count(*) AS count FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date'";
                        $q = "SELECT count(*) AS count FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $new_invoices = $rs->fields['count'];
                        $smarty->assign('new_invoices', $new_invoices);

                        /* Count Paid Invoices in selected period */
                        //$q = "SELECT count(*) AS count FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date' AND INVOICE_PAID = 1";
                        $q = "SELECT count(*) AS count FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date' AND INVOICE_PAID = 1";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $paid_invoices = $rs->fields['count'];
                        $smarty->assign('paid_invoices' , $paid_invoices);

// Parts Section

                        /* Count Total number of different part items ordered */
                        $q = "SELECT COUNT(*) AS parts_different_items_count FROM ".PRFX."TABLE_INVOICE_PARTS INNER JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_INVOICE.INVOICE_ID = ".PRFX."TABLE_INVOICE_PARTS.INVOICE_ID WHERE ".PRFX."TABLE_INVOICE.INVOICE_DATE >= '$start_date' AND ".PRFX."TABLE_INVOICE.INVOICE_DATE <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $parts_different_items_count = $rs->fields['parts_different_items_count'];
                        $smarty->assign('parts_different_items_count', $parts_different_items_count);

                        /* Sum Total quantity of part items ordered */
                        $q = "SELECT SUM(INVOICE_PARTS_COUNT) AS parts_items_sum FROM ".PRFX."TABLE_INVOICE_PARTS INNER JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_INVOICE.INVOICE_ID = ".PRFX."TABLE_INVOICE_PARTS.INVOICE_ID WHERE ".PRFX."TABLE_INVOICE.INVOICE_DATE >= '$start_date' AND ".PRFX."TABLE_INVOICE.INVOICE_DATE <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $parts_items_sum = $rs->fields['parts_items_sum'];
                        $smarty->assign('parts_items_sum', $parts_items_sum);

                        /* Sum Parts Sub Total */
                        $q = "SELECT SUM(INVOICE_PARTS_SUBTOTAL) AS parts_sub_total_sum FROM ".PRFX."TABLE_INVOICE_PARTS INNER JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_INVOICE.INVOICE_ID = ".PRFX."TABLE_INVOICE_PARTS.INVOICE_ID WHERE ".PRFX."TABLE_INVOICE.INVOICE_DATE >= '$start_date' AND ".PRFX."TABLE_INVOICE.INVOICE_DATE <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $parts_sub_total_sum = $rs->fields['parts_sub_total_sum'];
                        $smarty->assign('parts_sub_total_sum', $parts_sub_total_sum);

// Labour Section

                        /* Count Total number of different  labour items */
                        $q = "SELECT COUNT(*) AS labour_different_items_count FROM ".PRFX."TABLE_INVOICE_LABOR INNER JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_INVOICE.INVOICE_ID = ".PRFX."TABLE_INVOICE_LABOR.INVOICE_ID WHERE ".PRFX."TABLE_INVOICE.INVOICE_DATE >= '$start_date' AND ".PRFX."TABLE_INVOICE.INVOICE_DATE <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $labour_different_items_count = $rs->fields['labour_different_items_count'];
                        $smarty->assign('labour_different_items_count', $labour_different_items_count);

                         /* Sum Total quantity of labour items */
                        $q = "SELECT SUM(INVOICE_LABOR_UNIT) AS labour_items_sum FROM ".PRFX."TABLE_INVOICE_LABOR INNER JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_INVOICE.INVOICE_ID = ".PRFX."TABLE_INVOICE_LABOR.INVOICE_ID WHERE ".PRFX."TABLE_INVOICE.INVOICE_DATE >= '$start_date' AND ".PRFX."TABLE_INVOICE.INVOICE_DATE <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $labour_items_sum = $rs->fields['labour_items_sum'];
                        $smarty->assign('labour_items_sum', $labour_items_sum);

                        /* Sum Labour Sub Totals */
                        $q = "SELECT SUM(INVOICE_LABOR_SUBTOTAL) AS labour_sub_total_sum FROM ".PRFX."TABLE_INVOICE_LABOR INNER JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_INVOICE.INVOICE_ID = ".PRFX."TABLE_INVOICE_LABOR.INVOICE_ID WHERE ".PRFX."TABLE_INVOICE.INVOICE_DATE >= '$start_date' AND ".PRFX."TABLE_INVOICE.INVOICE_DATE <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $labour_sub_total_sum = $rs->fields['labour_sub_total_sum'];
                        $smarty->assign('labour_sub_total_sum', $labour_sub_total_sum);

// Expense Section

                        // Sum Expenses Net Amount
                        $q = "SELECT SUM(EXPENSE_NET_AMOUNT) AS expense_net_amount_sum FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_DATE  >= '$start_date' AND EXPENSE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $expense_net_amount_sum = $rs->fields['expense_net_amount_sum'];
                        $smarty->assign('expense_net_amount_sum', $expense_net_amount_sum);

                        // Sum Expenses Tax Amount
                        $q = "SELECT SUM(EXPENSE_TAX_AMOUNT) AS expense_tax_amount_sum FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_DATE  >= '$start_date' AND EXPENSE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $expense_tax_amount_sum = $rs->fields['expense_tax_amount_sum'];
                        $smarty->assign('expense_tax_amount_sum', $expense_tax_amount_sum);

                        // Sum Expenses Gross Amount
                        $q = "SELECT SUM(EXPENSE_GROSS_AMOUNT) AS expense_gross_amount_sum FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_DATE  >= '$start_date' AND EXPENSE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $expense_gross_amount_sum = $rs->fields['expense_gross_amount_sum'];
                        $smarty->assign('expense_gross_amount_sum', $expense_gross_amount_sum);

// refunds section

                        // Sum Refunds Net Amount
                        $q = "SELECT SUM(REFUND_NET_AMOUNT) AS refund_net_amount_sum FROM ".PRFX."TABLE_REFUND WHERE REFUND_DATE  >= '$start_date' AND REFUND_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $refund_net_amount_sum = $rs->fields['refund_net_amount_sum'];
                        $smarty->assign('refund_net_amount_sum', $refund_net_amount_sum);

                        // Sum Refunds Tax Amount
                        $q = "SELECT SUM(REFUND_TAX_AMOUNT) AS refund_tax_amount_sum FROM ".PRFX."TABLE_REFUND WHERE REFUND_DATE  >= '$start_date' AND REFUND_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $refund_tax_amount_sum = $rs->fields['refund_tax_amount_sum'];
                        $smarty->assign('refund_tax_amount_sum', $refund_tax_amount_sum);

                        // Sum Refunds Gross Amount
                        $q = "SELECT SUM(REFUND_GROSS_AMOUNT) AS refund_gross_amount_sum FROM ".PRFX."TABLE_REFUND WHERE REFUND_DATE  >= '$start_date' AND REFUND_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $refund_gross_amount_sum = $rs->fields['refund_gross_amount_sum'];
                        $smarty->assign('refund_gross_amount_sum', $refund_gross_amount_sum);

// invoice section

                        //Sum of Invoice Sub totals (invoice before tax, discounts and shipping is added)
                        // $q = "SELECT SUM(SUB_TOTAL) AS invoice_sub_total_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date'";
                        $q = "SELECT SUM(SUB_TOTAL) AS invoice_sub_total_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $invoice_sub_total_sum = $rs->fields['invoice_sub_total_sum'];
                        //$rev_invoices = $sum_invoices - $discounts;
                        $smarty->assign('invoice_sub_total_sum', $invoice_sub_total_sum);

                        // Sum of discount amounts
                        //$q = "SELECT SUM(DISCOUNT) AS invoice_discount_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date'";
                        $q = "SELECT SUM(DISCOUNT) AS invoice_discount_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $invoice_discount_sum = $rs->fields['invoice_discount_sum'];
                        $smarty->assign('invoice_discount_sum', $invoice_discount_sum);

                      // Sum of shipping amounts
                      //$q = "SELECT SUM(SHIPPING) AS invoice_shipping_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date'";
                        $q = "SELECT SUM(SHIPPING) AS invoice_shipping_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $invoice_shipping_sum = $rs->fields['invoice_shipping_sum'];
                        $smarty->assign('invoice_shipping_sum', $invoice_shipping_sum);

                       // Sum of TAX amounts
                       //$q = "SELECT SUM(TAX) AS invoice_tax_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date'";
                        $q = "SELECT SUM(TAX) AS invoice_tax_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $invoice_tax_sum = $rs->fields['invoice_tax_sum'];
                        $smarty->assign('invoice_tax_sum', $invoice_tax_sum);

                        //Sum of Invoice Total Amounts (Gross)
                        //$q = "SELECT SUM(INVOICE_AMOUNT) AS invoice_amount_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DUE  >= '$start_date' AND INVOICE_DUE  <= '$end_date'";
                        $q = "SELECT SUM(INVOICE_AMOUNT) AS invoice_amount_sum FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_DATE  >= '$start_date' AND INVOICE_DATE  <= '$end_date'";
                        if(!$rs = $db->Execute($q)){
                                echo 'Error: '. $db->ErrorMsg();
                                die;
                        }
                        $invoice_amount_sum = $rs->fields['invoice_amount_sum'];
                        //$rev_invoices = $sum_invoices - $discounts;
                        $smarty->assign('invoice_amount_sum', $invoice_amount_sum);


// calculations
// 
                        // Taxable profit ----  Profit = Invoiced - (Expenses - Refunds)
                        $taxable_profit_amount = $invoice_amount_sum - ($expense_gross_amount_sum - $refund_gross_amount_sum);
                        $smarty->assign('taxable_profit_amount', $taxable_profit_amount);
        }

$smarty->display('stats'.SEP.'main.tpl');

?>
