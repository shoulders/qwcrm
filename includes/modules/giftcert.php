<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

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

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

#########################################
#     Display Gift Certificates         #
#########################################

function display_giftcerts($db, $status, $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $employee_id = null, $customer_id = null, $invoice_id = null) {

    global $smarty;
    
    /* Get invoices restricted by pages */
    
    if($use_pages == true) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of invoices in the database for the given status
        $sql = "SELECT COUNT(*) as Num FROM ".PRFX."GIFTCERT WHERE ACTIVE=" . $db->qstr($status);
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the matching Gift Certificate records."));
            exit;
        } else {        
            $total_results = $rs->FetchRow();
            $smarty->assign('total_results', $total_results['Num']);
        }
        
        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results['Num'] / $records_per_page);
        $smarty->assign('total_pages', $total_pages);

        // Set the page number
        $smarty->assign('page_no', $page_no);

        // Assign the Previous page
        if($page_no > 1) {
            $previous = ($page_no - 1);            
        } else { 
            $previous = 1;            
        }
        $smarty->assign('previous', $previous);        
        
        // Assign the next page
        if($page_no < $total_pages){
            $next = ($page_no + 1);            
        } else {
            $next = $total_pages;
        }
        $smarty->assign('next', $next);
        
        // Restrict the results to the selected status
        $whereTheseRecords = " WHERE ".PRFX."GIFTCERT.ACTIVE=".$db->qstr($status);
        
        // Restrict by Employee
        if($employee_id != null){
            $whereTheseRecords .= " AND ".PRFX."EMPLOYEE.EMPLOYEE_ID=".$db->qstr($employee_id);
        }
        
        // Restrict by Customer
        if($customer_id != null){
            $whereTheseRecords .= " AND ".PRFX."CUSTOMER.CUSTOMER_ID=".$db->qstr($customer_id);
        }
        
        // Restrict by Invoice
        if($invoice_id != null){
            $whereTheseRecords .= " AND ".PRFX."INVOICE.INVOICE_ID=".$db->qstr($customer_id);
        } 
        
        // Only return the given page records
        $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;   
        
    
    /* Get all workorders (unrestricted) */
        
    } else {
        
        // Return all invoices for the selected status (no pages)
        $whereTheseRecords = " WHERE ".PRFX."GIFTCERT.ACTIVE= ".$db->qstr($status);
    }
    
    /* Get the records */

    $sql = "SELECT
            ".PRFX."GIFTCERT.           *,
            ".PRFX."EMPLOYEE.     EMPLOYEE_ID, EMPLOYEE_DISPLAY_NAME,
            ".PRFX."CUSTOMER.     CUSTOMER_ID,
            ".PRFX."INVOICE.      INVOICE_ID           
            FROM ".PRFX."GIFTCERT
            LEFT JOIN ".PRFX."EMPLOYEE ON ".PRFX."GIFTCERT.EMPLOYEE_ID = ".PRFX."EMPLOYEE.EMPLOYEE_ID
            LEFT JOIN ".PRFX."CUSTOMER ON ".PRFX."GIFTCERT.CUSTOMER_ID = ".PRFX."CUSTOMER.CUSTOMER_ID
            LEFT JOIN ".PRFX."INVOICE ON ".PRFX."GIFTCERT.INVOICE_ID = ".PRFX."INVOICE.INVOICE_ID". 
            $whereTheseRecords.
            " GROUP BY ".PRFX."GIFTCERT.GIFTCERT_ID".            
            " ORDER BY ".PRFX."GIFTCERT.GIFTCERT_ID ".$direction.
            $limitTheseRecords;

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching Gift Certificate records."));
        exit;
    } else {      
        
        return $rs->GetArray();
        
    }
    
}

/** New/Insert Functions **/

#################################
#   insert Gift Certificate     #
#################################

