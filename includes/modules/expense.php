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


#####################################################
#         Display expenses                          #
#####################################################

function display_expenses($db, $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $search_category, $search_term) {
    
    global $smarty;
    
    // Prepare the search terms where needed
    $prepared_search_term = prepare_expense_search_terms($search_category, $search_term);
    
    /* Filter the Records */    
    
    // Restrict by Search Category
    if($search_category != '') {
        
        switch ($search_category) {

            case 'id':            
                $whereTheseRecords = " WHERE EXPENSE_ID";
                break;

            case 'payee':          
                $whereTheseRecords = " WHERE EXPENSE_PAYEE";
                break;

            case 'date':          
                $whereTheseRecords = " WHERE EXPENSE_DATE";
                break;
            
            case 'type':          
                $whereTheseRecords = " WHERE EXPENSE_TYPE";
                break;

            case 'payement_method':          
                $whereTheseRecords = " WHERE EXPENSE_PAYMENT_METHOD";
                break;

            case 'net_amount':          
                $whereTheseRecords = " WHERE EXPENSE_NET_AMOUNT";
                break;

            case 'tax_rate':         
                $whereTheseRecords = " WHERE EXPENSE_TAX_RATE";
                break;
                
            case 'tax':      
                $whereTheseRecords = " WHERE EXPENSE_TAX_AMOUNT";
                break;
                
            case 'total':           
                $whereTheseRecords = " WHERE EXPENSE_GROSS_AMOUNT";
                break;
            
            case 'notes':        
                $whereTheseRecords = " WHERE EXPENSE_NOTES";
                break;
            
            case 'items':        
                $whereTheseRecords = " WHERE EXPENSE_ITEMS";
                break;
            
            default:           
                $whereTheseRecords = " WHERE EXPENSE_ID";

        }
        
        // Set the search term restrictor when a category has been set
        $likeTheseRecords = " LIKE '%".$prepared_search_term."%'";
        
    }
    
    /* The SQL code */
    
    $sql =  "SELECT * 
            FROM ".PRFX."EXPENSE".                                                   
            $whereTheseRecords.            
            $likeTheseRecords.
            " GROUP BY ".PRFX."EXPENSE.EXPENSE_ID".
            " ORDER BY ".PRFX."EXPENSE.EXPENSE_ID ".$direction;            
    
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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
#      Insert Expense                    #
##########################################

function insert_expense($db, $VAR) {
    
    global $smarty;

    $sql = "INSERT INTO ".PRFX."EXPENSE SET            
            EXPENSE_PAYEE           = ". $db->qstr( $VAR['expensePayee']                    ).",
            EXPENSE_DATE            = ". $db->qstr( date_to_timestamp($VAR['expenseDate'])  ).",
            EXPENSE_TYPE            = ". $db->qstr( $VAR['expenseType']                     ).",
            EXPENSE_PAYMENT_METHOD  = ". $db->qstr( $VAR['expensePaymentMethod']            ).",
            EXPENSE_NET_AMOUNT      = ". $db->qstr( $VAR['expenseNetAmount']                ).",
            EXPENSE_TAX_RATE        = ". $db->qstr( $VAR['expenseTaxRate']                  ).",
            EXPENSE_TAX_AMOUNT      = ". $db->qstr( $VAR['expenseTaxAmount']                ).",
            EXPENSE_GROSS_AMOUNT    = ". $db->qstr( $VAR['expenseGrossAmount']              ).",
            EXPENSE_NOTES           = ". $db->qstr( $VAR['expenseNotes']                    ).",
            EXPENSE_ITEMS           = ". $db->qstr( $VAR['expenseItems']                    );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
    
        return $db->Insert_ID();
        
    }
    
} 

/** Get Functions **/

##########################
#  Get Expense Details   #
##########################

