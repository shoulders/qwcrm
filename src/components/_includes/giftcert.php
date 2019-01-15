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
#     Display Gift Certificates         #
#########################################

function display_giftcerts($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null, $redeemed_client_id = null, $redeemed_invoice_id = null) {

    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'giftcert_id';
    $havingTheseRecords = '';
    
    /* Records Search */
        
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."giftcert_records.giftcert_id\n";
    
    // Restrict results by search category (client) and search term
    if($search_category == 'client_display_name') {$havingTheseRecords .= " HAVING client_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category (redeemed client) and search term
    elseif($search_category == 'redeemed_client_display_name') {$havingTheseRecords .= " HAVING redeemed_client_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$havingTheseRecords .= " HAVING employee_display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}  
    
    /* Filter the Records */
    
    // Restrict by Status
    if($status) {
        
        // All Active Gift Certificates
        if($status == 'active') {
            
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.blocked != '1'";
        
        // All Blocked Gift Certificates
        } elseif($status == 'blocked') {
            
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.blocked = '1'";
        
        // Return Gift Certificates for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."giftcert_records.status= ".$db->qstr($status);
            
        }
        
    }    
    
    // Restrict by Employee
    if($employee_id) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.employee_id=".$db->qstr($employee_id);}
    
    // Restrict by Client
    if($client_id) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.client_id=".$db->qstr($client_id);}
    
    // Restrict by Workorder
    if($workorder_id) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.workorder_id=".$db->qstr($workorder_id);}
    
    // Restrict by Invoice
    if($invoice_id) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.invoice_id=".$db->qstr($invoice_id);}
    
    // Restrict by Redeemed Client
    if($redeemed_client_id) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.redeemed_client_id=".$db->qstr($redeemed_client_id);}
        
    // Restrict by Redeemed Invoice
    if($redeemed_invoice_id) {$whereTheseRecords .= " AND ".PRFX."giftcert_records.redeemed_invoice_id=".$db->qstr($redeemed_invoice_id);}
    
    /* The SQL code */
    
    $sql = "SELECT

        ".PRFX."giftcert_records.*,                            
        IF(".PRFX."client_records.company_name !='', ".PRFX."client_records.company_name, CONCAT(".PRFX."client_records.first_name, ' ', ".PRFX."client_records.last_name)) AS client_display_name,                       
        CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS employee_display_name,            
        IF(redeemed_client_records.company_name !='', redeemed_client_records.company_name, CONCAT(redeemed_client_records.first_name, ' ', redeemed_client_records.last_name)) AS redeemed_client_display_name

        FROM ".PRFX."giftcert_records            
        LEFT JOIN ".PRFX."user_records ON ".PRFX."giftcert_records.employee_id = ".PRFX."user_records.user_id
        LEFT JOIN ".PRFX."client_records ON ".PRFX."giftcert_records.client_id = ".PRFX."client_records.client_id
        LEFT JOIN ".PRFX."client_records AS redeemed_client_records ON ".PRFX."giftcert_records.redeemed_client_id = redeemed_client_records.client_id

        ".$whereTheseRecords."
        GROUP BY ".PRFX."giftcert_records.".$order_by."
        ".$havingTheseRecords."
        ORDER BY ".PRFX."giftcert_records.".$order_by."
        ".$direction;   

    /* Restrict by pages */
    
    if($use_pages) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching Gift Certificate records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificate records."));
        
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
#   Insert Gift Certificate     #
#################################

function insert_giftcert($invoice_id, $date_expires, $amount, $note) {
    
    $db = QFactory::getDbo();
    $invoice_details = get_invoice_details($invoice_id);
    
    $sql = "INSERT INTO ".PRFX."giftcert_records SET 
            giftcert_code   =". $db->qstr( generate_giftcert_code()             ).",  
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            client_id       =". $db->qstr( $invoice_details['client_id']        ).",
            workorder_id    =". $db->qstr( $invoice_details['workorder_id']     ).",
            invoice_id      =". $db->qstr( $invoice_details['invoice_id']       ).",
            date_created    =". $db->qstr( mysql_datetime()                     ).",
            date_expires    =". $db->qstr( date_to_mysql_date($date_expires)    ).",            
            status          =". $db->qstr( 'unused'                             ).",  
            blocked         =". $db->qstr( '0'                                  ).",
            amount          =". $db->qstr( $amount                              ).",
            note            =". $db->qstr( $note                                );

    if(!$db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the Gift Certificate into the database."));

    } else {
        
        $giftcert_id = $db->Insert_ID();
        
        // Recalculate the invoice totals and update them
        recalculate_invoice($invoice_details['invoice_id']);

        // Log activity        
        $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("was created by").' '.QFactory::getUser()->login_display_name.'.';      
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $invoice_details['client_id']);
        
        // Update last active record
        update_client_last_active($invoice_details['client_id']);
        update_workorder_last_active($invoice_details['workorder_id']);
        update_invoice_last_active($invoice_details['invoice_id']);       
        
        return $giftcert_id ;
        
    }
    
}

/** Get Functions **/

##########################
#  Get giftcert details  #
##########################

function get_giftcert_details($giftcert_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."giftcert_records WHERE giftcert_id=".$db->qstr($giftcert_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the Gift Certificate details."));
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#########################################
#   Get giftcert_id by giftcert_code    #
#########################################

function get_giftcert_id_by_gifcert_code($giftcert_code) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."giftcert_records WHERE giftcert_code=".$db->qstr($giftcert_code);

    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the Gift Certificate ID by the Gift Certificate code."));
    }
    
    if($rs->fields['giftcert_id'] != '') {
        return $rs->fields['giftcert_id'];
    } else {
        return false;
    }
    
}

#####################################
#    Get Giftcert Statuses          #
#####################################

function get_giftcert_statuses() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."giftcert_statuses";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get Gift Certificate statuses."));
    } else {
        
        return $rs->GetArray();      
        
    }    
    
}

