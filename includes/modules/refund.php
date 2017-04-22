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
   
#############################
#     Display refunds       #
#############################

function display_refunds($db, $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $search_category, $search_term) {
    
    global $smarty;
    
    // Prepare the search terms where needed
    $prepared_search_term = prepare_refund_search_terms($search_category, $search_term);
    
    /* Filter the Records */  
    
    // Restrict by Search Category
    if($search_category != '') {
        
        switch ($search_category) {

            case 'id':            
                $whereTheseRecords = " WHERE REFUND_ID";
                break;

            case 'payee':          
                $whereTheseRecords = " WHERE REFUND_PAYEE";
                break;

            case 'date':          
                $whereTheseRecords = " WHERE REFUND_DATE";
                break;
            
            case 'type':          
                $whereTheseRecords = " WHERE REFUND_TYPE";
                break;

            case 'payement_method':          
                $whereTheseRecords = " WHERE REFUND_PAYMENT_METHOD";
                break;

            case 'net_amount':          
                $whereTheseRecords = " WHERE REFUND_NET_AMOUNT";
                break;

            case 'tax_rate':         
                $whereTheseRecords = " WHERE REFUND_TAX_RATE";
                break;
                
            case 'tax':      
                $whereTheseRecords = " WHERE REFUND_TAX_AMOUNT";
                break;
                
            case 'total':           
                $whereTheseRecords = " WHERE REFUND_GROSS_AMOUNT";
                break;
            
            case 'notes':        
                $whereTheseRecords = " WHERE REFUND_NOTES";
                break;
            
            case 'items':        
                $whereTheseRecords = " WHERE REFUND_ITEMS";
                break;
            
            default:           
                $whereTheseRecords = " WHERE REFUND_ID";

        }
        
        // Set the search term restrictor when a category has been set
        $likeTheseRecords = " LIKE '%".$prepared_search_term."%'";
        
    }
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."REFUND".                                                   
            $whereTheseRecords.            
            $likeTheseRecords.
            " GROUP BY ".PRFX."REFUND.REFUND_ID".
            " ORDER BY ".PRFX."REFUND.REFUND_ID ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_count'));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_refund_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;

    $sql = "INSERT INTO ".PRFX."REFUND SET            
            REFUND_PAYEE            = ". $db->qstr( $VAR['refundPayee']                     ).",
            REFUND_DATE             = ". $db->qstr( date_to_timestamp($VAR['refundDate'])   ).",
            REFUND_TYPE             = ". $db->qstr( $VAR['refundType']                      ).",
            REFUND_PAYMENT_METHOD   = ". $db->qstr( $VAR['refundPaymentMethod']             ).",
            REFUND_NET_AMOUNT       = ". $db->qstr( $VAR['refundNetAmount']                 ).",
            REFUND_TAX_RATE         = ". $db->qstr( $VAR['refundTaxRate']                   ).",
            REFUND_TAX_AMOUNT       = ". $db->qstr( $VAR['refundTaxAmount']                 ).",
            REFUND_GROSS_AMOUNT     = ". $db->qstr( $VAR['refundGrossAmount']               ).",
            REFUND_NOTES            = ". $db->qstr( $VAR['refundNotes']                     ).",
            REFUND_ITEMS            = ". $db->qstr( $VAR['refundItems']                     );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_refund_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."REFUND WHERE REFUND_ID=".$db->qstr($refund_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
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
#     Update refund                 #
#####################################

function update_refund($db, $refund_id, $VAR) {
    
    global $smarty;

    $sql = "UPDATE ".PRFX."REFUND SET
            REFUND_PAYEE            = ". $db->qstr( $VAR['refundPayee']                     ).",
            REFUND_DATE             = ". $db->qstr( date_to_timestamp($VAR['refundDate'])   ).",
            REFUND_TYPE             = ". $db->qstr( $VAR['refundType']                      ).",
            REFUND_PAYMENT_METHOD   = ". $db->qstr( $VAR['refundPaymentMethod']             ).",
            REFUND_NET_AMOUNT       = ". $db->qstr( $VAR['refundNetAmount']                 ).",
            REFUND_TAX_RATE         = ". $db->qstr( $VAR['refundTaxRate']                   ).",
            REFUND_TAX_AMOUNT       = ". $db->qstr( $VAR['refundTaxAmount']                 ).",
            REFUND_GROSS_AMOUNT     = ". $db->qstr( $VAR['refundGrossAmount']               ).",
            REFUND_NOTES            = ". $db->qstr( $VAR['refundNotes']                     ).",
            REFUND_ITEMS            = ". $db->qstr( $VAR['refundItems']                     )."
            WHERE REFUND_ID         = ". $db->qstr( $refund_id                              );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_refund_error_message_function_'.__FUNCTION__.'_failed'));
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
    
    global $smarty;
    
    $sql = "DELETE FROM ".PRFX."REFUND WHERE REFUND_ID=".$db->qstr($refund_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_refund_error_message_function_'.__FUNCTION__.'_failed'));
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

    $langvals = gateway_xml2php('expense');

    switch ($search_category) {

        case 'date':           
            return date_to_timestamp($search_term); 
            
        case 'type': {
            
            switch ($search_term) {

                case ($langvals['refund_type_1']):
                    return '1';                    

                case ($langvals['refund_type_2']):
                    return '2';                   

                case ($langvals['refund_type_3']):
                    return '3';                    

                case ($langvals['refund_type_4']):
                    return '4';

                case ($langvals['refund_type_5']):
                    return '5';
               
                default:                   
                    return $search_term;
                
            }
            
        }

        case 'payment_method': {
            
            switch ($search_term) {

                case ($langvals['refund_payment_method_1']):
                    return '1';

                case ($langvals['refund_payment_method_2']):
                    return '2';

                case ($langvals['refund_payment_method_3']):
                    return '3';

                case ($langvals['refund_payment_method_4']):
                    return '4';

                case ($langvals['refund_payment_method_5']):
                    return '5';

                case ($langvals['refund_payment_method_6']):
                    return '6';

                case ($langvals['refund_payment_method_7']):
                    return '7';

                case ($langvals['refund_payment_method_8']):
                    return '8';

                case ($langvals['refund_payment_method_9']):
                    return '9';

                case ($langvals['refund_payment_method_10']):
                    return '10';

                case ($langvals['refund_payment_method_11']):
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
    
    global $smarty;

    $sql = 'SELECT * FROM '.PRFX.'REFUND ORDER BY REFUND_ID DESC LIMIT 1';

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_refund_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['REFUND_ID'];
        
    }
        
}