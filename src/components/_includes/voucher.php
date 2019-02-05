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

#########################################
#     Display Vouchers                  #
#########################################

function display_vouchers($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null, $redeemed_client_id = null, $redeemed_invoice_id = null) {

    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'voucher_id';
    $havingTheseRecords = '';
    
    /* Records Search */
        
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."voucher_records.voucher_id\n";
    
    // Restrict results by search category (client) and search term
    if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category (redeemed client) and search term
    elseif($search_category == 'redeemed_client_display_name') {$havingTheseRecords .= " HAVING redeemed_client_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."voucher_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}  
    
    /* Filter the Records */
    
    // Restrict by Status
    if($status) {
        
        // All Active Vouchers
        if($status == 'active') {
            
            $whereTheseRecords .= " AND ".PRFX."voucher_records.blocked = 0";
        
        // All Blocked Vouchers
        } elseif($status == 'blocked') {
            
            $whereTheseRecords .= " AND ".PRFX."voucher_records.blocked = 1";
        
        // Return Vouchers for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."voucher_records.status= ".$db->qstr($status);
            
        }
        
    }    
    
    // Restrict by Employee
    if($employee_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.employee_id=".$db->qstr($employee_id);}
    
    // Restrict by Client
    if($client_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.client_id=".$db->qstr($client_id);}
    
    // Restrict by Workorder
    if($workorder_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.workorder_id=".$db->qstr($workorder_id);}
    
    // Restrict by Invoice
    if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.invoice_id=".$db->qstr($invoice_id);}
    
    // Restrict by Redeemed Client
    if($redeemed_client_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_client_id=".$db->qstr($redeemed_client_id);}
        
    // Restrict by Redeemed Invoice
    if($redeemed_invoice_id) {$whereTheseRecords .= " AND ".PRFX."voucher_records.redeemed_invoice_id=".$db->qstr($redeemed_invoice_id);}
    
    /* The SQL code */
    
    $sql = "SELECT

        ".PRFX."voucher_records.*,                            
        IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,                       
        CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,            
        IF(redeemed_client_records.company_name !='', redeemed_client_records.company_name, CONCAT(redeemed_client_records.first_name, ' ', redeemed_client_records.last_name)) AS redeemed_client_display_name

        FROM ".PRFX."voucher_records            
        LEFT JOIN ".PRFX."user_records ON ".PRFX."voucher_records.employee_id = ".PRFX."user_records.user_id
        LEFT JOIN ".PRFX."client_records ON ".PRFX."voucher_records.client_id = ".PRFX."client_records.client_id
        LEFT JOIN ".PRFX."client_records AS redeemed_client_records ON ".PRFX."voucher_records.redeemed_client_id = redeemed_client_records.client_id

        ".$whereTheseRecords."
        GROUP BY ".PRFX."voucher_records.".$order_by."
        ".$havingTheseRecords."
        ORDER BY ".PRFX."voucher_records.".$order_by."
        ".$direction;   

    /* Restrict by pages */
    
    if($use_pages) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching Voucher records."));
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
        $rs = '';
    
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }        
    
    /* Return the records */
         
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Voucher records."));
        
    } else {        
        
        $records = $rs->GetArray();   // If I call this twice for this search, no results are shown on the TPL

        if(empty($records)){
            
            return false;
            
        } else {
           
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

#################################
#   Insert Voucher     #
#################################

function insert_voucher($invoice_id, $expiry_date, $amount, $note) {
    
    $db = QFactory::getDbo();
    $invoice_details = get_invoice_details($invoice_id);
    
    $sql = "INSERT INTO ".PRFX."voucher_records SET 
            voucher_code   =". $db->qstr( generate_voucher_code()                     ).",  
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id           ).",
            client_id       =". $db->qstr( $invoice_details['client_id']                ).",
            workorder_id    =". $db->qstr( $invoice_details['workorder_id']             ).",
            invoice_id      =". $db->qstr( $invoice_details['invoice_id']               ).",
            open_date       =". $db->qstr( mysql_datetime()                             ).",
            expiry_date     =". $db->qstr( date_to_mysql_date($expiry_date).' 23:59:59' ).",            
            status          =". $db->qstr( 'unused'                                     ).",  
            blocked         =". $db->qstr( '0'                                          ).",
            amount          =". $db->qstr( $amount                                      ).",
            note            =". $db->qstr( $note                                        );

    if(!$db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the Voucher into the database."));

    } else {
        
        $voucher_id = $db->Insert_ID();
        
        // Recalculate the invoice totals and update them
        recalculate_invoice($invoice_details['invoice_id']);

        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was created by").' '.QFactory::getUser()->login_display_name.'.';      
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['client_id']);
        
        // Update last active record
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        update_invoice_last_active($invoice_details['invoice_id']);       
        
        return $voucher_id ;
        
    }
    
}

/** Get Functions **/

##########################
#  Get voucher details   #
##########################

function get_voucher_details($voucher_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."voucher_records WHERE voucher_id=".$db->qstr($voucher_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the Voucher details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#########################################
#   Get voucher_id by voucher_code      #
#########################################

function get_voucher_id_by_voucher_code($voucher_code) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."voucher_records WHERE voucher_code=".$db->qstr($voucher_code);

    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the Voucher ID by the Voucher code."));
    }
    
    if($rs->fields['voucher_id'] != '') {
        return $rs->fields['voucher_id'];
    } else {
        return false;
    }
    
}

#####################################
#    Get Voucher Statuses          #
#####################################

function get_voucher_statuses($restricted_statuses = false) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."voucher_statuses";
    
    // Restrict statuses to those that are allowed to be changed by the user
    if($restricted_statuses) {
        $sql .= "\nWHERE status_key NOT IN ('redeemed', 'expired', 'refunded', 'cancelled', 'deleted')";
    }

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Voucher statuses."));
    } else {
        
        return $rs->GetArray();     
        
    }    
    
}

######################################
#  Get Voucher status display name   #
######################################

function get_voucher_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."voucher_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the voucher status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

###########################################
#   Calculate Voucher Invoice Sub Total   #  // All statuses should be summed up, deleted vouchers do not have an invoice_id anyway so are ignored
###########################################

function get_vouchers_items_sub_total($invoice_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(amount) AS sub_total_sum FROM ".PRFX."voucher_records WHERE invoice_id=" . $db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to calculate the invoice voucher sub total."));
    } else {
        
        return  $rs->fields['sub_total_sum'];
        
    }
  
}