######################################
#  Get Giftcert status display name  #
######################################

function get_giftcert_status_display_name($status_key) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT display_name FROM ".PRFX."giftcert_statuses WHERE status_key=".$db->qstr($status_key);

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the giftcert status display name."));
    } else {
        
        return $rs->fields['display_name'];
        
    }    
    
}

/** Update Functions **/

#################################
#   Update Gift Certificate     #
#################################

function update_giftcert($giftcert_id, $date_expires, $amount, $note) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."giftcert_records SET     
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            date_expires    =". $db->qstr( date_to_mysql_date($date_expires)    ).",            
            amount          =". $db->qstr( $amount                              ).",
            note            =". $db->qstr( $note                                )."
            WHERE giftcert_id =". $db->qstr($giftcert_id);

    if(!$db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Gift Certificate record in the database."));

    } else {
        
        // Make sure correct expiry status is set (unused/expired) if status allows change
        validate_giftcert_is_expired($giftcert_id);
        
        $giftcert_details = get_giftcert_details($giftcert_id);
        
        // Recalculate the invoice totals and update them
        recalculate_invoice($giftcert_details['invoice_id']);
        
        // Log activity
        $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $giftcert_details['employee_id'], $giftcert_details['client_id']);

        // Update last active record
        update_client_last_active($giftcert_details['client_id']);
        update_workorder_last_active($giftcert_details['workorder_id']);
        update_invoice_last_active($giftcert_details['invoice_id']);
        
        return;
        
    }
    
}

############################
# Update Giftcert Status   #
############################

