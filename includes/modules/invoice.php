<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/** Workorder Include File **/

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records - linked tables
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update - no table linking
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

/** Mandatory Code **/

/** Display Functions **/

#########################################
#     Display Invoices                  # // Status = IS_PAID  0 = unpaid, 1 = paid
#########################################

function display_invoices($db, $status = 'all', $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $employee_id = null, $customer_id = null) {

    global $smarty;
    
    /* Filter the Records */
    
    // Status Restriction
    if($status != 'all') {
        // Restrict by status
        $whereTheseRecords = " WHERE ".PRFX."TABLE_INVOICE.IS_PAID=".$db->qstr($status);       
    } else {            
        // Do not restrict by status
        $whereTheseRecords = " WHERE ".PRFX."TABLE_INVOICE.INVOICE_ID = *";
    } 

    // Restrict by Employee
    if($employee_id != null){
        $whereTheseRecords .= " AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID=".$db->qstr($employee_id);
    }        

    // Restrict by Customer
    if($customer_id != null){
        $whereTheseRecords .= " AND ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID=".$db->qstr($customer_id);
    }
    
    /* The SQL code */
    
    $sql = "SELECT
        ".PRFX."TABLE_EMPLOYEE.     *,
        ".PRFX."TABLE_CUSTOMER.     CUSTOMER_DISPLAY_NAME, CUSTOMER_ADDRESS, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP, CUSTOMER_PHONE, CUSTOMER_WORK_PHONE, CUSTOMER_MOBILE_PHONE, CUSTOMER_EMAIL, CUSTOMER_TYPE, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME, CREATE_DATE, LAST_ACTIVE,
        ".PRFX."TABLE_INVOICE.      *
        FROM ".PRFX."TABLE_INVOICE
        LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
        LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_INVOICE.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID".
        $whereTheseRecords.
        " GROUP BY ".PRFX."TABLE_INVOICE.INVOICE_ID".            
        " ORDER BY ".PRFX."TABLE_INVOICE.INVOICE_ID ".$direction;
            
    /* Restrict by pages */
    
    if($use_pages == true) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_invoice_error_message_function_'.__FUNCTION__.'_count'));
            exit;
        } else {        
            $total_results = $rs->RecordCount();            
            $smarty->assign('total_results', $total_results);
        } 
        
        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results / $records_per_page);
        $smarty->assign('total_pages', $total_pages);

        // Set the page number
        $smarty->assign('page_no', $page_no);

        // Assign the Previous page
        if($page_no > 1){
            $prev = ($page_no - 1);
            $smarty->assign('previous', $prev);
        } 
        
        // Assign the next page
        if($page_no < $total_pages){
            $next = ($page_no + 1);
            $smarty->assign('next', $next);
        }  
        
        // Only return the given page's records
        $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;
        
        // add the restriction on to the SQL
        $sql .= $limitTheseRecords;
        
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }

    /* Return the records */
         
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_invoice_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}


/** New/Insert Functions  **/

#####################################
#     create invoice                #
#####################################

function create_invoice($db, $customer_id, $workorder_id, $discount_rate, $tax_rate) {
    
    $sql = "INSERT INTO ".PRFX."TABLE_INVOICE SET
            
            CUSTOMER_ID     =". $db->qstr( $customer_id             ).",
            WORKORDER_ID    =". $db->qstr( $workorder_id            ).",
            EMPLOYEE_ID     =". $db->qstr( $_SESSION['login_id']    ).",
            DATE            =". $db->qstr( time()                   ).",
            DUE_DATE        =". $db->qstr( time()                   ).",            
            DISCOUNT_RATE   =". $db->qstr( $discount_rate           ).",            
            TAX_RATE        =". $db->qstr( $tax_rate                );            
            
    /*
            CUSTOMER_ID     =". $db->qstr( $customer_id             ).",
            WORKORDER_ID    =". $db->qstr( $workorder_id            ).",
            EMPLOYEE_ID     =". $db->qstr( $_SESSION['login_id']    ).",
            DATE            =". $db->qstr( time()                   ).",
            DUE_DATE        =". $db->qstr( time()                   ).",
            SUB_TOTAL       =". $db->qstr( 0                        ).",
            DISCOUNT_RATE   =". $db->qstr( $discount_rate           ).",
            DISCOUNT        =". $db->qstr( 0                        ).",
            TAX_RATE        =". $db->qstr( $tax_rate                ).",
            TAX             =". $db->qstr( 0                        ).",
            TOTAL           =". $db->qstr( 0                        ).",
            IS_PAID         =". $db->qstr( 0                        ).",
            PAID_AMOUNT     =". $db->qstr( 0                        ).",
            BALANCE         =". $db->qstr( 0                        );
     */

    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
        
    return $db->insert_id();
    
}

