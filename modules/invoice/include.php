<?php

/** Mandatory Code **/


#########################################
#     Display Open Invoices             #
#########################################

function display_open_invoices($db, $page_no) {

    global $smarty;

    // Define the number of results per page
    $max_results = 25;

    // Figure out the limit for the Execute based on the current page number.
    $from = (($page_no * $max_results) - $max_results);

    $sql = "SELECT " . PRFX . "TABLE_INVOICE.*,
            " . PRFX . "TABLE_CUSTOMER. CUSTOMER_DISPLAY_NAME, CUSTOMER_ADDRESS, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP, CUSTOMER_PHONE, CUSTOMER_WORK_PHONE, CUSTOMER_MOBILE_PHONE, CUSTOMER_EMAIL, CUSTOMER_TYPE, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME, CREATE_DATE, LAST_ACTIVE ,
            " . PRFX . "TABLE_EMPLOYEE.*
            FROM " . PRFX . "TABLE_INVOICE
            LEFT JOIN " . PRFX . "TABLE_CUSTOMER ON (" . PRFX . "TABLE_INVOICE.CUSTOMER_ID = " . PRFX . "TABLE_CUSTOMER.CUSTOMER_ID)
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE ON (" . PRFX . "TABLE_INVOICE.EMPLOYEE_ID = " . PRFX . "TABLE_EMPLOYEE.EMPLOYEE_ID)
            WHERE INVOICE_PAID=" . $db->qstr(0) . " ORDER BY INVOICE_ID DESC LIMIT $from, $max_results";

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $invoice_arr = $rs->GetArray();
    }
    
    // Get the total number of records in the database
    $sql = "SELECT COUNT(*) as Num FROM " . PRFX . "TABLE_INVOICE WHERE INVOICE_PAID=" . $db->qstr(0);

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }    
    if (!$total_results = $rs->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }

    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results);
    $smarty->assign('total_pages', $total_pages);

    // Assign the first page
    if ($page_no > 1) {
        $prev = ($page_no - 1);
    }

    // Build Next Link
    if ($page_no < $total_pages) {
        $next = ($page_no + 1);
    }

    // Assign variables
    $smarty->assign('name', $name);
    $smarty->assign('page_no', $page_no);
    $smarty->assign("previous", $prev);
    $smarty->assign("next", $next);
    
    return $invoice_arr;
}

########################################
# Paid Invoices                        #
########################################

function display_paid_invoice($db, $page_no) {

    global $smarty;

    // Define the number of results per page
    $max_results = 25;

    // Figure out the limit for the Execute based on the current page number.
    $from = (($page_no * $max_results) - $max_results);

    $sql =  "SELECT " . PRFX . "TABLE_INVOICE.*,
            " . PRFX . "TABLE_CUSTOMER. CUSTOMER_DISPLAY_NAME, CUSTOMER_ADDRESS, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP, CUSTOMER_PHONE, CUSTOMER_WORK_PHONE, CUSTOMER_MOBILE_PHONE, CUSTOMER_EMAIL, CUSTOMER_TYPE, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME, CREATE_DATE, LAST_ACTIVE ,
            " . PRFX . "TABLE_EMPLOYEE.*
            FROM " . PRFX . "TABLE_INVOICE
            LEFT JOIN " . PRFX . "TABLE_CUSTOMER ON (" . PRFX . "TABLE_INVOICE.CUSTOMER_ID = " . PRFX . "TABLE_CUSTOMER.CUSTOMER_ID)
            LEFT JOIN " . PRFX . "TABLE_EMPLOYEE ON (" . PRFX . "TABLE_INVOICE.EMPLOYEE_ID = " . PRFX . "TABLE_EMPLOYEE.EMPLOYEE_ID)
            WHERE INVOICE_PAID=" . $db->qstr(1) . " ORDER BY INVOICE_ID DESC LIMIT $from, $max_results";

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $invoice_arr = $rs->GetArray();
    }

    // Figure out the total number of results in DB:
    $sql = "SELECT COUNT(*) as Num FROM " . PRFX . "TABLE_INVOICE WHERE INVOICE_PAID=" . $db->qstr(1);
    if (!$results = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }

    if (!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }

    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results);
    $smarty->assign('total_pages', $total_pages);

    // Assign the first page
    if ($page_no > 1) {
        $prev = ($page_no - 1);
    }

    // Build Next Link
    if ($page_no < $total_pages) {
        $next = ($page_no + 1);
    }

    // Assign variables
    $smarty->assign('name', $name);
    $smarty->assign('page_no', $page_no);
    $smarty->assign("previous", $prev);
    $smarty->assign("next", $next);
    
    return $invoice_arr;
    
}