function update_giftcert_status($giftcert_id, $new_status, $silent = false) {
    
    $db = QFactory::getDbo();
    
    // Get giftcert details
    $giftcert_details = get_giftcert_details($giftcert_id);
    
    // if the new status is the same as the current one, exit
    if($new_status == $giftcert_details['status']) {        
        if (!$silent) { postEmulationWrite('warning_msg', _gettext("Nothing done. The new status is the same as the current status.")); }
        return false;
    }    
    
    $sql = "UPDATE ".PRFX."giftcert_records SET
            status               =". $db->qstr( $new_status  )."            
            WHERE giftcert_id    =". $db->qstr( $giftcert_id );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a gift Certificate Status."));
        
    } else {    
        
        // Update giftcert 'blocked' boolean for the new status
        if($new_status == 'redeemed' || $new_status == 'suspended' || $new_status == 'expired' || $new_status == 'refunded' || $new_status == 'cancelled' || $new_status == 'deleted') {
            update_giftcert_blocked_status($giftcert_id, 'blocked');
        } else {
            update_giftcert_blocked_status($giftcert_id, 'active');
        }
        
        // Status updated message
        if (!$silent) { postEmulationWrite('information_msg', _gettext("Gift Certificate status updated.")); }
        
        // For writing message to log file, get giftcert status display name
        $giftcert_status_diplay_name = _gettext(get_giftcert_status_display_name($new_status));
        
        // Create a Workorder History Note       
        insert_workorder_history_note($giftcert_details['workorder_id'], _gettext("Gift Certificate Status updated to").' '.$giftcert_status_diplay_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("Status updated to").' '.$giftcert_status_diplay_name.' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $giftcert_details['employee_id'], $giftcert_details['client_id'], $giftcert_details['workorder_id'], $giftcert_id);
        
        // Update last active record
        update_client_last_active($giftcert_details['client_id']);
        update_workorder_last_active($giftcert_details['workorder_id']);
        update_invoice_last_active($giftcert_details['invoice_id']);                
        
        return true;
        
    }
    
}

####################################
#  Update Giftcert blocked Status  #
####################################

function update_giftcert_blocked_status($giftcert_id, $new_blocked_status) {
    
    $db = QFactory::getDbo();
    
    if($new_blocked_status == 'active') {
        
        $sql = "UPDATE ".PRFX."giftcert_records SET
                blocked           =". $db->qstr( 0                )."
                WHERE giftcert_id =". $db->qstr( $giftcert_id     );     
        
    }
    
    if($new_blocked_status == 'blocked') {        
        
        $sql = "UPDATE ".PRFX."giftcert_records SET
                blocked           =". $db->qstr( 1                )."
                WHERE giftcert_id =". $db->qstr( $giftcert_id     );
        
    }    
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a gift certificate blocked status."));
    }
    
}

######################################################
#   Redeem the gift certificate against an invoice   #
######################################################

function update_giftcert_as_redeemed($giftcert_id, $redeemed_invoice_id) {
    
    $db = QFactory::getDbo();
    
    $invoice_details = get_invoice_details($redeemed_invoice_id);
    
    $sql = "UPDATE ".PRFX."giftcert_records SET
            employee_id         =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            redeemed_client_id  =". $db->qstr( $invoice_details['client_id']        ).",   
            redeemed_invoice_id =". $db->qstr( $redeemed_invoice_id                 ).",
            date_redeemed       =". $db->qstr( mysql_datetime()                     ).",
            redeemed            =". $db->qstr( 1                                    ).",            
            blocked             =". $db->qstr( 1                                    )."
            WHERE giftcert_id   =". $db->qstr( $giftcert_id                         );
    
    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Gift Certificate as redeemed."));
    } else {
        
        
        $client_details = get_client_details($invoice_details['client_id']);
        
        // Log activity        
        $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("was redeemed by").' '.$client_details['display_name'].'.';
        write_record_to_activity_log($record, QFactory::getUser()->login_user_id, $client_details['client_id'], null, $redeemed_invoice_id);
        
        // Update last active record
        update_client_last_active($client_details['client_id']);        
        update_invoice_last_active($redeemed_invoice_id);
        
    }
    
}

/** Close Functions **/

#####################################
#   Refund Giftcert                 #
#####################################

