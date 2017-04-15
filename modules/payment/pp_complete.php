<?php



if($VAR['submit']) {

   
    $invoice = get_invoice_details($db, $invoice_id);   
    $customer_id        = $invoice['CUSTOMER_ID'];
    $workorder_id       = $invoice['WORKORDER_ID'];
    $amount             = $VAR['amount'];

    $memo = "PayPal Payment Made of $currency_sym$amount, PayPal ID ".$VAR['pp_invoice'];

    insert_transaction($db, 5, $invoice_id, $workorder_id, $customer_id, $amount, $memo);

    /* update the invoice */    
    $q = "UPDATE ".PRFX."TABLE_INVOICE SET
        PAID_DATE              = ".$db->qstr(time()).", 
        PAID_AMOUNT             = ".$db->qstr($amount).",
        IS_PAID            = '1',
        BALANCE             = ".$db->qstr(0.00)."
        WHERE INVOICE_ID                = ".$db->qstr($invoice_id);

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    update_invoice_transaction_only($db, $invoice_id, 0, 0, $new_paid_amount, $new_balance);
    
    

    /* update work order */
    $q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
        WORK_ORDER_ID   = ".$db->qstr($workorder_id).",
        DATE            = ".$db->qstr(time()).",
        NOTE            = ".$db->qstr($memo).",
        ENTERED_BY      = ".$db->qstr($_SESSION['login_id']);

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }

    $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
        WORK_ORDER_STATUS        = '8'            
        WHERE WORK_ORDER_ID         =".$db->qstr($workorder_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }

    force_page('invoice', "view&invoice_id=$invoice_id&customer_id=$customer_id");
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    } 