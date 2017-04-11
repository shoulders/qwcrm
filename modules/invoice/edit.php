<?php

require_once ('include.php');
require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/workorder.php');
require('modules/payment/include.php');

// check if we have a workorder_id and the retrieve the status
if($workorder_id == '' && $workorder_id != '0') {    
    force_page('core', 'error&error_msg=No Work Order ID');
} else {
    $smarty->assign('wo_status', get_workorder_status($db, $workorder_id)); 
}

// check if we have a customer_id and the retrieve the details
if($customer_id == '' || $customer_id == '0'){
        force_page('core', 'error&error_msg=No Customer ID - remove this check no customer id should be passed - invoice:edit - &menu=1');
        exit;
} else {
    $smarty->assign('customer_details', display_customer_info($db, $customer_id));
}


##################################
# Insert new invoice record      #
##################################
if(isset($submit)){
    
    if($VAR['invoice_id'] == ''){
        force_page('core', 'error&error_msg=No Invoice ID');
    }
        
    // Invoice Date
    $date = date_to_timestamp($VAR['date']);
    
    // Invoice Due Date
    $due_date = date_to_timestamp($VAR['due_date']);
        
    // Insert Labor into database (if set)
    if($VAR['labour_hour'] > 0 ) {
        
        $i = 1;
        
        $sql = "INSERT INTO ".PRFX."TABLE_INVOICE_LABOR (INVOICE_ID, EMPLOYEE_ID, INVOICE_LABOR_DESCRIPTION, INVOICE_LABOR_RATE, INVOICE_LABOR_UNIT, INVOICE_LABOR_SUBTOTAL) VALUES ";
        
        foreach($VAR['labour_hour'] as $key => $hours) {
            
            $sql .="(".
                    
                    $db->qstr( $VAR['invoice_id']               ).",".
                    $db->qstr( $login_id                        ).",".
                    $db->qstr( $VAR['labour_description'][$i]   ).",".
                    $db->qstr( $VAR['labour_rate'][$i]          ).",".
                    $db->qstr( $hours                           ).",".
                    $db->qstr( $hours * $VAR['labour_rate'][$i] ).
                    
                    "),";
            
            $i++;
            
        }
        
        // Strips off last comma as this is a joined SQL statement
        $sql = substr($sql , 0, -1);
        
        if(!$rs = $db->Execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
            exit;
            }
        }
    
    // Insert Parts into database (if set)
    if($VAR['parts_qty'] > 0 ) {
        
        $i = 1;
        
        $sql = "INSERT INTO ".PRFX."TABLE_INVOICE_PARTS (INVOICE_ID, INVOICE_PARTS_DESCRIPTION, INVOICE_PARTS_AMOUNT, INVOICE_PARTS_COUNT, INVOICE_PARTS_SUBTOTAL) VALUES ";
        
        foreach($VAR['parts_qty'] as $key=>$num_parts) {
            
            $sql .="(".
                    
                    $db->qstr( $VAR['invoice_id']                   ).",".                    
                    $db->qstr( $VAR['parts_description'][$i]        ).                    
                    $db->qstr( $VAR['parts_price'][$i]              ).",".
                    $db->qstr( $num_parts                           ).", ".
                    $db->qstr( $num_parts * $VAR['parts_price'][$i] ).
                    
                    "),";
            
            $i++;
            
        }
        
        // Strips off last comma as this is a joined SQL statement
        $sql = substr($sql ,0,-1);
        
        if(!$rs = $db->Execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
            exit;
        }
        
    }

    /* Update and Calculate Invoice */

    // Calculate Sub Total
    $sub_total = labour_sub_total_sum ($db, $VAR['invoice_id'])+ parts_sub_total_sum ($db, $VAR['invoice_id']);

    // Calculate Discount
    if(empty($VAR['discount'])) {        
        $discount = display_customer_info($db, $customer_id, 'DISCOUNT');
    } else {
        $discount_rate = $VAR['discount'];
    }
    $discount_rate = $discount_rate / 100; // turns 17.5 in to 0.175
    $discount_amount = $sub_total * $discount_rate;

    // Calculate Tax
    $tax_rate = get_company_details($db,'INVOICE_TAX_RATE') / 100; // turns 17.5 in to 0.175
    $tax_amount = ($sub_total - $discount_amount) * $tax_rate;

    // Calculate Totals
    $invoice_total = $sub_total - $discount_amount + $tax_amount;

    // Calculate Balance - Prevents resubmissions balance errors
    if (!isset ($paid_amount)) {        
        $paid_amount = get_invoice_details($db, $invoice_id, 'PAID_AMOUNT');        
    }
    $invoice_balance = $invoice_total - $paid_amount;

    // update invoice
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
            INVOICE_DATE        =". $db->qstr( $date                                            ).",
            CUSTOMER_ID         =". $db->qstr( $customer_id                                     ).",
            EMPLOYEE_ID         =". $db->qstr( $login_id                                        ).",
            DISCOUNT            =". $db->qstr( number_format($discount_amount, 2,'.', '')       ).",
            SUB_TOTAL           =". $db->qstr( number_format($sub_total, 2,'.', '')             ).",
            INVOICE_AMOUNT      =". $db->qstr( number_format($invoice_total, 2,'.', '')         ).",
            TAX_RATE            =". $db->qstr( number_format($tax, 3,'.', '')                   ).",
            DISCOUNT_APPLIED    =". $db->qstr( number_format($discount_rate * 100, 2, '.', '')  ).",
            BALANCE             =". $db->qstr( number_format($invoice_balance, 2, '.', '')      ).",
            TAX                 =". $db->qstr( number_format($tax_amount, 2,'.', '')            ).",
            INVOICE_DUE         =". $db->qstr( $due_date                                        )." 
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id                                      );

    if(!$rs = $db->Execute($sql)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
    // if discount makes the item free, mark the workorder 'payment made' and the invoice paid
    if( $VAR['discount'] >= 100){
        update_workorder_status($db, $workorder_id, 8);
        transaction_update_invoice($db, $invoice_id, 1, time(), 0, 0);
    }

    // reload the invoice page
    force_page('invoice', 'edit', 'workorder_id='.$workorder_id.'&customer_id='.$customer_id.'&invoice_id='.$VAR['invoice_id']);


    
    
##################################
#  Load create new invoice page  #
##################################  
} else {
    
    // if no invoice exists for this workorder_id or it is an 'invoice only'
    if(!check_workorder_has_invoice($db, $workorder_id) || ($workorder_id == '0' && $VAR['invoice_type'] == 'invoice-only')) {

        // inserts the invoice and returns the new invoice_id
        $invoice_id = insert_invoice($db, $customer_id, $workorder_id, $amount_paid, $invoice_total);

        // When invoices have attached work orders, Update Work Order status and record invoice created
        if($count == 0 && $workorder_id > 0) {            
            insert_new_workorder_history_note($db, $workorder_id, 'Invoice Created ID: '.$invoice_id);            
        }
        
        // Reload 
        force_page('invoice', 'edit', 'workorder_id='.$workorder_id.'&customer_id='.$customer_id.'&invoice_id='.$invoice_id);
        exit;
        

    // if an invoice exists for this workorder_id, Load invoice data and employee display name
    } elseif($count == 1 || ($workorder_id == '0' && $invoice_id != '')) {
        
        $sql = "SELECT ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
            FROM ".PRFX."TABLE_INVOICE
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
            WHERE INVOICE_ID=".$invoice_id;

        $rs = $db->execute($sql);
        $invoice = $rs->FetchRow();

        if($invoice['INVOICE_PAID'] == 1) {
            force_page('invoice', "view&invoice_id=".$invoice['INVOICE_ID']."&page_title=Invoice&customer_id=".$invoice['CUSTOMER_ID']);
            exit;
        }

    // if more than 1 invoice exists with the same work order id that is not work order id 0
    } elseif($count > 1 && $workorder_id > 0) {
        force_page("core", "error&error_msg=Duplicate Invoice's. - WO has more than 1 Invoice");
        exit;
    }

    // add } else { here for another error ie undefined to allow fail gracefully


    // Get any labour details    
    $labour = get_invoice_labour_details($db, $invoice_id);
    if(empty($labour)){
        $smarty->assign('labour', 0);
    } else {
        $smarty->assign('labour', $labour);
    }

    // Get any parts details   
    $parts = get_invoice_parts_details($db, $invoice_id);
    if(empty($parts)){
        $smarty->assign('parts', 0);
    } else {
        $smarty->assign('parts', $parts);
    }

    // load transactions    
    $smarty->assign('trans', get_invoice_transactions($db, $invoice_id));

    // load active labour rate items    
    $smarty->assign('rate', get_active_labour_rate_items($db));

    // Assign company information    
    $smarty->assign('company', get_company_details($db));
    $smarty->assign('invoice', $invoice);

    // Sub_total results    
    $smarty->assign('labour_sub_total_sum', labour_sub_total_sum($db, $invoice['INVOICE_ID']));
    $smarty->assign('parts_sub_total_sum', parts_sub_total_sum($db, $invoice['INVOICE_ID']));

    $BuildPage .= $smarty->fetch('invoice'.SEP.'edit.tpl');

    // if discount makes the item free, mark the workorder 'payment made' and the invoice paid
    if($VAR['discount'] >= 100) {
        update_workorder_status($db, $workorder_id, 8);
        transaction_update_invoice($db, $invoice_id, 1, time(), 0, 0);
    }

}

##################################
# If We have a Submit2           #
##################################

if(isset($submit2) && $workorder_id != '0'){    
    update_workorder_status($db, $workorder_id, 8);
}