function refund_giftcert($giftcert_id) {
    
    // make sure the giftcert can be cancelled
    if(!check_giftcert_can_be_refunded($giftcert_id)) {
        
        // Load the relevant invoice page with failed message
        force_page('invoice', 'edit&invoice_id='.get_giftcert_details($giftcert_id, 'invoice_id'), 'warning_msg='._gettext("Gift Certificate").': '.$giftcert_id.' '._gettext("cannot be refunded."));
        
    }
    
    // Change the giftcert status to refunded (I do this here to maintain consistency)
    update_giftcert_status($giftcert_id, 'refunded', true);
    
    // Get giftcert details before deleting
    $giftcert_details = get_giftcert_details($giftcert_id);    
        
    // Create a Workorder History Note  
    insert_workorder_history_note($giftcert_details['giftcert_id'], _gettext("Invoice").' '.$giftcert_id.' '._gettext("was refunded by").' '.QFactory::getUser()->login_display_name.'.');

    // Log activity        
    $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("for Invoice").' '.$giftcert_details['invoice_id'].' '._gettext("was refunded by").' '.QFactory::getUser()->login_display_name.'.';
    write_record_to_activity_log($record, $giftcert_details['employee_id'], $giftcert_details['client_id'], $giftcert_details['workorder_id'], $giftcert_id);

    // Update last active record
    update_client_last_active($giftcert_details['client_id']);
    update_workorder_last_active($giftcert_details['workorder_id']);
    update_invoice_last_active($giftcert_details['invoice_id']);

    return true;
    
}

##############################
#  Cancel Gift Certificate   #  // update and set blocked as you cannot really delete an issued Gift Certificate  
##############################