#####################################
#   Delete Labour Record            #
#####################################

$labourID = $VAR['labourID'];

function delete_labour_record($db, $labourID)
{
    $sql = "DELETE FROM " . PRFX . "TABLE_INVOICE_LABOR WHERE INVOICE_LABOR_ID=" . $db->qstr($labourID);

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }
}

#####################################
#   Delete Parts Record             #
#####################################

function delete_parts_record($db, $partsID)
{
    $sql = "DELETE FROM " . PRFX . "TABLE_INVOICE_PARTS WHERE INVOICE_PARTS_ID=" . $db->qstr($partsID);


    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }

}

#####################################
#   Delete Invoice                  #
#####################################

function delete_invoice($db, $invoice_id, $customer_id, $login_usr)
{
      //Actual Deletion Function from Invoice Table
    $sql = "DELETE FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }
    // TODO - Add transaction log to database
/*
    $sql = "INSERT INTO ".PRFX."TABLE_TRANSACTION ( TRANSACTION_ID, DATE, TYPE, INVOICE_ID, WORKORDER_ID, CUSTOMER_ID, MEMO, AMOUNT ) VALUES,
         ( NULL, ".$db->qstr(time()).",'6',".$db->qstr($invoice_id).",'0',".$db->qstr($customer_id).",'Invoice Deleted By ".$db->qstr($login_usr).",'0.00');";

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }*/
    
}
#####################################
#   Sum Labour Sub Totals           #
#####################################

function labour_sub_total_sum($db, $invoiceID)
{
    $sql = "SELECT SUM(INVOICE_LABOR_SUBTOTAL) AS labour_sub_total_sum FROM " . PRFX . "TABLE_INVOICE_LABOR WHERE INVOICE_ID=" . $db->qstr($invoiceID);
    if (!$rs = $db->Execute($sql)) {
        echo 'Error: ' . $db->ErrorMsg();
        die;
    }
    return $rs->fields['labour_sub_total_sum'];
    
}

#####################################
#   Sum Parts Sub Total             #
#####################################

function parts_sub_total_sum($db, $invoiceID)
{
    $sql = "SELECT SUM(INVOICE_PARTS_SUBTOTAL) AS parts_sub_total_sum FROM " . PRFX . "TABLE_INVOICE_PARTS WHERE INVOICE_ID=" . $db->qstr($invoiceID);
    if (!$rs = $db->Execute($sql)) {
        echo 'Error: ' . $db->ErrorMsg();
        die;
    }
    return  $rs->fields['parts_sub_total_sum'];
  
}

#####################################
#   Get invoice details             #
#####################################

