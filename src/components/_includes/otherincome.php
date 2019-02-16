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
#     Display otherincomes       #
#############################

function display_otherincomes($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $type = null, $payment_method = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'otherincome_id';    
    
    /* Records Search */    
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."otherincome_records.otherincome_id\n";
    
    // Restrict results by search category and search term
    if($search_term) {$whereTheseRecords .= " AND ".PRFX."otherincome_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');} 
    
    /* Filter the Records */  
    
    // Restrict by Type
    if($type) { $whereTheseRecords .= " AND ".PRFX."otherincome_records.type= ".$db->qstr($type);}
        
    // Restrict by Method
    if($payment_method) { $whereTheseRecords .= " AND ".PRFX."otherincome_records.payment_method= ".$db->qstr($payment_method);} 
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."otherincome_records                                                   
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."otherincome_records.".$order_by."
            ORDER BY ".PRFX."otherincome_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching otherincome records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching otherincome records."));
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

function insert_otherincome($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."otherincome_records SET
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",
            tax_system       =". $db->qstr(get_company_details('tax_system')).",            
            item_type        =". $db->qstr( $VAR['item_type']               ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_tax_code     =". $db->qstr( $VAR['vat_tax_code']            ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            note             =". $db->qstr( $VAR['note']                    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the otherincome record into the database."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$db->Insert_ID().' '._gettext("created.");
        write_record_to_activity_log($record, null, null, null, null);
        
        return $db->Insert_ID();
        
    } 
    
}

/** Get Functions **/

###############################
#   Get otherincome details   #
###############################

function get_otherincome_details($otherincome_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_records WHERE otherincome_id=".$db->qstr($otherincome_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the otherincome details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Otherincome Types          #
#####################################

function get_otherincome_types() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get otherincome types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update otherincome            #
#####################################

function update_otherincome($VAR) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."otherincome_records SET
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_mysql_date($VAR['date'])).",            
            item_type        =". $db->qstr( $VAR['item_type']               ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            vat_tax_code     =". $db->qstr( $VAR['vat_tax_code']            ).",
            vat_rate         =". $db->qstr( $VAR['vat_rate']                ).",
            vat_amount       =". $db->qstr( $VAR['vat_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            note             =". $db->qstr( $VAR['note']                    )."
            WHERE otherincome_id  =". $db->qstr( $VAR['otherincome_id']     );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the otherincome details."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$VAR['otherincome_id'].' '._gettext("updated.");
        write_record_to_activity_log($record, null, null, null, null);
        
        return true;
      
    }
    
} 

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_otherincome($otherincome_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "DELETE FROM ".PRFX."otherincome_records WHERE otherincome_id=".$db->qstr($otherincome_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the otherincome records."));
    } else {
        
        // Log activity        
        $record = _gettext("Refund Record").' '.$otherincome_id.' '._gettext("deleted.");
        write_record_to_activity_log($record, null, null, null, null);
        
        return true;
        
    }
    
}

/** Other Functions **/
   
##########################################
#      Last Record Look Up               #  // not currently used
##########################################

function last_otherincome_id_lookup() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."otherincome_records ORDER BY otherincome_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last otherincome record ID."));
    } else {
        
        return $rs->fields['otherincome_id'];
        
    }
        
}