function cancel_giftcert($giftcert_id) {     
    
    $giftcert_details = get_giftcert_details($giftcert_id);    
    
    if(!check_giftcert_can_be_cancelled($giftcert_id)) {
        
        // Load the relevant invoice page with failed message
        force_page('invoice', 'edit&invoice_id='.$giftcert_details['invoice_id'], 'warning_msg='._gettext("Gift Certificate").': '.$giftcert_id.' '._gettext("cannot be cancelled."));
        
    } else {
        
        // Change the giftcert status to cancelled (I do this here to maintain log consistency)
        update_giftcert_status($giftcert_id, 'cancelled', true);
        
        // Recalculate the invoice totals and update them
        recalculate_invoice($giftcert_details['invoice_id']);

        // Log activity        
        $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("was cancelled by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $giftcert_details['employee_id'], $giftcert_details['client_id']);

        // Update last active record
        update_client_last_active($giftcert_details['client_id']);
        update_workorder_last_active($giftcert_details['workorder_id']);
        update_invoice_last_active($giftcert_details['invoice_id']);

        return true;        
        
    }
        
}

/** Delete Functions **/

##############################
#  Delete Gift Certificate   #  // remove some information and set blocked as you cannot really delete an issued Gift Certificate  
##############################

function delete_giftcert($giftcert_id) {     
    
    $db = QFactory::getDbo();
    $giftcert_details = get_giftcert_details($giftcert_id);    
    
    if(!check_giftcert_can_be_deleted($giftcert_id)) {
        
        // Load the relevant invoice page with failed message
        force_page('invoice', 'edit&invoice_id='.$giftcert_details['invoice_id'], 'warning_msg='._gettext("Gift Certificate").': '.$giftcert_id.' '._gettext("cannot be deleted."));
        
    } else {
        
        // Change the giftcert status to deleted (I do this here to maintain log consistency)
        update_giftcert_status($giftcert_id, 'deleted', true);
        
        $sql = "UPDATE ".PRFX."giftcert_records SET
            giftcert_code       =". $db->qstr( $giftcert_details['giftcert_code']   ).",
            employee_id         =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            client_id           =   NULL,
            workorder_id        =   NULL,
            invoice_id          =   NULL,
            redeemed_client_id  =   NULL,
            redeemed_invoice_id =   NULL,
            date_created        =". $db->qstr( $giftcert_details['date_created']    ).",
            date_expires        =   '0000-00-00',
            date_redeemed       =   '0000-00-00 00:00:00',
            status              =   'deleted',
            redeemed            =   '0',
            blocked             =   '1',
            amount              =   '0.00',
            note                =   NULL
            WHERE giftcert_id =". $db->qstr($giftcert_id);        

        if(!$db->execute($sql)) {
            
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the Gift Certificate."));

        } else {

            // Recalculate the invoice totals and update them
            recalculate_invoice($giftcert_details['invoice_id']);

            // Log activity        
            $record = _gettext("Gift Certificate").' '.$giftcert_id.' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.';
            write_record_to_activity_log($record, $giftcert_details['employee_id'], $giftcert_details['client_id']);

            // Update last active record
            update_client_last_active($giftcert_details['client_id']);
            update_workorder_last_active($giftcert_details['workorder_id']);
            update_invoice_last_active($giftcert_details['invoice_id']);

            return true;

        }
    
    }
        
}

/** Other Functions **/

##############################################################
#  Check if the Gift Certificate can be used for a payment   #
##############################################################

function check_giftcert_can_be_redeemed($giftcert_id, $redeem_invoice_id) {
    
    $giftcert_details = get_giftcert_details($giftcert_id);
        
    // Giftcert can not be used to pay for itself
    if($giftcert_details['invoice_id'] == $redeem_invoice_id) {
        //force_page('core','error', 'error_msg='._gettext("This gift certificate cannot be used to pay for itself."));
        return false;        
    }
    
    // Giftcert must have been paid for
    if(get_invoice_details($giftcert_details['invoice_id'], 'status') !== 'paid') {
        //force_page('core','error', 'error_msg='._gettext("This gift certificate has not been paid for."));
        return false;        
    }

    // Check if blocked
    if($giftcert_details['blocked']) {
        //force_page('core','error', 'error_msg='._gettext("This gift certificate is blocked."));
        return false;        
    }

    // Check if expired - This does a live check for expiry as it is not always upto date
    if(validate_giftcert_is_expired($giftcert_id)) {
        //force_page('core', 'error', 'error_msg='._gettext("This gift certificate is expired."));        
        return false;        
    }
    
    // Check if unused (any other status causes failure)
    if($giftcert_details['status'] !== 'unused') {
        //force_page('core', 'error', 'error_msg='._gettext("This gift certificate is unused."));
        return false;        
    }    
    
    return true;
    
}

##########################################################
#  Check if the giftcert status is allowed to be changed #
##########################################################

 function check_giftcert_can_be_edited($giftcert_id) {
     
    // Get the giftcert details
    $giftcert_details = get_giftcert_details($giftcert_id);
    
    // Is Redeemed
    if($giftcert_details['status'] == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been redeemed."));
        return false;        
    }
        
    // Is Refunded
    if($giftcert_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($giftcert_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($giftcert_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been deleted."));
        return false;        
    }

    // All checks passed
    return true;     
     
}

###########################################################
#  Check if the giftcert status is allowed to be changed  #
###########################################################

 function check_giftcert_status_can_be_changed($giftcert_id) {
     
    // Get the giftcert status
    $status = get_giftcert_details($giftcert_id, 'status');
    
    // Unused and Expired
    if($status == 'unused' && validate_giftcert_is_expired($giftcert_id)) {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has expired."));
        return false;        
    }
    
    // Is Redeemed
    if($status == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been redeemed."));
        return false;        
    }   
    
    // Is Suspended
    if($status == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been suspended."));
        return false;        
    } 
    
    // Is Expired
    if($status == 'expired') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has expired."));
        return false;        
    }    
        
    // Is Refunded
    if($status == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($status == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($status == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been deleted."));
        return false;        
    }

    // All checks passed
    return true;     
     
}

###############################################################
#   Check to see if the giftcert can be refunded              #
###############################################################

function check_giftcert_can_be_refunded($giftcert_id) {
    
    // Get the giftcert details
    $giftcert_details = get_giftcert_details($giftcert_id);
    
    // Unused and Expired
    if($giftcert_details['status'] == 'unused' && validate_giftcert_is_expired($giftcert_id)) {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has expired."));
        return false;        
    }
    
    // Is Redeemed
    if($giftcert_details['status'] == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been redeemed."));
        return false;        
    }
        
    // Is Suspended
    if($giftcert_details['status'] == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has expired."));
        return false;        
    }  
    
    // Is Expired
    if($giftcert_details['status'] == 'expired') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has expired."));
        return false;        
    }
    
    // Is Refunded
    if($giftcert_details['status'] == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($giftcert_details['status'] == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($giftcert_details['status'] == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be changed because it has been deleted."));
        return false;        
    }
        

    // All checks passed
    return true;
    
} 

###############################################################
#   Check to see if the giftcert can be cancelled             #
###############################################################

function check_giftcert_can_be_cancelled($giftcert_id) {
    
    // Get the giftcert status
    $status = get_giftcert_details($giftcert_id, 'status');
    
    // Is Redeemed
    if($status == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be cancelled because it has been redeemed."));
        return false;        
    }
    
    // Is Suspended
    if($status == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be cancelled because it has been suspended."));
        return false;        
    }
            
    // Is Refunded
    if($status == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be cancelled because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($status == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be cancelled because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($status == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be cancelled because it has been deleted."));
        return false;        
    }

    // All checks passed
    return true;
    
}

###############################################################
#   Check to see if the giftcert can be cancelled             #
###############################################################

function check_giftcert_can_be_deleted($giftcert_id) {
    
    // Get the giftcert status
    $status = get_giftcert_details($giftcert_id, 'status');
    
    // Is Redeemed
    if($status == 'redeemed') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be deleted because it has been redeemed."));
        return false;        
    }
    
    // Is Suspended
    if($status == 'suspended') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be deleted because it is suspended."));
        return false;        
    }
    
    // Is Expired
    if($status == 'expired') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be deleted because it has expired."));
        return false;        
    }
            
    // Is Refunded
    if($status == 'refunded') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be deleted because it has been refunded."));
        return false;        
    }
    
    // Is Cancelled
    if($status == 'cancelled') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be deleted because it has been cancelled."));
        return false;        
    }
    
    // Is Deleted
    if($status == 'deleted') {
        //postEmulationWrite('warning_msg', _gettext("The gift certificate status cannot be deleted because it has been deleted."));
        return false;        
    }

    // All checks passed
    return true;
    
}

############################################
#  Generate Random Gift Certificate code   #
############################################

function generate_giftcert_code() {
    
    $acceptedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $max_offset = strlen($acceptedChars)-1;
    $giftcert_code = '';
    
    for($i=0; $i < 16; $i++) {
        $giftcert_code .= $acceptedChars{mt_rand(0, $max_offset)};
    }
    
    return $giftcert_code;
    
}

#################################################
#   Check to see if the giftcert is expired     #  // This does a live check to see if the giftcert is expired and tagged as such
#################################################

function validate_giftcert_is_expired($giftcert_id) {
    
    $calculated_status = '';
    
    $giftcert_details = get_giftcert_details($giftcert_id);
    
    // If the giftcert is expired 
    if (strtotime($giftcert_details['date_expires']) < time() ) {
        
        // If the status is not 'expired', update the status silenty (only from unused)
        if ($giftcert_details['status'] == 'unused') {
            update_giftcert_status($giftcert_id, 'expired', true);      
        }
        
        $calculated_status = 'expired';
    
    }
    
    // If the giftcert is not expired
    if (strtotime($giftcert_details['date_expires']) >= time() ) {
        
        //  If the status has not been updated, update the status silenty (only from expired)
        if ($giftcert_details['status'] == 'expired') {
            update_giftcert_status($giftcert_id, 'unused', true);      
        }
        
        $calculated_status = 'unused';
        
    } 
    
    // Return the calulates Expiry state
    if ($calculated_status === 'expired') {
        
        // The giftcert is expired
        return true;
        
    } else {
        
        // The giftcert is not expired
        return false;
        
    }
    
}

#####################################
#   Sum Giftcert Invoice Sub Total  #  // All statuses should be summed up, deleted giftcerts do not have an invoice_id anyway so are ignored
#####################################

function giftcerts_sub_total($invoice_id) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT SUM(amount) AS sub_total_sum FROM ".PRFX."giftcert_records WHERE invoice_id=" . $db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to calculate the invoice gift certificate sub total."));
    } else {
        
        return  $rs->fields['sub_total_sum'];
        
    }
  
}