/** Update Functions **/

#################################
#   Update Voucher              #
#################################

function update_voucher($voucher_id, $expiry_date, $amount, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."voucher_records SET     
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id           ).",
            expiry_date     =". $db->qstr( date_to_mysql_date($expiry_date).' 23:59:59' ).",            
            amount          =". $db->qstr( $amount                                      ).",
            note            =". $db->qstr( $note                                        )."
            WHERE voucher_id =". $db->qstr($voucher_id);

    if(!$db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Voucher record in the database."));

    } else {
        
        // Make sure correct expiry status is set (unused/expired)
        check_voucher_is_expired($voucher_id);
        
        $voucher_details = get_voucher_details($voucher_id);
        
        // Recalculate the invoice totals and update them
        recalculate_invoice($voucher_details['invoice_id']);
        
        // Log activity
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id']);

        // Update last active record
        update_client_last_active($voucher_details['client_id']);
        update_workorder_last_active($voucher_details['workorder_id']);
        update_invoice_last_active($voucher_details['invoice_id']);
        
        return;
        
    }
    
}

############################
# Update Voucher Status    #
############################

function update_voucher_status($voucher_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get voucher details
    $voucher_details = get_voucher_details($voucher_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $voucher_details['status']) {        
        if (!$silent) { postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    $sql = "UPDATE ".PRFX."voucher_records SET
            status               =". $db->qstr( $new_status  )."            
            WHERE voucher_id    =". $db->qstr( $voucher_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a Voucher Status."));
        
    } else {    
        
        // Update voucher 'blocked' boolean for the new status
        if($new_status == 'redeemed' || $new_status == 'suspended' || $new_status == 'expired' || $new_status == 'refunded' || $new_status == 'cancelled' || $new_status == 'deleted') {
            update_voucher_blocked_status($voucher_id, 'blocked');
        } else {
            update_voucher_blocked_status($voucher_id, 'active');
        }
        
        // Status updated message
        if (!$silent) { postEmulationWrite('information_msg', _gettext("Voucher status updated.")); }
        
        // For writing message to log file, get voucher status display name
        $voucher_status_diplay_name = _gettext(get_voucher_status_display_name($new_status));
        
        // Create a Workorder History Note       
        insert_workorder_history_note($voucher_details['workorder_id'], _gettext("Voucher Status updated to").' '.$voucher_status_diplay_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("Status updated to").' '.$voucher_status_diplay_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_id);
        
        // Update last active record
        update_client_last_active($voucher_details['client_id']);
        update_workorder_last_active($voucher_details['workorder_id']);
        update_invoice_last_active($voucher_details['invoice_id']);                
        
        return true;
        
    }
    
}

