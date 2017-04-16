<?php

require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/invoice.php');



















// Get invoice details
$invoice_details = get_invoice_details($db, $invoice_id);

// Check to see if we are processing more then required
if($invoice_details['0']['BALANCE'] < $gift_amount){
        force_page('core', 'error', 'error_msg=You can not bill more than the amount of the invoice.');
            exit;
    }

/* check if this is a partial payment */
if($invoice_details['INVOICE_AMOUNT'] > $gift_amount){

    if($invoice_details['balance'] > 0 ) {
        $balance = $invoice_details['balance'] - $gift_amount;
    } else {
        $balance = $invoice_details['INVOICE_AMOUNT'] - $gift_amount; 
    }    
    $paid_amount = $gift_amount + $invoice_details['PAID_AMOUNT'];
        $balance = sprintf("%.2f", $balance);
    
    if($balance == 0 ) {
        $flag  = 1;
    } else {
        $flag = 0;
    }

    /* insert Transaction */
    $gift_amount = number_format($gift_amount, 2,'.', '');
    $balance    = number_format($balance, 2,'.', '');
    $memo       = "Partial Gift Certificate Payment Made of $currency_sym$gift_amount, Balance due: $currency_sym$balance, ID: $gift_code";

    $q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
          DATE             = ".$db->qstr(time()).",
          TYPE             = '3',
          INVOICE_ID            = ".$db->qstr($invoice_id).",
          WORKORDER_ID          = ".$db->qstr($workorder_id).",
          CUSTOMER_ID           = ".$db->qstr($customer_id).",
          MEMO             = ".$db->qstr($memo).",
          AMOUNT        = ".$db->qstr($gift_amount);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
    /* update the invoice */    
      if($balance == 0 ) {
            $q = "UPDATE ".PRFX."TABLE_INVOICE SET 
              PAID_DATE      = ".$db->qstr(time()).",
              IS_PAID    = ".$db->qstr($flag).",
              PAID_AMOUNT     = ".$db->qstr($paid_amount).",
              balance     = ".$db->qstr($balance).",
            IS_PAID    ='1' WHERE INVOICE_ID = ".$db->qstr($invoice_id);
    } else {
        $q = "UPDATE ".PRFX."TABLE_INVOICE SET 
              PAID_DATE      = ".$db->qstr(time()).",
              IS_PAID    = ".$db->qstr($flag).",
              PAID_AMOUNT     = ".$db->qstr($paid_amount).",
              balance     = ".$db->qstr($balance)." WHERE INVOICE_ID = ".$db->qstr($invoice_id);
    }

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
    /* update work order */
    $q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
          WORK_ORDER_ID     = ".$db->qstr($workorder_id).",
          DATE              = ".$db->qstr(time()).",
          NOTE              = ".$db->qstr($memo).",
          ENTERED_BY        = ".$db->qstr($_SESSION['login_id']);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }

    /* update if balance = 0 */
        if($balance == 0 ) {
            $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
            WORK_ORDER_STATUS        = '8'            
            WHERE WORK_ORDER_ID         =".$db->qstr($workorder_id);
            if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
            exit;
            }
        }

} else {

    /* full payment made */
    if($invoice_details['INVOICE_AMOUNT'] < $gift_amount) {
            /* update gift amnount and set paid amount full */
            $remain_gift = $gift_amount - $invoice_details['INVOICE_AMOUNT'];
            
            $q = "UPDATE ".PRFX."GIFTCERT SET AMOUNT=".$db->qstr( $remain_gift )." WHERE GIFT_ID=".$db->qstr( $gift_id );
            if(!$rs = $db->execute($q)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
                exit;
            }
            $gift_amount = $invoice_details['INVOICE_AMOUNT'];
            $flag = 1;
    } 

    if($invoice_details['INVOICE_AMOUNT'] = $gift_amount){    
        /* insert Transaction */
        $gift_amount = number_format($gift_amount, 2,'.', '');

        $memo = "Full Gift Certificate Payment Made of $currency_sym$gift_amount, ID: $gift_code";
    
        $q = "INSERT INTO ".PRFX."TABLE_TRANSACTION SET
            DATE             = ".$db->qstr(time()).",
            TYPE             = '3',
            INVOICE_ID              = ".$db->qstr($invoice_id).",
            WORKORDER_ID            = ".$db->qstr($workorder_id).",
            CUSTOMER_ID             = ".$db->qstr($customer_id).",
            MEMO             = ".$db->qstr($memo).",
            AMOUNT            = ".$db->qstr($gift_amount);
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
            exit;
        }
        
        /* update the invoice */    
        $q = "UPDATE ".PRFX."TABLE_INVOICE SET
            PAID_DATE              = ".$db->qstr(time()).", 
            PAID_AMOUNT             = ".$db->qstr($gift_amount).",
            IS_PAID            = '1',
            balance             = ".$db->qstr(0.00)."
            WHERE INVOICE_ID                = ".$db->qstr($invoice_id);
            
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
            exit;
        }
        
        /* update work order */
        $q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
            WORK_ORDER_ID       = ".$db->qstr($workorder_id).",
            DATE                = ".$db->qstr(time()).",
            NOTE                = ".$db->qstr($memo).",
            ENTERED_BY          = ".$db->qstr($_SESSION['login_id']);
        
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
    }
}











