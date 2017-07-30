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

function display_refunds($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null) {
    
    global $smarty;
    
    // Prepare the search terms where needed
    $prepared_search_term = prepare_refund_search_terms($search_category, $search_term);
    
    /* Filter the Records */  
    
    // Restrict by Search Category
    if($search_category != '') {
        
        switch ($search_category) {

            case 'id':            
                $whereTheseRecords = " WHERE refund_id";
                break;

            case 'payee':          
                $whereTheseRecords = " WHERE payee";
                break;

            case 'date':          
                $whereTheseRecords = " WHERE date";
                break;
            
            case 'type':          
                $whereTheseRecords = " WHERE type";
                break;

            case 'payement_method':          
                $whereTheseRecords = " WHERE payment_method";
                break;

            case 'net_amount':          
                $whereTheseRecords = " WHERE net_amount";
                break;

            case 'tax_rate':         
                $whereTheseRecords = " WHERE tax_rate";
                break;
                
            case 'tax':      
                $whereTheseRecords = " WHERE tax_amount";
                break;
                
            case 'total':           
                $whereTheseRecords = " WHERE gross_amount";
                break;
            
            case 'notes':        
                $whereTheseRecords = " WHERE notes";
                break;
            
            case 'items':        
                $whereTheseRecords = " WHERE items";
                break;
            
            default:           
                $whereTheseRecords = " WHERE refund_id";

        }
        
        // Set the search term restrictor when a category has been set
        $likeTheseRecords = " LIKE '%".$prepared_search_term."%'";
        
    }
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."refund                                                   
            ".$whereTheseRecords."
            ".$likeTheseRecords."
            GROUP BY ".PRFX."refund.refund_id
            ORDER BY ".PRFX."refund.refund_id
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the matching refund records."));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching refund records."));
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

function insert_refund($db, $VAR) {
    
    $sql = "INSERT INTO ".PRFX."refund SET            
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_timestamp($VAR['date']) ).",
            type             =". $db->qstr( $VAR['type']                    ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            tax_rate         =". $db->qstr( $VAR['tax_rate']                ).",
            tax_amount       =". $db->qstr( $VAR['tax_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            notes            =". $db->qstr( $VAR['notes']                   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the refund record into the database."));
        exit;
    } else {
        
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the refund details."));
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
#     Update refund                 #
#####################################

function update_refund($db, $refund_id, $VAR) {
    
    $sql = "UPDATE ".PRFX."refund SET
        
            payee            =". $db->qstr( $VAR['payee']                   ).",
            date             =". $db->qstr( date_to_timestamp($VAR['date']) ).",
            type             =". $db->qstr( $VAR['type']                    ).",
            payment_method   =". $db->qstr( $VAR['payment_method']          ).",
            net_amount       =". $db->qstr( $VAR['net_amount']              ).",
            tax_rate         =". $db->qstr( $VAR['tax_rate']                ).",
            tax_amount       =". $db->qstr( $VAR['tax_amount']              ).",
            gross_amount     =". $db->qstr( $VAR['gross_amount']            ).",            
            items            =". $db->qstr( $VAR['items']                   ).",
            notes            =". $db->qstr( $VAR['notes']                   )."
            WHERE refund_id  = ". $db->qstr( $refund_id                     );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the refund details."));
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

function delete_refund($db, $refund_id) {
    
    $sql = "DELETE FROM ".PRFX."refund WHERE refund_id=".$db->qstr($refund_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the refund records."));
        exit;
    } else {
        
        return true;
        
    }
    
}

/** Other Functions **/

################################################################
#  Prepare Search Terms - compensates for smarty translations  #
################################################################

function prepare_refund_search_terms($search_category, $search_term) {

    switch ($search_category) {

        case 'date':           
            return date_to_timestamp($search_term); 
            
        case 'type': {
            
            switch ($search_term) {

                case gettext("REFUND_TYPE_1"):
                    return '1';                    

                case gettext("REFUND_TYPE_2"):
                    return '2';                   

                case gettext("REFUND_TYPE_3"):
                    return '3';                    

                case gettext("REFUND_TYPE_4"):
                    return '4';

                case gettext("REFUND_TYPE_5"):
                    return '5';
               
                default:                   
                    return $search_term;
                
            }
            
        }

        case 'payment_method': {
            
            switch ($search_term) {

                case gettext("REFUND_PAYMENT_METHOD_1"):
                    return '1';

                case gettext("REFUND_PAYMENT_METHOD_2"):
                    return '2';

                case gettext("REFUND_PAYMENT_METHOD_3"):
                    return '3';

                case gettext("REFUND_PAYMENT_METHOD_4"):
                    return '4';

                case gettext("REFUND_PAYMENT_METHOD_5"):
                    return '5';

                case gettext("REFUND_PAYMENT_METHOD_6"):
                    return '6';

                case gettext("REFUND_PAYMENT_METHOD_7"):
                    return '7';

                case gettext("REFUND_PAYMENT_METHOD_8"):
                    return '8';

                case gettext("REFUND_PAYMENT_METHOD_9"):
                    return '9';

                case gettext("REFUND_PAYMENT_METHOD_10"):
                    return '10';

                case gettext("REFUND_PAYMENT_METHOD_11"):
                    return '11';
                    
                default:                   
                    return $search_term;                    

            }
                   
        }

        // If no conversion required just return the search term
        default:           
            return $search_term;           

    }
    
    // If no category sent return the search term
    return $search_term;
    
}
    
##########################################
#      Last Record Look Up               #
##########################################

function last_refund_id_lookup($db) {
    
    $sql = "SELECT * FROM ".PRFX."refund ORDER BY refund_id DESC LIMIT 1";

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to lookup the last refund record ID."));
        exit;
    } else {
        
        return $rs->fields['refund_id'];
        
    }
        
}