####################################
#  Update Voucher blocked Status   #
####################################

function update_voucher_blocked_status($voucher_id, $new_blocked_status) {
    
    $db = QFactory::getDbo();
    
    if($new_blocked_status == 'active') {
        
        $sql = "UPDATE ".PRFX."voucher_records SET
                blocked           =". $db->qstr( 0              )."
                WHERE voucher_id =". $db->qstr( $voucher_id     );     
        
    }
    
    if($new_blocked_status == 'blocked') {        
        
        $sql = "UPDATE ".PRFX."voucher_records SET
                blocked           =". $db->qstr( 1              )."
                WHERE voucher_id =". $db->qstr( $voucher_id     );
        
    }    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a voucher blocked status."));
    }
    
}

######################################################
#   Redeem the voucher against an invoice   #
######################################################

function update_voucher_as_redeemed($voucher_id, $invoice_id, $payment_id) {
    
    $db = QFactory::getDbo();
    
    $voucher_details = get_invoice_details($invoice_id);
    
    // Make sure redeem_date and close_date are the same
    $datetime = mysql_datetime();
    
    // some information has already been applied (as below) using update_voucher_status() earlier in the process
    $sql = "UPDATE ".PRFX."voucher_records SET
            employee_id         =". $db->qstr( QFactory::getUser()->login_user_id       ).",
            payment_id          =". $db->qstr( $payment_id                              ).",
            redeemed_client_id  =". $db->qstr( $voucher_details['client_id']            ).",   
            redeemed_invoice_id =". $db->qstr( $invoice_id                              ).",
            redeem_date         =". $db->qstr( $datetime                                ).", 
            close_date          =". $db->qstr( $datetime                                ).",
            status              =". $db->qstr( 'redeemed'                               ).",                        
            blocked             =". $db->qstr( 1                                        )."
            WHERE voucher_id   =". $db->qstr( $voucher_id                               );
    
    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Voucher as redeemed."));
    } else {       
        
        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was redeemed by").' '.get_client_details($voucher_details['client_id'], 'display_name').'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $voucher_details['client_id'], null, $invoice_id);
        
        // Update last active record
        update_client_last_active($voucher_details['client_id']);        
        update_workorder_last_active($voucher_details['workorder_id']);
        update_invoice_last_active($voucher_details);        
        
    }
    
}

/** Close Functions **/

#####################################
#   Refund Voucher                  #
#####################################

