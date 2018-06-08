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

###############################
#     Display Suppliers       #
###############################

function display_suppliers($db, $order_by = 'supplier_id', $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $type = null) {
    
    $smarty = QSmarty::getInstance();
    
    /* Records Search */ 
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."supplier.supplier_id\n";
    
    // Restrict results by search category and search term
    if($search_term) {$whereTheseRecords .= " AND ".PRFX."supplier.$search_category LIKE '%$search_term%'";}
    
    /* Filter the Records */ 
    
    // Restrict by Type
    if($type) { $whereTheseRecords .= " AND ".PRFX."supplier.type= ".$db->qstr($type);}
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."supplier                                                   
            ".$whereTheseRecords."            
            GROUP BY ".PRFX."supplier.".$order_by."
            ORDER BY ".PRFX."supplier.".$order_by."
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching supplier records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching supplier records."));
    } else {
        
        $records = $rs->GetArray();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

##########################################
#      Insert New Record                 #
##########################################

function insert_supplier($db, $VAR) {
    
    $sql = "INSERT INTO ".PRFX."supplier SET            
            display_name   =". $db->qstr( $VAR['display_name']  ).",
            first_name     =". $db->qstr( $VAR['first_name']    ).",
            last_name      =". $db->qstr( $VAR['last_name']     ).",
            website        =". $db->qstr( $VAR['website']       ).",
            email          =". $db->qstr( $VAR['email']         ).",
            type           =". $db->qstr( $VAR['type']          ).",
            primary_phone  =". $db->qstr( $VAR['primary_phone'] ).",
            mobile_phone   =". $db->qstr( $VAR['mobile_phone']  ).",
            fax            =". $db->qstr( $VAR['fax']           ).",
            address        =". $db->qstr( $VAR['address']       ).",
            city           =". $db->qstr( $VAR['city']          ).",
            state          =". $db->qstr( $VAR['state']         ).",
            zip            =". $db->qstr( $VAR['zip']           ).",
            country        =". $db->qstr( $VAR['country']       ).",
            description    =". $db->qstr( $VAR['description']   ).", 
            notes          =". $db->qstr( $VAR['notes']         );            

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the supplier record into the database."));
    } else {
        
        // Log activity        
        write_record_to_activity_log(_gettext("Supplier Record").' '.$db->Insert_ID().' ('.$VAR['display_name'].') '._gettext("created."));

        return $db->Insert_ID();
        
    }
    
} 

/** Get Functions **/

############################
#   Get supplier details   #
############################

function get_supplier_details($db, $supplier_id, $item = null){
    
    $sql = "SELECT * FROM ".PRFX."supplier WHERE supplier_id=".$db->qstr($supplier_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the supplier details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Get Supplier Types             #
#####################################

function get_supplier_types($db) {
    
    $sql = "SELECT * FROM ".PRFX."supplier_types";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get supplier types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

/** Update Functions **/

#####################################
#     Update Record                 #
#####################################

function update_supplier($db, $supplier_id, $VAR) {
    
    $sql = "UPDATE ".PRFX."supplier SET
            display_name   =". $db->qstr( $VAR['display_name']  ).",
            first_name     =". $db->qstr( $VAR['first_name']    ).",
            last_name      =". $db->qstr( $VAR['last_name']     ).",
            website        =". $db->qstr( $VAR['website']       ).",
            email          =". $db->qstr( $VAR['email']         ).",
            type           =". $db->qstr( $VAR['type']          ).",
            primary_phone  =". $db->qstr( $VAR['primary_phone'] ).",
            mobile_phone   =". $db->qstr( $VAR['mobile_phone']  ).",
            fax            =". $db->qstr( $VAR['fax']           ).",
            address        =". $db->qstr( $VAR['address']       ).",
            city           =". $db->qstr( $VAR['city']          ).",
            state          =". $db->qstr( $VAR['state']         ).",
            zip            =". $db->qstr( $VAR['zip']           ).",
            country        =". $db->qstr( $VAR['country']       ).",
            description    =". $db->qstr( $VAR['description']   ).", 
            notes          =". $db->qstr( $VAR['notes']         )."
            WHERE supplier_id = ". $db->qstr( $supplier_id );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the supplier details."));
    } else {
        
        // Log activity        
        write_record_to_activity_log(_gettext("Supplier Record").' '.$db->Insert_ID().' ('.$VAR['display_name'].') '._gettext("updated."));

        return true;
        
    }
    
} 

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_supplier($db, $supplier_id) {
    
    $display_name = get_supplier_details($db, $supplier_id, 'display_name');
    
    $sql = "DELETE FROM ".PRFX."supplier WHERE supplier_id=".$db->qstr($supplier_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the supplier record."));
    } else {
        
        // Log activity        
        write_record_to_activity_log(_gettext("Supplier Record").' '.$supplier_id.' ('.$display_name.') '._gettext("deleted."));
        
        return true;
        
    }
    
}

/** Other Functions **/

############################################
#      Last supplier Record ID Look Up     #
############################################

function last_supplier_id_lookup($db) {
    
    $sql = "SELECT * FROM ".PRFX."supplier ORDER BY supplier_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to lookup the last supplier record ID."));
    } else {
        
        return $rs->fields['supplier_id'];
        
    }
    
}