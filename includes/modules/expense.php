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


#####################################################
#         Display expenses                          #
#####################################################

function display_expenses($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null) {
    
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
            FROM ".PRFX."expense                                                   
            ".$whereTheseRecords."
            ".$likeTheseRecords."
            GROUP BY ".PRFX."expense.EXPENSE_ID
            ORDER BY ".PRFX."expense.EXPENSE_ID
            ".$direction;            
    
    /* Restrict by pages */
    
    if($use_pages == true) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the matching expense records."));
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching expense records."));
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
    
    $sql = "INSERT INTO ".PRFX."expense SET            
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the expense record into the database."));
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
    
    $sql = "SELECT * FROM ".PRFX."expense WHERE EXPENSE_ID=".$db->qstr($expense_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the expense details."));
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
#     Update Expense                #
#####################################

function update_expense($db, $expense_id, $VAR) {
    
    $sql = "UPDATE ".PRFX."expense SET
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the expense details."));
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
    
    $sql = "DELETE FROM ".PRFX."expense WHERE EXPENSE_ID=".$db->qstr($expense_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the expense record."));
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

    switch ($search_category) {

        case 'date':           
            return date_to_timestamp($search_term); 
            
        case 'type': {
            
            switch ($search_term) {

                case gettext("EXPENSE_TYPE_1"):
                    return '1';                    

                case gettext("EXPENSE_TYPE_2"):
                    return '2';                   

                case gettext("EXPENSE_TYPE_3"):
                    return '3';                    

                case gettext("EXPENSE_TYPE_4"):
                    return '4';

                case gettext("EXPENSE_TYPE_5"):
                    return '5';
                    
                case gettext("EXPENSE_TYPE_6"):
                    return '6';

                case gettext("EXPENSE_TYPE_7"):
                    return '7';

                case gettext("EXPENSE_TYPE_8"):
                    return '8';

                case gettext("EXPENSE_TYPE_9"):
                    return '9';

                case gettext("EXPENSE_TYPE_10"):
                    return '10';

                case gettext("EXPENSE_TYPE_11"):
                    return '11';

                case gettext("EXPENSE_TYPE_12"):
                    return '12';

                case gettext("EXPENSE_TYPE_13"):
                    return '13';

                case gettext("EXPENSE_TYPE_14"):
                    return '14';

                case gettext("EXPENSE_TYPE_15"):
                    return '15';

                case gettext("EXPENSE_TYPE_16"):
                    return '16';

                case gettext("EXPENSE_TYPE_17"):
                    return '17';

                case gettext("EXPENSE_TYPE_18"):
                    return '18';

                case gettext("EXPENSE_TYPE_19"):
                    return '19';

                case gettext("EXPENSE_TYPE_20"):
                    return '20';

                case gettext("EXPENSE_TYPE_21"):
                    return '21';

                default:                   
                    return $search_term;
                
            }
            
        }

        case 'payment_method': {
            
            switch ($search_term) {

                case gettext("EXPENSE_PAYMENT_METHOD_1"):
                    return '1';

                case gettext("EXPENSE_PAYMENT_METHOD_2"):
                    return '2';

                case gettext("EXPENSE_PAYMENT_METHOD_3"):
                    return '3';

                case gettext("EXPENSE_PAYMENT_METHOD_4"):
                    return '4';

                case gettext("EXPENSE_PAYMENT_METHOD_5"):
                    return '5';

                case gettext("EXPENSE_PAYMENT_METHOD_6"):
                    return '6';

                case gettext("EXPENSE_PAYMENT_METHOD_7"):
                    return '7';

                case gettext("EXPENSE_PAYMENT_METHOD_8"):
                    return '8';

                case gettext("EXPENSE_PAYMENT_METHOD_9"):
                    return '9';

                case gettext("EXPENSE_PAYMENT_METHOD_10"):
                    return '10';

                case gettext("EXPENSE_PAYMENT_METHOD_11"):
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
    
    $sql = "SELECT * FROM ".PRFX."expense ORDER BY EXPENSE_ID DESC LIMIT 1";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to lookup the last expense record ID."));
        exit;
    } else {
        
        return $rs->fields['EXPENSE_ID'];
        
    }
    
}