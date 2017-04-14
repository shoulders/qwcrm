<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/invoice.php');
//require(INCLUDES_DIR.'modules/payment.php');
require('modules/payment/include.php');
require(INCLUDES_DIR.'modules/workorder.php');

// check if we have an invoice_id and the retrieve the status
if($invoice_id == '' && $invoice_id != '0') {    
    force_page('core', 'error', 'error_msg=No Invoice ID');
    exit;
}

########################################################
#    Javascript Labour and Parts Deletion Endpoints    #
########################################################

// Delete Invoice Labour item
if(isset($VAR['deleteType']) && $VAR['deleteType'] == 'labourRecord') {

    // Delete the labour record Function call
    if(!delete_invoice_labour_item($db, $VAR['labour_id'])) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        recalculate_invoice_totals($db, $invoice_id);
        //force_page('invoice', 'edit&invoice_id='.$invoice_id);
        //exit;
    }

}

// Parts Delete Record
if(isset($VAR['deleteType']) && $VAR['deleteType'] == 'partsRecord') {

    // Delete the labour record Function call
    if(!delete_invoice_parts_item($db, $VAR['parts_id'])) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        recalculate_invoice_totals($db, $invoice_id);
        //force_page('invoice', 'edit&invoice_id='.$invoice_id);
        //exit;        
    }

}




##################################
#      Update Invoice            #
##################################
if(isset($VAR['submit'])){
        
    // This is all that is need now to update    
    insert_labour_items($db, $invoice_id, $labour_items);
    insert_parts_items($db, $invoice_id, $parts_items);
    update_invoice_small($db, $invoice_id, $VAR['date'], $VAR['due_date'], $VAR['discount_rate']);    
    recalculate_invoice_totals($db, $invoice_id);
    
    
    /* what is this for 
    
    // if discount makes the item free, mark the workorder 'payment made' and the invoice paid
    if( $VAR['discount_rate'] >= 100){
        update_workorder_status($db, $workorder_id, 8);
        transaction_update_invoice($db, $invoice_id, 1, time(), 0, 0);
        
        force_page('invoice', 'edit', 'invoice_id='.$invoice_id);
        exit;        
    
    // Just reload the view/edit page    
    } else {
        
        force_page('invoice', 'edit', 'invoice_id='.$invoice_id);
        exit;
    }
    
    
    */
    
    
    
    
    
    
    


    /* Update and Calculate Invoice */
    
    // consider using invoice[] here to prevent all of the lookups. it can be shard between the 2 sections - does it need to update all of this, transaction and detials
    // some should on be set on creation, keep the update function but create other maybe
    
    // Get customer_id
    //$customer_id = get_invoice_details($db, $invoice_id, 'CUSTOMER_ID');
    
    // Get workorder_id
    //$workorder_id = get_invoice_details($db, $invoice_id, 'WORKORDER_ID');
    
    // Get employee_id
    //$employee_id = get_invoice_details($db, $invoice_id, 'EMPLOYEE_ID');
    
   /* // Date
    $date = date_to_timestamp($VAR['date']);
    
    // Due Date
    $due_date = date_to_timestamp($VAR['due_date']);
    
    // Discount Rate
    //$discount_rate = $VAR['discount_rate'];
    
    
    
    /* Recalculate Totals */ 
    

    // Sub Total
  /*  $sub_total = labour_sub_total($db, $invoice_id) + parts_sub_total($db, $invoice_id);

    // Discount and Rate - if this is a new invoice thene get the discount from the customers profile not the invoice
    $discount_rate = $VAR['discount_rate']; 
    $discount = $sub_total * ($discount_rate / 100); // divide by 100; turns 17.5 in to 0.17575

    // Tax
    //$tax_rate = get_invoice_details($db, $invoice_id, 'TAX_RATE');
    $tax = ($sub_total - $discount) * ((get_invoice_details($db, $invoice_id, 'TAX_RATE')/ 100)); // divide by 100; turns 17.5 in to 0.175

    // Total
    $total = ($sub_total - $discount_amount) + $tax_amount;
    
    // Is Paid?
    //$is_paid = get_invoice_details($db, $invoice_id, 'IS_PAID');
    
    // Paid Amount   
    //$paid_amount = get_invoice_details($db, $invoice_id, 'PAID_AMOUNT');        
    
    // Balance
    $balance = $total - $paid_amount;

    // Update Invoice
    //update_invoice($db, $invoice_id, $customer_id, $workorder_id, $employee_id, $date, $due_date, $sub_total, $discount_rate, $discount, $tax_rate, $tax, $total, $is_paid, $paid_date, $paid_amount, $balance);
    
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
        
            
                
            DATE                =". $db->qstr( $date            ).",
            DUE_DATE            =". $db->qstr( $due_date        ).",
                
            SUB_TOTAL           =". $db->qstr( $sub_total       ).",
            DISCOUNT_RATE       =". $db->qstr( $discount_rate   ).",
            DISCOUNT            =". $db->qstr( $discount        ).",    

            TAX                 =". $db->qstr( $tax             ).",             
            TOTAL               =". $db->qstr( $total           ).",
            BALANCE             =". $db->qstr( $balance         )."            
            
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id      );

    if(!$rs = $db->Execute($sql)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
  */
    
    
    
    
    
    

    
}

    
    
##################################
#     Load invoice edit page     #
################################## 
    
//} else {
    
    $invoice = get_invoice_details($db, $invoice_id);
    
    $smarty->assign('company',              get_company_details($db)                                    );
    $smarty->assign('customer',             display_customer_info($db, $invoice['0']['CUSTOMER_ID'])    );      
    $smarty->assign('invoice',              $invoice                                                    );
    $smarty->assign('labour_rate_items',    get_active_labour_rate_items($db)                           );    
    $smarty->assign('labour_items',         get_invoice_labour_items($db, $invoice_id)                  );
    $smarty->assign('parts_items',          get_invoice_parts_items($db, $invoice_id)                   );
    $smarty->assign('labour_sub_total',     labour_sub_total($db, $invoice_id)                          );
    $smarty->assign('parts_sub_total',      parts_sub_total($db, $invoice_id)                           );
    $smarty->assign('transactions',         get_invoice_transactions($db, $invoice_id)                  ); 
    $smarty->assign('workorder_status',     get_workorder_status($db, $workorder_id)                    ); 
    $smarty->assign('employee_display_name', get_employee_details($db, $invoice['0']['EMPLOYEE_ID'],'EMPLOYEE_DISPLAY_NAME')    );
    
    // temp these are needed for the record deltion routines - consider making all fields editable
    $smarty->assign('workorder_id',             $invoice['0']['WORKORDER_ID']                           );
    $smarty->assign('customer_id',              $invoice['0']['CUSTOMER_ID']                            );
    
    // Fetch Page
    $BuildPage .= $smarty->fetch('invoice/edit.tpl');
//}

##################################
# If We have a Submit2           #  // something to do with the paypal payment method
##################################

if(isset($submit2) && $workorder_id != '0'){    
    update_workorder_status($db, $workorder_id, 8);
}