function get_expense_details($db, $expense_id, $item = null){
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."EXPENSE WHERE EXPENSE_ID=".$db->qstr($expense_id);
    
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
#     Update Expense                #
#####################################

function update_expense($db, $expense_id, $VAR) {
    
    global $smarty;

    $sql = "UPDATE ".PRFX."EXPENSE SET
            EXPENSE_PAYEE           = ". $db->qstr( $VAR['expensePayee']                    ).",
            EXPENSE_DATE            = ". $db->qstr( date_to_timestamp($VAR['expenseDate'])  ).",
            EXPENSE_TYPE            = ". $db->qstr( $VAR['expenseType']                     ).",
            EXPENSE_PAYMENT_METHOD  = ". $db->qstr( $VAR['expensePaymentMethod']            ).",
            EXPENSE_NET_AMOUNT      = ". $db->qstr( $VAR['expenseNetAmount']                ).",
            EXPENSE_TAX_RATE        = ". $db->qstr( $VAR['expenseTaxRate']                  ).",
            EXPENSE_TAX_AMOUNT      = ". $db->qstr( $VAR['expenseTaxAmount']                ).",
            EXPENSE_GROSS_AMOUNT    = ". $db->qstr( $VAR['expenseGrossAmount']              ).",
            EXPENSE_NOTES           = ". $db->qstr( $VAR['expenseNotes']                    ).",
            EXPENSE_ITEMS           = ". $db->qstr( $VAR['expenseItems']                    )."
            WHERE EXPENSE_ID        = ". $db->qstr( $expense_id                             );                        
            
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
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

function delete_expense($db, $expense_id){
    
    global $smarty;
    
    $sql = "DELETE FROM ".PRFX."EXPENSE WHERE EXPENSE_ID=".$db->qstr($expense_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return true;
        
    } 
    
}

/** Other Functions **/

################################################################
#  Prepare Search Terms - compensates for smarty translations  #
################################################################

function prepare_expense_search_terms($search_category, $search_term) {

    $langvals = gateway_xml2php('expense');

    switch ($search_category) {

        case 'date':           
            return date_to_timestamp($search_term); 
            
        case 'type': {
            
            switch ($search_term) {

                case ($langvals['expense_type_1']):
                    return '1';                    

                case ($langvals['expense_type_2']):
                    return '2';                   

                case ($langvals['expense_type_3']):
                    return '3';                    

                case ($langvals['expense_type_4']):
                    return '4';

                case ($langvals['expense_type_5']):
                    return '5';
                    
                case ($langvals['expense_type_6']):
                    return '6';

                case ($langvals['expense_type_7']):
                    return '7';

                case ($langvals['expense_type_8']):
                    return '8';

                case ($langvals['expense_type_9']):
                    return '9';

                case ($langvals['expense_type_10']):
                    return '10';

                case ($langvals['expense_type_11']):
                    return '11';

                case ($langvals['expense_type_12']):
                    return '12';

                case ($langvals['expense_type_13']):
                    return '13';

                case ($langvals['expense_type_14']):
                    return '14';

                case ($langvals['expense_type_15']):
                    return '15';

                case ($langvals['expense_type_16']):
                    return '16';

                case ($langvals['expense_type_17']):
                    return '17';

                case ($langvals['expense_type_18']):
                    return '18';

                case ($langvals['expense_type_19']):
                    return '19';

                case ($langvals['expense_type_20']):
                    return '20';

                case ($langvals['expense_type_21']):
                    return '21';

                default:                   
                    return $search_term;
                
            }
            
        }

        case 'payment_method': {
            
            switch ($search_term) {

                case ($langvals['expense_payment_method_1']):
                    return '1';

                case ($langvals['expense_payment_method_2']):
                    return '2';

                case ($langvals['expense_payment_method_3']):
                    return '3';

                case ($langvals['expense_payment_method_4']):
                    return '4';

                case ($langvals['expense_payment_method_5']):
                    return '5';

                case ($langvals['expense_payment_method_6']):
                    return '6';

                case ($langvals['expense_payment_method_7']):
                    return '7';

                case ($langvals['expense_payment_method_8']):
                    return '8';

                case ($langvals['expense_payment_method_9']):
                    return '9';

                case ($langvals['expense_payment_method_10']):
                    return '10';

                case ($langvals['expense_payment_method_11']):
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

function last_expense_id_lookup($db){
    
    global $smarty;

    $sql = 'SELECT * FROM '.PRFX.'EXPENSE ORDER BY EXPENSE_ID DESC LIMIT 1';
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->fields['EXPENSE_ID'];
        
    }
    
}