############################################################################
# Check an invoices giftcerts do not prevent the invoice getting refunded  #
############################################################################

function check_invoice_giftcerts_allow_refunding($invoice_id) {
    
    $db = QFactory::getDbo();    
    $allow_state = true;
    
    $sql = "SELECT *
            FROM ".PRFX."giftcert_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            // Check the Giftcert to see if it can be deleted
            if(!check_giftcert_can_be_refunded($rs->fields['giftcert_id'])) {                    
                $allow_state = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return $allow_state;

    }

}

############################################################################
# Check an invoices giftcerts do not prevent the invoice getting cancelled #
############################################################################

function check_invoice_giftcerts_allow_cancellation($invoice_id) {
    
    $db = QFactory::getDbo();    
    $giftcerts_allow_cancellation = true;
    
    $sql = "SELECT *
            FROM ".PRFX."giftcert_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            $giftcert_details = $rs->GetRowAssoc();        

            // Check the Giftcert to see if it can be deleted
            if(!check_giftcert_can_be_deleted($giftcert_details['giftcert_id'])) {                    
                $giftcerts_allow_cancellation = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        // Check to if any giftcerts prevent the invoice from being deleted
        if(!$giftcerts_allow_cancellation) {            
            force_page('invoice', 'edit&invoice_id='.$invoice_id, 'warning_msg='._gettext("The invoice cannot be deleted because of Gift Certificate").': '.$giftcert_details['giftcert_id']);                               
        } else {
            
            return true;
            
        }

    }

}