function get_invoice_details($db, $invoice_id, $item = null) {
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID =".$invoice_id;
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#####################################
#   Get invoice labour details      #
#####################################

function get_invoice_labour_details($db, $invoice_id) {
    
    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE_LABOR WHERE INVOICE_ID=".$db->qstr( $invoice_id );
    $rs = $db->execute($sql);
    return $rs->GetArray();
}

#####################################
#   Get invoice parts details       #
#####################################

function get_invoice_parts_details($db, $invoice_id) {
    
    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE_PARTS WHERE INVOICE_ID=".$db->qstr( $invoice_id );
    $rs = $db->execute($sql);
    return $rs->GetArray();
}

#####################################
#   Get Labour Rate Items           #
#####################################

function get_active_labour_rate_items($db) {
    
    $sql = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ACTIVE='1'";
    
    $rs = $db->execute($sql);
    return $rs->GetArray();    
}

#####################################
#     create invoice                #
#####################################

function create_invoice($db, $customer_id, $workorder_id, $amount_paid, $invoice_total) {
    
    $sql = "INSERT INTO ".PRFX."TABLE_INVOICE SET
            INVOICE_DATE    =". $db->qstr( time()                   ).",
            CUSTOMER_ID     =". $db->qstr( $customer_id             ).",
            WORKORDER_ID    =". $db->qstr( $workorder_id            ).",
            EMPLOYEE_ID     =". $db->qstr( $_SESSION['login_id']    ).",
            INVOICE_PAID    =". $db->qstr( $amount_paid             ).",
            INVOICE_AMOUNT  =". $db->qstr( $invoice_total           );

    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
        
    return $db->insert_id();
    
}

#####################################
#     update invoice                #
#####################################

function update_invoice($db, $invoice_id, $customer_id, $workorder_id, $date, $due_date, $discount_rate, $discount, $tax_rate, $tax_amount, $sub_total, $total, $is_paid, $paid_date, $paid_amount, $balance) {
    
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
        
            CUSTOMER_ID         =". $db->qstr( $customer_id     ).",
            WORKORDER_ID        =". $db->qstr( $workorder_id    ).",
            EMPLOYEE_ID         =". $db->qstr( $_SESSION['login_id']    ).",
                
            DATE                =". $db->qstr( $date            ).",
            DUE_DATE            =". $db->qstr( $due_date        ).",
                
            SUB_TOTAL           =". $db->qstr( $sub_total       ).",
            DISCOUNT_RATE       =". $db->qstr( $discount_rate   ).",
            DISCOUNT            =". $db->qstr( $discount        ).",    
            TAX_RATE            =". $db->qstr( $tax_rate        ).",
            TAX                 =". $db->qstr( $tax_amount      ).",             
            TOTAL               =". $db->qstr( $total           ).",              
                    
            IS_PAID             =". $db->qstr( $is_paid         ).",
            PAID_DATE           =". $db->qstr( $paid_date       ).",
            PAID_AMOUNT         =". $db->qstr( $paid_amount     ).",
            BALANCE             =". $db->qstr( $balance         )."            
            
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id      );

    if(!$rs = $db->Execute($sql)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }    
    
}

/*
#####################################
#     update invoice                #
#####################################

function update_invoice($db, $invoice_id, $customer_id, $workorder_id, $employee_id, $date, $due_date, $discount_rate, $discount, $tax_rate, $tax_amount, $sub_total, $total, $is_paid, $paid_date, $paid_amount, $balance ) {
    
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
        
            CUSTOMER_ID         =". $db->qstr( $customer_id     ).",
            WORKORDER_ID        =". $db->qstr( $workorder_id    ).",
            EMPLOYEE_ID         =". $db->qstr( $_SESSION['login_id']    ).",
                
            DATE                =". $db->qstr( $date            ).",
            DUE_DATE            =". $db->qstr( $due_date        ).",
                
            SUB_TOTAL           =". $db->qstr( $sub_total       ).",
            DISCOUNT_RATE       =". $db->qstr( $discount_rate   ).",
            DISCOUNT            =". $db->qstr( $discount        ).",    
            TAX_RATE            =". $db->qstr( $tax_rate        ).",
            TAX                 =". $db->qstr( $tax_amount      ).",             
            TOTAL               =". $db->qstr( $total           ).",              
                    
            IS_PAID             =". $db->qstr( $is_paid         ).",
            PAID_DATE           =". $db->qstr( $paid_date       ).",
            PAID_AMOUNT         =". $db->qstr( $paid_amount     ).",
            BALANCE             =". $db->qstr( $balance         )."            
            
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id      );

    if(!$rs = $db->Execute($sql)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }    
    
*/