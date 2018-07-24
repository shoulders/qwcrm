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
   
#############################
#     Display refunds       #
#############################

function display_refunds($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $payment_method = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'refund_id';    
    
    /* Records Search */    
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."refund_records.refund_id\n";
    
    // Restrict results by search category and search term
    if($search_term) {$whereTheseRecords .= " AND ".PRFX."refund_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');} 
    
    /* Filter the Records */  
    
    // Restrict by Type
    if($type) { $whereTheseRecords .= " AND ".PRFX."refund_records.type= ".$db->qstr($type);}
        
    // Restrict by Method
    if($payment_method) { $whereTheseRecords .= " AND ".PRFX."refund_records.payment_method= ".$db->qstr($payment_method);} 
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."refund_records                                                   
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."refund_records.".$order_by."
            ORDER BY ".PRFX."refund_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching refund records."));
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
        $previous_page_no = ($page_no - 1);        
        $smarty->assign('previous_page_no', $previous_page_no);          
        
        // Assign the next page        
        if($page_no == $total_pages) {$next_page_no = 0;}
        elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
        else {$next_page_no = $total_pages;}
        $smarty->assign('next_page_no', $next_page_no);
        
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching refund records."));
    } else {
        
        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

##########################################
#      Insert Refund                     #
##########################################

function insert_refund($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."refund_records SET
            invoice_id       =". $db->qstr( $VAR['invoice_id']              ).",
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",
            type             =". $db->qstr( $VAR['type']                    ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            note             =". $db->qstr( $VAR['note']                    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the refund record into the database."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$db->Insert_ID().' '._gettext("created.");
        write_record_to_activity_log($record, null, null, null, $VAR['invoice_id']);
        
        return $db->Insert_ID();
        
    } 
    
}

/** Get Functions **/

##########################
#   Get refund details   #
##########################

function get_refund_details($refund_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_records WHERE refund_id=".$db->qstr($refund_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the refund details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Refund Types               #
#####################################

function get_refund_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get refund types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update refund                 #
#####################################

function update_refund($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."refund_records SET
            invoice_id       =". $db->qstr( $VAR['invoice_id']              ).",
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",
            type             =". $db->qstr( $VAR['type']                    ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            note             =". $db->qstr( $VAR['note']                    )."
            WHERE refund_id  =". $db->qstr( $VAR['refund_id']               );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the refund details."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$VAR['refund_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, null, null, null, $VAR['invoice_id']);
        
        return true;
      
    }
    
} 

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_refund($refund_id) {
    
    $db = QFactory::getDbo();
    
    // Get invoice_id before deleting the record
    $invoice_id = get_refund_details($refund_id, 'invoice_id');
    
    $sql = "DELETE FROM ".PRFX."refund_records WHERE refund_id=".$db->qstr($refund_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the refund records."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$refund_id.' '._gettext("deleted.");
        write_record_to_activity_log($record, null, null, null, $invoice_id);
        
        return true;
        
    }
    
}

/** Other Functions **/
   
##########################################
#      Last Record Look Up               #
##########################################

function last_refund_id_lookup() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."refund_records ORDER BY refund_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last refund record ID."));
    } else {
        
        return $rs->fields['refund_id'];
        
    }
        
}