function insert_giftcert($db, $customer_id, $date_expires, $giftcert_code, $amount, $note) {
    
    $sql = "INSERT INTO ".PRFX."GIFTCERT SET 
            CUSTOMER_ID     =". $db->qstr( $customer_id             ).",               
            INVOICE_ID      =". $db->qstr( 0                        ).",
            EMPLOYEE_ID     =". $db->qstr( $_SESSION['login_id']    ).",
            DATE_CREATED    =". $db->qstr( time()                   ).",
            DATE_EXPIRES    =". $db->qstr( $date_expires            ).",
            DATE_REDEEMED   =". $db->qstr( 0                        ).",
            IS_REDEEMED     =". $db->qstr( 0                        ).",   
            GIFTCERT_CODE   =". $db->qstr( $giftcert_code           ).",                
            AMOUNT          =". $db->qstr( $amount                  ).",
            ACTIVE          =". $db->qstr( 1                        ).",                
            NOTE            =". $db->qstr( $note                    );

    if(!$db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the Gift Certificate into the database."));
        exit;

    } else {

        return $db->insert_id();
    }
    
}

/** Get Functions **/

##########################
#  Get giftcert details  #
##########################

function get_giftcert_details($db, $giftcert_id, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."GIFTCERT WHERE GIFTCERT_ID=".$db->qstr($giftcert_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the Gift Certificate details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#########################################
#   get giftcert_id by giftcert_code    #
#########################################

function get_giftcert_id_by_gifcert_code($db, $giftcert_code) {
    
    $sql = "SELECT * FROM ".PRFX."GIFTCERT WHERE GIFTCERT_CODE=".$db->qstr( $giftcert_code );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the Gift Certificate ID by the Gift Certificate code."));
        exit;
    }
    
    if($rs->fields['GIFTCERT_ID'] != '') {
        return $rs->fields['GIFTCERT_ID'];
    } else {
        return false;
    }
    
}

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

##############################
#  Delete Gift Certificate   #
##############################

function delete_giftcert($db, $giftcert_id) {     
    
    // update and set non-active as you cannot really delete an issues gift certificate

    $sql = "UPDATE ".PRFX."GIFTCERT SET ACTIVE='0' WHERE GIFTCERT_ID=".$db->qstr($giftcert_id);

    if(!$db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the Gift Certificate."));
        exit;
    } else {

        return;

    }            
        
}

/** Other Functions **/

################################################
#  Validate the Gift Certificate can be used   #
################################################

function validate_giftcert_code($db, $giftcert_id) {

    // check is active
    if(get_giftcert_details($db, $giftcert_id['ACTIVE']) != 1) {
        //force_page('core','error', 'error_msg=This gift certificate is not active');
        //exit;
        return false;
    }

    // check if expired
    if(get_giftcert_details($db, $giftcert_id['DATE_EXPIRES']) < time()) {
        //force_page('core', 'error', 'error_msg=This gift certificate is expired.');
        //exit;
        return false;
    }
    
    return true;
    
}

############################################
#  Generate Random Gift Certificate code   #
############################################

function generate_giftcert_code() {
    
    // generate a random string for the gift certificate
    
    $acceptedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $max_offset = strlen($acceptedChars)-1;
    $giftcert_code = '';
    
    for($i=0; $i < 16; $i++) {
        $giftcert_code .= $acceptedChars{mt_rand(0, $max_offset)};
    }
    
    return $giftcert_code;
    
}

######################################################
#   redeem the gift certificate against an invoice   #
######################################################

function update_giftcert_as_redeemed($db, $giftcert_id, $invoice_id) {
    
    $sql = "UPDATE ".PRFX."GIFTCERT SET
            DATE_REDEEMED       =". $db->qstr( time()       ).",
            IS_REDEEMED         =". $db->qstr( 1            ).",   
            INVOICE_ID          =". $db->qstr( $invoice_id  ).",
            ACTIVE              =". $db->qstr( 0            )."
            WHERE GIFTCERT_ID   =". $db->qstr( $giftcert_id );
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the Gift Certificate as redeemed."));
        exit;
    }
    
}