###########################################################################
# Check an invoices giftcerts do not prevent the invoice getting deleted  #
###########################################################################
         
function check_invoice_giftcerts_allow_deletion($invoice_id) {
    
    $db = QFactory::getDbo();    
    $giftcerts_allow_deletion = true;
    
    $sql = "SELECT *
            FROM ".PRFX."giftcert_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            $giftcert_details = $rs->GetRowAssoc();        

            // Check the Giftcert to see if it can be deleted
            if(!check_giftcert_can_be_deleted($giftcert_details['giftcert_id'])) {                    
                $giftcerts_allow_deletion = false;
            }

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        // Check to if any giftcerts prevent the invoice from being deleted
        if(!$giftcerts_allow_deletion) {            
            force_page('invoice', 'edit&invoice_id='.$invoice_id, 'warning_msg='._gettext("The invoice cannot be deleted because of Gift Certificate").': '.$giftcert_details['giftcert_id']);                               
        } else {
            
            return true;
            
        }

    }

}

##########################################
#  Refund all of an Invoice's Giftcerts  #
##########################################

function refund_invoice_giftcerts($invoice_id) {
    
    $db = QFactory::getDbo();    
        
    $sql = "SELECT *
            FROM ".PRFX."giftcert_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            // Refund Giftcert
            refund_giftcert($rs->fields['giftcert_id']);

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return;

    }

}

##########################################
#  Cancel all of an Invoice's Giftcerts  #
##########################################

function cancel_invoice_giftcerts($invoice_id) {
    
    $db = QFactory::getDbo();    
        
    $sql = "SELECT *
            FROM ".PRFX."giftcert_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            // Refund Giftcert
            cancel_giftcert($rs->fields['giftcert_id']);

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return;

    }

}

##########################################
#  Delete all of an Invoice's Giftcerts  #
##########################################

function delete_invoice_giftcerts($invoice_id) {
    
    $db = QFactory::getDbo();    
        
    $sql = "SELECT *
            FROM ".PRFX."giftcert_records
            WHERE invoice_id = ".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {

        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            // Refund Giftcert
            delete_giftcert($rs->fields['giftcert_id']);

            // Advance the loop to the next record
            $rs->MoveNext();           

        }
        
        return;

    }

}