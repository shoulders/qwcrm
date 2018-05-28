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

function display_refunds($db, $order_by = 'refund_id', $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null) {
    
    global $smarty;
    
    /* Filter the Records */    
    
    // Restrict by Search Category
    if($search_category != '') {
        
        // Filter by search category
        $whereTheseRecords = " WHERE $search_category";    
        
        // Filter by search term
        $likeTheseRecords = " LIKE '%".$search_term."%'";
        
    }
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."refund                                                   
            ".$whereTheseRecords."
            ".$likeTheseRecords."
            GROUP BY ".PRFX."refund.".$order_by."
            ORDER BY ".PRFX."refund.".$order_by."
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching refund records."));
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
        $previous = ($page_no - 1);        
        $smarty->assign('previous', $previous);          
        
        // Assign the next page        
        if($page_no == $total_pages) {$next = 0;}
        elseif($page_no < $total_pages) {$next = ($page_no + 1);}
        else {$next = $total_pages;}
        $smarty->assign('next', $next);
        
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
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching refund records."));
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

/** Insert Functions **/

##########################################
#      Insert Refund                     #
##########################################

function insert_refund($db, $VAR) {
    
    $sql = "INSERT INTO ".PRFX."refund SET
            invoice_id      =". $db->qstr( $VAR['invoice_id']              ).",
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_timestamp($VAR['date']) ).",
            type             =". $db->qstr( $VAR['type']                    ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            notes            =". $db->qstr( $VAR['notes']                   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the refund record into the database."));
        exit;
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

function get_refund_details($db, $refund_id, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."refund WHERE refund_id=".$db->qstr($refund_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the refund details."));
        exit;
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

function get_refund_types($db) {
    
    $sql = "SELECT * FROM ".PRFX."refund_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get refund types."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update refund                 #
#####################################

function update_refund($db, $refund_id, $VAR) {
    
    $sql = "UPDATE ".PRFX."refund SET
            invoice_id          =". $db->qstr( $VAR['invoice_id']           ).",
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_timestamp($VAR['date']) ).",
            type             =". $db->qstr( $VAR['type']                    ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            notes            =". $db->qstr( $VAR['notes']                   )."
            WHERE refund_id  = ". $db->qstr( $refund_id                     );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the refund details."));
        exit;
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$refund_id.' '._gettext("updated.");
        write_record_to_activity_log($record, null, null, null, $VAR['invoice_id']);
        
        return true;
      
    }
    
} 

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_refund($db, $refund_id) {
    
    // Get invoice_id before deleting the record
    $invoice_id = get_refund_details($db, $refund_id, 'invoice_id');
    
    $sql = "DELETE FROM ".PRFX."refund WHERE refund_id=".$db->qstr($refund_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the refund records."));
        exit;
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

function last_refund_id_lookup($db) {
    
    $sql = "SELECT * FROM ".PRFX."refund ORDER BY refund_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['component'], $_GET['page_tpl'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last refund record ID."));
        exit;
    } else {
        
        return $rs->fields['refund_id'];
        
    }
        
}