function refund_voucher($voucher_id) {
    
    // make sure the voucher can be cancelled
    if(!check_voucher_status_allows_refunding($voucher_id)) {
        
        // Load the relevant invoice page with failed message
        force_page('invoice', 'details&invoice_id='.get_voucher_details($voucher_id, 'invoice_id'), 'warning_msg='._gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be refunded."));
        
    }
    
    // Change the voucher status to refunded (I do this here to maintain consistency)
    update_voucher_status($voucher_id, 'refunded', true);
    
    // Get voucher details before deleting
    $voucher_details = get_voucher_details($voucher_id);    
        
    // Create a Workorder History Note  
    insert_workorder_history_note($voucher_details['voucher_id'], _gettext("Invoice").' '.$voucher_id.' '._gettext("was refunded by").' '.QFactory::getUser()->login_display_name.'.');

    // Log activity        
    $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("for Invoice").' '.$voucher_details['invoice_id'].' '._gettext("was refunded by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_id);

    // Update last active record
    update_client_last_active($voucher_details['client_id']);
    update_workorder_last_active($voucher_details['workorder_id']);
    update_invoice_last_active($voucher_details['invoice_id']);

    return true;
    
}

##############################
#  Cancel Voucher            #  // update and set blocked as you cannot really delete an issued Voucher  
##############################

function cancel_voucher($voucher_id) {     
    
    $voucher_details = get_voucher_details($voucher_id);    
    
    if(!check_voucher_status_allows_cancellation($voucher_id)) {
        
        // Load the relevant invoice page with failed message
        force_page('invoice', 'details&invoice_id='.$voucher_details['invoice_id'], 'warning_msg='._gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be cancelled."));
        
    } else {
        
        // Change the voucher status to cancelled (I do this here to maintain log consistency)
        update_voucher_status($voucher_id, 'cancelled', true);
        
        // Log activity        
        $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id']);

        // Update last active record
        update_client_last_active($voucher_details['client_id']);
        update_workorder_last_active($voucher_details['workorder_id']);
        update_invoice_last_active($voucher_details['invoice_id']);

        return true;        
        
    }
        
}

/** Delete Functions **/

##############################
#  Delete Voucher            #  // remove some information and set blocked as you cannot really delete an issued Voucher  
##############################

function delete_voucher($voucher_id) {     
    
    $db = QFactory::getDbo();
    $voucher_details = get_voucher_details($voucher_id);    
    
    if(!check_voucher_status_allows_deletion($voucher_id)) {
        
        // Load the relevant invoice page with failed message
        force_page('invoice', 'details&invoice_id='.$voucher_details['invoice_id'], 'warning_msg='._gettext("Voucher").': '.$voucher_id.' '._gettext("cannot be deleted."));
        
    } else {
        
        // Change the voucher status to deleted (I do this here to maintain log consistency)
        update_voucher_status($voucher_id, 'deleted', true);
        
        $sql = "UPDATE ".PRFX."voucher_records SET
            voucher_code       =". $db->qstr( $voucher_details['voucher_code']   ).",
            employee_id         =   '',
            client_id           =   '',
            workorder_id        =   '',
            invoice_id          =   '',
            redeemed_client_id  =   '',
            redeemed_invoice_id =   '',
            open_date           =   '0000-00-00 00:00:00',
            expiry_date         =   '0000-00-00 00:00:00',
            redeem_date         =   '0000-00-00 00:00:00',
            close_date          =   '0000-00-00 00:00:00',
            status              =   'deleted',            
            blocked             =   '1',
            amount              =   '0.00',
            note                =   ''
            WHERE voucher_id =". $db->qstr($voucher_id);        

        if(!$db->execute($sql)) {
            
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the Voucher."));

        } else {

            // Recalculate the invoice totals and update them
            recalculate_invoice($voucher_details['invoice_id']);

            // Log activity        
            $record = _gettext("Voucher").' '.$voucher_id.' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.';
            write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id']);

            // Update last active record
            update_client_last_active($voucher_details['client_id']);
            update_workorder_last_active($voucher_details['workorder_id']);
            update_invoice_last_active($voucher_details['invoice_id']);

            return true;

        }
    
    }
        
}

/** Other Functions **/

###########################################################
#  Check if the voucher status is allowed to be changed  #
###########################################################

 function check_voucher_status_can_be_changed($voucher_id) {
     
    // Get the voucher status
    $status = get_voucher_details($voucher_id, 'status');
        
    // Unused and Expired
    if($status == 'unused' && check_voucher_is_expired($voucher_id)) {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has expired."));
        return false;        
    }
    
    // Is Redeemed
    if($status == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been redeemed."));
        return false;        
    }   
    
    // Is Expired
    if($status == 'expired') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has expired."));
        return false;        
    }    
        
    // Is Refunded
    if($status == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($status == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($status == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been deleted."));
        return false;        
    }
    
    // All checks passed
    return true;     
     
}

##############################################################
#  Check if the Voucher can be used for a payment            #
##############################################################

function check_voucher_can_be_redeemed($voucher_id, $redeem_invoice_id) {
    
    $voucher_details = get_voucher_details($voucher_id);
        
    // Voucher can not be used to pay for itself
    if($voucher_details['invoice_id'] == $redeem_invoice_id) {
        //force_page('core','error', 'error_msg='._gettext("This voucher cannot be used to pay for itself."));
        return false;        
    }
    
    // Voucher must have been paid for
    if(get_invoice_details($voucher_details['invoice_id'], 'status') !== 'paid') {
        //force_page('core','error', 'error_msg='._gettext("This voucher has not been paid for."));
        return false;        
    }
    
    // Check if expired - This does a live check for expiry as it is not always upto date
    if(check_voucher_is_expired($voucher_id)) {
        //force_page('core', 'error', 'error_msg='._gettext("This voucher is expired."));        
        return false;        
    }
    
    // Check if unused (any other status causes failure)
    if($voucher_details['status'] !== 'unused') {
        //force_page('core', 'error', 'error_msg='._gettext("This voucher is unused."));
        return false;        
    }    
    
    // Check if blocked
    if($voucher_details['blocked']) {
        //force_page('core','error', 'error_msg='._gettext("This voucher is blocked."));
        return false;        
    }
    
    return true;
    
}

##########################################################
#  Check if the voucher status allows editing            #
##########################################################

 function check_voucher_status_allows_editing($voucher_id) {
     
    // Get the voucher details
    $voucher_details = get_voucher_details($voucher_id);
    
    // Is Redeemed
    if($voucher_details['status'] == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been redeemed."));
        return false;        
    }
        
    // Is Refunded
    if($voucher_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($voucher_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($voucher_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been deleted."));
        return false;        
    }

    // All checks passed
    return true;     
     
}

###############################################################
#   Check to see if the voucher can be refunded               #
###############################################################

function check_voucher_can_be_refunded($voucher_id) {
        
    // This checks the parent invoice and it's associated vouchers including the supplied voucher
    if(!check_invoice_can_be_refunded(get_voucher_details($voucher_id, 'invoice_id'))) {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because the invoice it is attached to, does not allow it."));
        return false;
    }
    
    return true;
    
}

###############################################################
#   Check to see if the voucher status allows refunding       #
###############################################################

function check_voucher_status_allows_refunding($voucher_id) {
    
    // Get the voucher details
    $voucher_details = get_voucher_details($voucher_id);
    
    // Unused and Expired
    if($voucher_details['status'] == 'unused' && check_voucher_is_expired($voucher_id)) {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has expired."));
        return false;        
    }
    
    // Is Redeemed
    if($voucher_details['status'] == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been redeemed."));
        return false;        
    }
        
    // Is Suspended
    if($voucher_details['status'] == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has expired."));
        return false;        
    }  
    
    // Is Expired
    if($voucher_details['status'] == 'expired') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has expired."));
        return false;        
    }
    
    // Is Refunded
    if($voucher_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($voucher_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($voucher_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be changed because it has been deleted."));
        return false;        
    }
    
    // All checks passed
    return true;
    
} 

###############################################################
#   Check to see if the voucher can be cancelled              #  // not currently used
###############################################################

function check_voucher_can_be_cancelled($voucher_id) {
        
    // This checks the parent invoice and it's associated vouchers including the supplied voucher
    if(!check_invoice_can_be_cancelled(get_voucher_details($voucher_id, 'invoice_id'))) {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be cancelled because the invoice it is attached to, does not allow it."));
        return false;
    }
    
    return true;
    
}

###############################################################
#   Check to see if the voucher status allows cancellation    #
###############################################################

function check_voucher_status_allows_cancellation($voucher_id) {
    
    // Get the voucher status
    $voucher_details = get_voucher_details($voucher_id);
    
    // Is Redeemed
    if($voucher_details['status'] == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be cancelled because it has been redeemed."));
        return false;        
    }
    
    // Is Suspended
    if($voucher_details['status'] == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be cancelled because it has been suspended."));
        return false;        
    }
            
    // Is Refunded
    if($voucher_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be cancelled because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($voucher_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be cancelled because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($voucher_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The voucher status cannot be cancelled because it has been deleted."));
        return false;        
    }
    
    
    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the voucher can be deleted                #
###############################################################

function check_voucher_can_be_deleted($voucher_id) {
        
    // This checks the parent invoice and it's associated vouchers including the supplied voucher
    if(!check_invoice_can_be_deleted(get_voucher_details($voucher_id, 'invoice_id'))) {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because the invoice it is attached to, does not allow it."));
        return false;
    }
    
    return true;
    
}

###############################################################
#   Check to see if the voucher status allows deletion        #
###############################################################

function check_voucher_status_allows_deletion($voucher_id) {
    
    // Get the voucher status
    $voucher_details = get_voucher_details($voucher_id);
    
    // Is Redeemed
    if($voucher_details['status'] == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because it has been redeemed."));
        return false;        
    }
    
    // Is Suspended
    if($voucher_details['status'] == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because it is suspended."));
        return false;        
    }
    
    // Is Expired
    if($voucher_details['status'] == 'expired') {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because it has expired."));
        return false;        
    }
            
    // Is Refunded
    if($voucher_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($voucher_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($voucher_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The voucher cannot be deleted because it has been deleted."));
        return false;        
    }    
    
    // All checks passed
    return true;
    
}

############################################
#  Generate Random Voucher code            #
############################################

function generate_voucher_code() {
    
    $acceptedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $max_offset = strlen($acceptedChars)-1;
    $voucher_code = '';
    
    for($i=0; $i < 16; $i++) {
        $voucher_code .= $acceptedChars{mt_rand(0, $max_offset)};
    }
    
    return $voucher_code;
    
}

#################################################
#   Check to see if the voucher is expired      #  // This does a live check to see if the voucher is expired and tagged as such
#################################################

function check_voucher_is_expired($voucher_id) {
    
    $calculated_status = '';
    
    $voucher_details = get_voucher_details($voucher_id);
    
    // If the voucher is expired 
    if (strtotime($voucher_details['expiry_date']) < time() ) {
        
        // If the status is not 'expired', update the status silenty (only from unused)
        if ($voucher_details['status'] == 'unused') {
            update_voucher_status($voucher_id, 'expired', true);      
        }
        
        $calculated_status = 'expired';
    
    }
    
    // If the voucher is not expired
    if (strtotime($voucher_details['expiry_date']) >= time() ) {
        
        //  If the status has not been updated, update the status silenty (only from expired)
        if ($voucher_details['status'] == 'expired') {
            update_voucher_status($voucher_id, 'unused', true);      
        }
        
        $calculated_status = 'unused';
        
    } 
    
    // Return the calulates Expiry state
    if ($calculated_status === 'expired') {
        
        // The voucher is expired
        return true;
        
    } else {
        
        // The voucher is not expired
        return false;
        
    }
    
}


############################################################################
# Check an invoices vouchers do not prevent the invoice getting refunded   #
############################################################################

function check_invoice_vouchers_allow_refunding($invoice_id) {
    
    $db = QFactory::getDbo();

    $vouchers_allow_refunding = true;    
        
    $sql = "SELECT *
            FROM ".PRFX."voucher_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

    } else {

        while(!$rs->EOF) {            
            
            //$voucher_details = $rs->GetRowAssoc();
            
            // Make sure correct expiry status is set (unused/expired)
            check_voucher_is_expired($rs->fields['voucher_id']);

            // Check the Voucher to see if it can be refunded
            if(!check_voucher_status_allows_refunding($rs->fields['voucher_id'])) {                    
                $vouchers_allow_refunding = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        // Check to if any vouchers prevent the invoice from being deleted
        if(!$vouchers_allow_refunding) {            
            //force_page('invoice', 'details&invoice_id='.$invoice_id, 'warning_msg='._gettext("The invoice cannot be refunded because of Voucher").': '.$voucher_details['voucher_id']);                               
            //postEmulationWrite('warning_msg', _gettext("The invoice cannot be refunded because of Voucher").': '.$voucher_details['voucher_id']); 
            return false;
            
        } else {
            
            return true;
            
        }
       
    }

}

############################################################################
# Check an invoices vouchers do not prevent the invoice getting cancelled  #
############################################################################

function check_invoice_vouchers_allow_cancellation($invoice_id) {
    
    $db = QFactory::getDbo();    
    $vouchers_allow_cancellation = true;
    
    $sql = "SELECT *
            FROM ".PRFX."voucher_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

    } else {

        while(!$rs->EOF) {            

            //$voucher_details = $rs->GetRowAssoc(); 
            
            // Make sure correct expiry status is set (unused/expired)
            check_voucher_is_expired($rs->fields['voucher_id']);

            // Check the Voucher to see if it can be deleted
            if(!check_voucher_status_allows_cancellation($rs->fields['voucher_id'])) {                    
                $vouchers_allow_cancellation = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        // Check to if any vouchers prevent the invoice from being deleted
        if(!$vouchers_allow_cancellation) {            
            //force_page('invoice', 'details&invoice_id='.$invoice_id, 'warning_msg='._gettext("The invoice cannot be cancelled because of Voucher").': '.$voucher_details['voucher_id']);                               
            //postEmulationWrite('warning_msg', _gettext("The invoice cannot be cancelled because of Voucher").': '.$voucher_details['voucher_id']); 
            return false;
            
        } else {
            
            return true;
            
        }

    }

}

###########################################################################
# Check an invoices vouchers do not prevent the invoice getting deleted   #
###########################################################################
         
function check_invoice_vouchers_allow_deletion($invoice_id) {
    
    $db = QFactory::getDbo();    
    $vouchers_allow_deletion = true;
    
    $sql = "SELECT *
            FROM ".PRFX."voucher_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

    } else {

        while(!$rs->EOF) {            

            //$voucher_details = $rs->GetRowAssoc();        
            
            // Make sure correct expiry status is set (unused/expired)
            check_voucher_is_expired($rs->fields['voucher_id']);            

            // Check the Voucher to see if it can be deleted
            if(!check_voucher_status_allows_deletion($rs->fields['voucher_id'])) {                    
                $vouchers_allow_deletion = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        // Check to if any vouchers prevent the invoice from being deleted
        if(!$vouchers_allow_deletion) {            
            //force_page('invoice', 'details&invoice_id='.$invoice_id, 'warning_msg='._gettext("The invoice cannot be deleted because of Voucher").': '.$voucher_details['voucher_id']);
            //postEmulationWrite('warning_msg', _gettext("The invoice cannot be deleted because of Voucher").': '.$voucher_details['voucher_id']);
            return false;
            
        } else {
            
            return true;
            
        }

    }

}

##########################################
#  Refund all of an Invoice's Vouchers   #
##########################################

function refund_invoice_vouchers($invoice_id) {
    
    $db = QFactory::getDbo();    
        
    $sql = "SELECT *
            FROM ".PRFX."voucher_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

    } else {

        while(!$rs->EOF) {            

            // Refund Voucher
            refund_voucher($rs->fields['voucher_id']);

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return;

    }

}

##########################################
#  Cancel all of an Invoice's Vouchers   #
##########################################

function cancel_invoice_vouchers($invoice_id) {
    
    $db = QFactory::getDbo();    
        
    $sql = "SELECT *
            FROM ".PRFX."voucher_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

    } else {

        while(!$rs->EOF) {            

            // Cancel Voucher
            cancel_voucher($rs->fields['voucher_id']);

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return;

    }

}

##########################################
#  Delete all of an Invoice's Vouchers   #
##########################################

function delete_invoice_vouchers($invoice_id) {
    
    $db = QFactory::getDbo();    
        
    $sql = "SELECT *
            FROM ".PRFX."voucher_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Vouchers."));

    } else {

        while(!$rs->EOF) {            

            // Refund Voucher
            delete_voucher($rs->fields['voucher_id']);

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return;

    }

}