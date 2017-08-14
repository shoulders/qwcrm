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

function display_suppliers($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null) {
    
    global $smarty;
    
    // Prepare the search terms where needed
    $prepared_search_term = prepare_supplier_search_terms($search_category, $search_term);
    
    /* Filter the Records */
    
    // Restrict by Search Category
    if($search_category != '') {
        
        switch ($search_category) {

            case 'id':            
                $whereTheseRecords = " WHERE supplier_id";
                break;

            case 'name':          
                $whereTheseRecords = " WHERE display_name";
                break;

            case 'contact':          
                $whereTheseRecords = " WHERE contact";
                break;
            
            case 'type':          
                $whereTheseRecords = " WHERE type";
                break;

            case 'zip':          
                $whereTheseRecords = " WHERE zip";
                break;
            
            case 'country':          
                $whereTheseRecords = " WHERE country";
                break;            

            case 'notes':          
                $whereTheseRecords = " WHERE notes";
                break;

            case 'description':         
                $whereTheseRecords = " WHERE description";
                break;
             
            default:           
                $whereTheseRecords = " WHERE supplier_id";

        }
        
        // Set the search term restrictor when a category has been set
        $likeTheseRecords = " LIKE '%".$prepared_search_term."%'";
        
    }
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."supplier                                                   
            ".$whereTheseRecords."
            ".$likeTheseRecords."
            GROUP BY ".PRFX."supplier.supplier_id
            ORDER BY ".PRFX."supplier.supplier_id
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the matching supplier records."));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching supplier records."));
        exit;
    } else {
        
        $records = $rs->GetArray();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** New/Insert Functions **/

##########################################
#      Insert New Record                 #
##########################################

function insert_supplier($db, $VAR) {
    
    $sql = "INSERT INTO ".PRFX."supplier SET            
            display_name   =". $db->qstr( $VAR['display_name']  ).",
            contact        =". $db->qstr( $VAR['contact']       ).",
            website        =". $db->qstr( $VAR['website']       ).",
            email          =". $db->qstr( $VAR['email']         ).",
            type           =". $db->qstr( $VAR['type']          ).",
            phone          =". $db->qstr( $VAR['phone']         ).",
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the supplier record into the database."));
        exit;
    } else {
        
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the supplier details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

/** Update Functions **/

#####################################
#     Update Record                 #
#####################################

function update_supplier($db, $supplier_id, $VAR) {
    
    $sql = "UPDATE ".PRFX."supplier SET
            display_name   =". $db->qstr( $VAR['display_name']  ).",
            contact        =". $db->qstr( $VAR['contact']       ).",
            website        =". $db->qstr( $VAR['website']       ).",
            email          =". $db->qstr( $VAR['email']         ).",
            type           =". $db->qstr( $VAR['type']          ).",
            phone          =". $db->qstr( $VAR['phone']         ).",
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the supplier details."));
        exit;
    } else {
        
        return true;
        
    }
    
} 

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete Record                  #
#####################################

function delete_supplier($db, $supplier_id){
    
    $sql = "DELETE FROM ".PRFX."supplier WHERE supplier_id=".$db->qstr($supplier_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the supplier record."));
        exit;
    } else {
        
        return true;
        
    }
    
}

/** Other Functions **/

################################################################
#  Prepare Search Terms - compensates for smarty translations  #
################################################################

function prepare_supplier_search_terms($search_category, $search_term) {

    switch ($supplier_search_category) {

        case 'type': {
            
            switch ($supplier_search_term) {

                case gettext("SUPPLIER_TYPE_1"):
                    return '1';

                case gettext("SUPPLIER_TYPE_2"):
                    return '2';

                case gettext("SUPPLIER_TYPE_3"):
                    return '3';

                case gettext("SUPPLIER_TYPE_4"):
                    return '4';

                case gettext("SUPPLIER_TYPE_5"):
                    return '5';

                case gettext("SUPPLIER_TYPE_6"):
                    return '6';

                case gettext("SUPPLIER_TYPE_7"):
                    return '7';

                case gettext("SUPPLIER_TYPE_8"):
                    return '8';

                case gettext("SUPPLIER_TYPE_9"):
                    return '9';

                case gettext("SUPPLIER_TYPE_10"):
                    return '10';

                case gettext("SUPPLIER_TYPE_11"):
                    return'11';

            }
                 
        }

       default:
           return $search_term;          

    }
    
}

############################################
#      Last supplier Record ID Look Up     #
############################################

function last_supplier_id_lookup($db) {
    
    $sql = "SELECT * FROM ".PRFX."supplier ORDER BY supplier_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to lookup the last supplier record ID."));
        exit;
    } else {
        
        return $rs->fields['supplier_id'];
        
    }
    
}