#####################################
#     Insert Labour Items           #
#####################################

function insert_labour_items($db, $invoice_id, $labour_description, $labour_rate, $labour_hour) {
    
    // Insert Labour Items into database (if any)
    if($labour_hour > 0 ) {
        
        $i = 1;
        
        $sql = "INSERT INTO ".PRFX."TABLE_INVOICE_LABOUR (INVOICE_ID, INVOICE_LABOUR_DESCRIPTION, INVOICE_LABOUR_RATE, INVOICE_LABOUR_UNIT, INVOICE_LABOUR_SUBTOTAL) VALUES ";
        
        foreach($labour_hour as $key) {
            
            $sql .="(".
                    
                    $db->qstr( $invoice_id                          ).",".                    
                    $db->qstr( $labour_description[$i]              ).",".
                    $db->qstr( $labour_rate[$i]                     ).",".
                    $db->qstr( $labour_hour[$i]                     ).",".
                    $db->qstr( $labour_hour[$i] * $labour_rate[$i]  ).
                    
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
        
}

#####################################
#     Insert Parts Items            #
#####################################

function insert_parts_items($db, $invoice_id, $parts_description, $parts_price, $parts_qty) {
    
    // Insert Parts Items into database (if any)
    if($parts_qty > 0 ) {
        
        $i = 1;
        
        $sql = "INSERT INTO ".PRFX."TABLE_INVOICE_PARTS (INVOICE_ID, INVOICE_PARTS_DESCRIPTION, INVOICE_PARTS_AMOUNT, INVOICE_PARTS_COUNT, INVOICE_PARTS_SUBTOTAL) VALUES ";
        
        foreach($parts_qty as $key) {
            
            $sql .="(".
                    
                    $db->qstr( $invoice_id                          ).",".                    
                    $db->qstr( $parts_description[$i]               ).",".                  
                    $db->qstr( $parts_price[$i]                     ).",".
                    $db->qstr( $parts_qty[$i]                       ).",".
                    $db->qstr( $parts_qty[$i] * $parts_price[$i]    ).
                    
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

}

/** Get Functions **/

#####################################
#   Get invoice details             #
#####################################

function get_invoice_details($db, $invoice_id, $item = null) {
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID =".$db->qstr($invoice_id);
    
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
#   Get Labour Rate Items           #
#####################################

function get_active_labour_rate_items($db) {
    
    $sql = "SELECT * FROM ".PRFX."TABLE_LABOUR_RATE WHERE LABOUR_RATE_ACTIVE='1'";
    $rs = $db->execute($sql);
    if(!empty($rs)) {
        return $rs->GetArray();
    }
    
}

#####################################
#   Get invoice labour details      #
#####################################

function get_invoice_labour_items($db, $invoice_id) {
    
    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE_LABOUR WHERE INVOICE_ID=".$db->qstr( $invoice_id );
    $rs = $db->execute($sql);
    
    if(!empty($rs)) {
        return $rs->GetArray();
    }
    
}

#####################################
#   Get invoice parts details       #
#####################################

function get_invoice_parts_items($db, $invoice_id) {
    
    $sql = "SELECT * FROM ".PRFX."TABLE_INVOICE_PARTS WHERE INVOICE_ID=".$db->qstr( $invoice_id );
    $rs = $db->execute($sql);
    
    if(!empty($rs)) {
        return $rs->GetArray();
    }
    
}

/** Update Functions **/

function update_invoice_small($db, $invoice_id, $date, $due_date, $discount_rate) {
    
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
            DATE                =". $db->qstr( date_to_timestamp($date)     ).",
            DUE_DATE            =". $db->qstr( date_to_timestamp($due_date) ).",
            DISCOUNT_RATE       =". $db->qstr( $discount_rate               )."
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id                  );

    if(!$rs = $db->Execute($sql)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
}

########################################################
#   update invoice after a transaction has been added  #
########################################################

function update_invoice_transaction_only($db, $invoice_id, $paid_status, $paid_date, $paid_amount, $balance) {
    
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
            IS_PAID             =". $db->qstr( $paid_status ).",
            PAID_DATE           =". $db->qstr( $paid_date   ).",        
            PAID_AMOUNT         =". $db->qstr( $paid_amount ).",                    
            BALANCE             =". $db->qstr( $balance     )."
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id  );

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
}

#####################################
#     update invoice (full)         # // not used anywhere
#####################################

function update_invoice_full($db, $invoice_id, $customer_id, $workorder_id, $employee_id, $date, $due_date, $discount_rate, $discount, $tax_rate, $tax_amount, $sub_total, $total, $is_paid, $paid_date, $paid_amount, $balance) {
    
    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET
        
            CUSTOMER_ID         =". $db->qstr( $customer_id     ).",
            WORKORDER_ID        =". $db->qstr( $workorder_id    ).",
            EMPLOYEE_ID         =". $db->qstr( $employee_id     ).",
                
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

/** Close Functions **/

/** Delete Functions **/

#####################################
#   Delete Invoice                  #
#####################################

function delete_invoice($db, $invoice_id)
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
#   Delete Labour Record            #
#####################################

function delete_invoice_labour_item($db, $labour_id)
{
    $sql = "DELETE FROM " . PRFX . "TABLE_INVOICE_LABOUR WHERE INVOICE_LABOUR_ID=" . $db->qstr($labour_id);

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

function delete_invoice_parts_item($db, $parts_id)
{
    $sql = "DELETE FROM " . PRFX . "TABLE_INVOICE_PARTS WHERE INVOICE_PARTS_ID=" . $db->qstr($parts_id);

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }

}

/** Other Functions **/

#####################################
#   Sum Labour Sub Totals           #
#####################################

function labour_sub_total($db, $invoice_id) {
    
    $sql = "SELECT SUM(INVOICE_LABOUR_SUBTOTAL) AS labour_sub_total_sum FROM " . PRFX . "TABLE_INVOICE_LABOUR WHERE INVOICE_ID=" . $db->qstr($invoice_id);
    if (!$rs = $db->Execute($sql)) {
        echo 'Error: ' . $db->ErrorMsg();
        die;
    }
    return $rs->fields['labour_sub_total_sum'];
    
}

#####################################
#   Sum Parts Sub Total             #
#####################################

function parts_sub_total($db, $invoice_id) {
    
    $sql = "SELECT SUM(INVOICE_PARTS_SUBTOTAL) AS parts_sub_total_sum FROM " . PRFX . "TABLE_INVOICE_PARTS WHERE INVOICE_ID=" . $db->qstr($invoice_id);
    if (!$rs = $db->Execute($sql)) {
        echo 'Error: ' . $db->ErrorMsg();
        die;
    }
    return  $rs->fields['parts_sub_total_sum'];
  
}

#####################################
#   Recalculate Invoice Totals      #
#####################################

function recalculate_invoice_totals($db, $invoice_id) {    
    
    $sub_total = labour_sub_total($db, $invoice_id) + parts_sub_total($db, $invoice_id);    
    $discount_rate = get_invoice_details($db, $invoice_id, 'DISCOUNT_RATE');
    $discount = $sub_total * ($discount_rate / 100); // divide by 100; turns 17.5 in to 0.17575
    $tax = ($sub_total - $discount) * ((get_invoice_details($db, $invoice_id, 'TAX_RATE')/ 100)); // divide by 100; turns 17.5 in to 0.175  
    $total = ($sub_total - $discount) + $tax;    
    $balance = $total - $paid_amount = get_invoice_details($db, $invoice_id, 'PAID_AMOUNT');

    $sql = "UPDATE ".PRFX."TABLE_INVOICE SET        
            SUB_TOTAL           =". $db->qstr( $sub_total       ).",            
            DISCOUNT            =". $db->qstr( $discount        ).",
            TAX                 =". $db->qstr( $tax             ).",             
            TOTAL               =". $db->qstr( $total           ).",
            BALANCE             =". $db->qstr( $balance         )."            
            WHERE INVOICE_ID    =". $db->qstr( $invoice_id      );

    if(!$rs = $db->Execute($sql)){
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
        exit;
    }
    
}

###################################
#  Does invoice have a workorder  #
###################################

function check_invoice_has_workorder($db, $invoice_id) {
    
    global $smarty;
    
    $sql = "SELECT WORKORDER_ID FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        
        $temp = $rs->Fields('WORKORDER_ID');
        
        if($temp == 0) {
            
            return false;
            
        } else {          
            
            return true;
            
        }
        
    }
    
}