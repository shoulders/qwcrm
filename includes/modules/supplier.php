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

/** Mandatory Code **/

/** Display Functions **/

###############################
#         Display expenses    #
###############################

function display_suppliers($db, $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $search_category, $search_term) {
    
    global $smarty;
    
    // Prepare the search terms where needed
    $prepared_search_term = prepare_supplier_search_terms($search_category, $search_term);
    
    /* Filter the Records */
    
    // Restrict by Search Category
    if($search_category != '') {
        
        switch ($search_category) {

            case 'id':            
                $whereTheseRecords = " WHERE SUPPLIER_ID";
                break;

            case 'name':          
                $whereTheseRecords = " WHERE SUPPLIER_NAME";
                break;

            case 'contact':          
                $whereTheseRecords = " WHERE SUPPLIER_CONTACT";
                break;
            
            case 'type':          
                $whereTheseRecords = " WHERE SUPPLIER_TYPE";
                break;

            case 'zip':          
                $whereTheseRecords = " WHERE SUPPLIER_ZIP";
                break;

            case 'notes':          
                $whereTheseRecords = " WHERE SUPPLIER_NOTES";
                break;

            case 'description':         
                $whereTheseRecords = " WHERE SUPPLIER_DESCRIPTION";
                break;
             
            default:           
                $whereTheseRecords = " WHERE SUPPLIER_ID";

        }
        
        // Set the search term restrictor when a category has been set
        $likeTheseRecords = " LIKE '%".$prepared_search_term."%'";
        
    }
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."TABLE_SUPPLIER".                                                   
            $whereTheseRecords.            
            $likeTheseRecords.
            " GROUP BY ".PRFX."TABLE_SUPPLIER.SUPPLIER_ID".
            " ORDER BY ".PRFX."TABLE_SUPPLIER.SUPPLIER_ID ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_supplier_error_message_function_'.__FUNCTION__.'_count'));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_supplier_error_message_function_'.__FUNCTION__.'_failed'));
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

/** New/Insert Functions **/

##########################################
#      Insert New Record                 #
##########################################

function insert_supplier($db, $VAR) {
    
    global $smarty;

    $sql = "INSERT INTO ".PRFX."TABLE_SUPPLIER SET            
            SUPPLIER_NAME           = ". $db->qstr( $VAR['supplierName']        ).",
            SUPPLIER_CONTACT        = ". $db->qstr( $VAR['supplierContact']     ).",
            SUPPLIER_TYPE           = ". $db->qstr( $VAR['supplierType']        ).",
            SUPPLIER_PHONE          = ". $db->qstr( $VAR['supplierPhone']       ).",
            SUPPLIER_FAX            = ". $db->qstr( $VAR['supplierFax']         ).",
            SUPPLIER_MOBILE         = ". $db->qstr( $VAR['supplierMobile']      ).",
            SUPPLIER_WWW            = ". $db->qstr( $VAR['supplierWww']         ).",
            SUPPLIER_EMAIL          = ". $db->qstr( $VAR['supplierEmail']       ).",
            SUPPLIER_ADDRESS        = ". $db->qstr( $VAR['supplierAddress']     ).",
            SUPPLIER_CITY           = ". $db->qstr( $VAR['supplierCity']        ).",
            SUPPLIER_STATE          = ". $db->qstr( $VAR['supplierState']       ).",
            SUPPLIER_ZIP            = ". $db->qstr( $VAR['supplierZip']         ).",
            SUPPLIER_NOTES          = ". $db->qstr( $VAR['supplierNotes']       ).",
            SUPPLIER_DESCRIPTION    = ". $db->qstr( $VAR['supplierDescription'] );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_supplier_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_ID=".$db->qstr($supplier_id);
    
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

/** Update Functions **/

#####################################
#     Update Record                 #
#####################################

function update_supplier($db, $supplier_id, $VAR) {
    
    global $smarty;

    $sql = "UPDATE ".PRFX."TABLE_SUPPLIER SET
            SUPPLIER_NAME           = ". $db->qstr( $VAR['supplierName']        ).",
            SUPPLIER_CONTACT        = ". $db->qstr( $VAR['supplierContact']     ).",
            SUPPLIER_TYPE           = ". $db->qstr( $VAR['supplierType']        ).",
            SUPPLIER_PHONE          = ". $db->qstr( $VAR['supplierPhone']       ).",
            SUPPLIER_FAX            = ". $db->qstr( $VAR['supplierFax']         ).",
            SUPPLIER_MOBILE         = ". $db->qstr( $VAR['supplierMobile']      ).",
            SUPPLIER_WWW            = ". $db->qstr( $VAR['supplierWww']         ).",
            SUPPLIER_EMAIL          = ". $db->qstr( $VAR['supplierEmail']       ).",
            SUPPLIER_ADDRESS        = ". $db->qstr( $VAR['supplierAddress']     ).",
            SUPPLIER_CITY           = ". $db->qstr( $VAR['supplierCity']        ).",
            SUPPLIER_STATE          = ". $db->qstr( $VAR['supplierState']       ).",
            SUPPLIER_ZIP            = ". $db->qstr( $VAR['supplierZip']         ).",
            SUPPLIER_NOTES          = ". $db->qstr( $VAR['supplierNotes']       ).",
            SUPPLIER_DESCRIPTION    = ". $db->qstr( $VAR['supplierDescription'] )."
            WHERE SUPPLIER_ID       = ". $db->qstr( $supplier_id                );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_supplier_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;
    
    $sql = "DELETE FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_ID=".$db->qstr($supplier_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_supplier_error_message_function_'.__FUNCTION__.'_failed'));
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

    $langvals = gateway_xml2php('supplier');

    switch ($supplier_search_category) {

        case 'type': {
            
            switch ($supplier_search_term) {

                case ($langvals['supplier_type_1']):
                    return '1';

                case ($langvals['supplier_type_2']):
                    return '2';

                case ($langvals['supplier_type_3']):
                    return '3';

                case ($langvals['supplier_type_4']):
                    return '4';

                case ($langvals['supplier_type_5']):
                    return '5';

                case ($langvals['supplier_type_6']):
                    return '6';

                case ($langvals['supplier_type_7']):
                    return '7';

                case ($langvals['supplier_type_8']):
                    return '8';

                case ($langvals['supplier_type_9']):
                    return '9';

                case ($langvals['supplier_type_10']):
                    return '10';

                case ($langvals['supplier_type_11']):
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
    
    global $smarty;

    $sql = 'SELECT * FROM '.PRFX.'TABLE_SUPPLIER ORDER BY SUPPLIER_ID DESC LIMIT 1';

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_supplier_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['SUPPLIER_ID'];
        
    }
    
}