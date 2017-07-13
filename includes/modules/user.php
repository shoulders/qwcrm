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

#####################################
#    Display Users                  #
#####################################

function display_users($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $status = null, $user_type = null, $usergroup = null) {
    
    // should status and usergroup be at the end of the variables supplied?
    
    
    global $smarty;

    /* Filter the Records */
        
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."user.user_id";    
    
    // Restrict results by search category and search term
    if($search_term != null) {$whereTheseRecords .= " AND ".PRFX."user.$search_category LIKE '%$search_term%'";}    
        
    // Restrict by Status
    if($status != null) {$whereTheseRecords .= " AND ".PRFX."user.status=".$db->qstr($status);}  
    
    // Restrict results by user type
    if($user_type != null) {
        
        if($user_type == 'customers') { 
            $whereTheseRecords .= " AND ".PRFX."user.usergroup =".$db->qstr('7');}            
        
        if($user_type == 'employees') {
            
            $whereTheseRecords .= " AND ".PRFX."user.usergroup =".$db->qstr('1');
            $whereTheseRecords .= " OR ".PRFX."user.usergroup =".$db->qstr('2');
            $whereTheseRecords .= " OR ".PRFX."user.usergroup =".$db->qstr('3');
            $whereTheseRecords .= " OR ".PRFX."user.usergroup =".$db->qstr('4');
            $whereTheseRecords .= " OR ".PRFX."user.usergroup =".$db->qstr('5');
            $whereTheseRecords .= " OR ".PRFX."user.usergroup =".$db->qstr('6');
            
        }
    }
         
    // Restrict results by usergroup
    if($usergroup != null) {$whereTheseRecords .= " AND ".PRFX."user.usergroup =".$db->qstr($usergroup);}
    
    /* The SQL code */    
    
    $sql = "SELECT
        ".PRFX."user.*,
        ".PRFX."user_usergroups.usergroup_display_name   
        FROM ".PRFX."user
        LEFT JOIN ".PRFX."user_usergroups ON (".PRFX."user.usergroup = ".PRFX."user_usergroups.usergroup_id)
        ".$whereTheseRecords."
        GROUP BY ".PRFX."user.user_id
        ORDER BY ".PRFX."user.user_id
        ".$direction;  
   
    /* Restrict by pages */
        
    if($use_pages == true) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the number of matching user records."));
            exit;
        } else {        
            $total_results = $rs->RecordCount();            
            $smarty->assign('total_results', $total_results);
        }  
        
        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results / $records_per_page);
        $smarty->assign('total_pages', $total_pages);

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
        $rs = '';
    
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }
  
    /* Return the records */
         
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching user records."));
        exit;
        
    } else {        
        
        $records = $rs->GetArray();   // If I call this twice for this search, no results are shown on the TPL

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** New/Insert Functions **/

#####################################
#    insert new Employee            #
#####################################

function insert_user($db, $VAR){
    
    $sql = "INSERT INTO ".PRFX."user SET
            user_id             =". $db->qstr( $VAR['user_id']                              ).",
            username            =". $db->qstr( $VAR['username']                             ).",
            password            =". $db->qstr( JUserHelper::hashPassword($VAR['password'])  ).",
            email               =". $db->qstr( $VAR['email']                                ).",
            usergroup           =". $db->qstr( $VAR['usergroup']                            ).",
            status              =". $db->qstr( $VAR['status']                               ).",            
            require_reset       =". $db->qstr( $VAR['require_reset']                        ).",
            is_employee         =". $db->qstr( $VAR['is_employee']                          ).", 
            customer_id         =". $db->qstr( $VAR['customer_id']                          ).",   
            display_name        =". $db->qstr( $VAR['display_name']                         ).",
            first_name          =". $db->qstr( $VAR['first_name']                           ).",
            last_name           =". $db->qstr( $VAR['last_name']                            ).",
            work_phone          =". $db->qstr( $VAR['work_phone']                           ).",
            work_mobile_phone   =". $db->qstr( $VAR['work_mobile_phone']                    ).",
            work_fax            =". $db->qstr( $VAR['work_fax']                             ).",                    
            home_phone          =". $db->qstr( $VAR['home_phone']                           ).",
            home_mobile_phone   =". $db->qstr( $VAR['home_mobile_phone']                    ).",
            home_email          =". $db->qstr( $VAR['home_email']                           ).",
            home_address        =". $db->qstr( $VAR['home_address']                         ).",
            home_city           =". $db->qstr( $VAR['home_city']                            ).",  
            home_state          =". $db->qstr( $VAR['home_state']                           ).",
            home_zip            =". $db->qstr( $VAR['home_zip']                             ).",
            based               =". $db->qstr( $VAR['based']                                ).",  
            notes               =". $db->qstr( $VAR['notes']                                );                     
          
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the user record into the database."));
        exit;
    } else {
        
        return $db->insert_id();        
        
    }
    
}

/** Get Functions **/

#####################################
#     Get User Details              #
#####################################

function get_user_details($db, $user_id, $item = null) {
    
    $sql = "SELECT * FROM ".PRFX."user WHERE user_id =".$user_id;
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the user details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc();
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#####################################
# Get User Display Name from ID #  // not actually used anywhere
#####################################

function get_user_display_name_by_id($db, $user_id) {
    
    $sql = "SELECT 
            ".PRFX."user.*,
            ".PRFX."user_usergroups.usergroup_display_name
            FROM ".PRFX."user
            LEFT JOIN ".PRFX."user_usergroups ON (".PRFX."user.usergroup = ".PRFX."user_usergroups.usergroup_id)
            WHERE user_id=". $db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the User Display Name by ID."));
        exit;
    } else {        

        return $rs->fields['display_name'];
        
    }
    
}

#########################################
# Get User ID by username               # // moved from core
#########################################

/* 
 * Not used in core anywhere
 * it was used for getting user specific stats in theme_header_block.php
 * $login_user_id / $login_username is not set via the auth session
 * i will leave this here just for now
 * no longer needed as I stored the id in the session
 * 
 */

function get_user_id_by_username($db, $username){
    
    $sql = "SELECT user_id FROM ".PRFX."user WHERE username =".$db->qstr($username);    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the User ID by their username."));
        exit;
    } else {
        
        return $rs->fields['EMPLOYEE_ID'];
        
    }
        
}

#########################################
# Get user record by username           #  // does not seem to be used anywhere
#########################################

function get_user_record_by_username($db, $username){
    
    $sql = "SELECT * FROM ".PRFX."user WHERE username =".$db->qstr($username);    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the user record by username"));
        exit;
    } else {
        
        return $rs->FetchRow();
        
    }
    
}

##################################
# Get the usergroups             #
##################################

function get_usergroups($db, $user_type = null) {
    
    $sql = "SELECT * FROM ".PRFX."user_usergroups";
    
    // Filter the results by user type customer/employee
    if($user_type === 'employees') {$sql .= " WHERE user_type='1'";}
    if($user_type === 'customers') {$sql .= " WHERE user_type='2'";}    
    if($user_type === 'other')     {$sql .= " WHERE user_type='3'";}
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the usergroups."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }
    
}

##################################################
# Get all active employees display name and ID   #
##################################################
    
function get_active_users($db, $user_type = null) {
    
    
    $sql = "SELECT user_id, display_name FROM ".PRFX."user WHERE status=1";
    
    // Filter the results by user type customer/employee
    if($user_type === 'customers') {$sql .= " AND is_employee='0'";}
    if($user_type === 'employees') {$sql .= " AND is_employee='1'";}
       
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the active users."));
        exit;
    } else {    
        
        return $rs->GetArray();    
        
    }
    
}

/** Update Functions **/

#########################
#   Update Employee     #
#########################

function update_user($db, $user_id, $VAR) {
    
        $set .="    SET
                    username            =". $db->qstr( $VAR['username']                             ).",";

    if($VAR['password'] != '') {
        $set .="    password           =". $db->qstr( JUserHelper::hashPassword($VAR['password'])   ).",";
    }

        $set .="    email               =". $db->qstr( $VAR['email']                                ).",
                    usergroup           =". $db->qstr( $VAR['usergroup']                            ).",
                    status              =". $db->qstr( $VAR['status']                               ).",                    
                    require_reset       =". $db->qstr( $VAR['require_reset']                        ).",
                    is_employee         =". $db->qstr( $VAR['is_employee']                          ).", 
                    customer_id         =". $db->qstr( $VAR['customer_id']                          ).",          
                    display_name        =". $db->qstr( $VAR['display_name']                         ).",
                    first_name          =". $db->qstr( $VAR['first_name']                           ).",
                    last_name           =". $db->qstr( $VAR['last_name']                            ).",
                    work_phone          =". $db->qstr( $VAR['work_phone']                           ).",
                    work_mobile_phone   =". $db->qstr( $VAR['work_mobile_phone']                    ).",
                    work_fax            =". $db->qstr( $VAR['work_fax']                             ).",                    
                    home_phone          =". $db->qstr( $VAR['home_phone']                           ).",
                    home_mobile_phone   =". $db->qstr( $VAR['home_mobile_phone']                    ).",
                    home_email          =". $db->qstr( $VAR['home_email']                           ).",
                    home_address        =". $db->qstr( $VAR['home_address']                         ).",
                    home_city           =". $db->qstr( $VAR['home_city']                            ).",  
                    home_state          =". $db->qstr( $VAR['home_state']                           ).",
                    home_zip            =". $db->qstr( $VAR['home_zip']                             ).",
                    based               =". $db->qstr( $VAR['based']                                ).",  
                    notes               =". $db->qstr( $VAR['notes']                                );

    $sql = "UPDATE ".PRFX."user ". $set ." WHERE user_id= ".$db->qstr($user_id);

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the user record."));
        exit;
    } else{
        
        return true;
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

#################################################
# Count a User's Work Orders for a given status #
#################################################

function count_user_workorders_with_status($db, $user_id, $workorder_status){
    
    $sql = "SELECT COUNT(*) AS user_workorder_status_count
            FROM ".PRFX."workorder
            WHERE WORK_ORDER_ASSIGN_TO=".$db->qstr($user_id)."
            AND WORK_ORDER_STATUS=".$db->qstr($workorder_status);
    
    if(!$rs = $db->Execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the number of Work Orders for the user for the defined status"));
        exit;
   } else {
       
       return $rs->fields['user_workorder_status_count'];
       
   }
   
}

###############################################
# Count Employee Invoices for a given status  #
###############################################

function count_user_invoices_with_status($db, $user_id, $invoice_status){
    
    $sql = "SELECT COUNT(*) AS user_invoice_count
            FROM ".PRFX."invoice
            WHERE IS_PAID=".$db->qstr($invoice_status)."
            AND EMPLOYEE_ID=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed count the number of invoices for the user for the defined status."));
        exit;
   } else {
       
       return $rs->fields['user_invoice_count'];
       
   }
   
}

##############################################
#   Build an active employee <option> list   #  // keep for reference
##############################################

/*
 * This utilises the ADODB PHP Framework for building a <option> list from the supplied data set.
 * 
 * Build <option></option> list for a <form></form> to select employee for 'Assign To' feature
 * GetMenu2('assign_employee_val, null, false') will turn off the blank option
 * GetMenu2('dataset values', 'default option', 'blank 1st record' )
 * 
 * The assigned employee is the default option selected
 * 
 */

function build_active_employee_form_option_list($db, $assigned_user_id){
    
    // select all employees and return their display name and ID as an array
    $sql = "SELECT display_name, user_id FROM ".PRFX."user WHERE status=1 AND is_employee=1";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed build and return and User list."));
        exit;
    } else {
        
        // Get ADODB to build the form using the loaded dataset
        return $rs->GetMenu2('assign_user', $assigned_user_id, false);
        
    }
    
}

#################################################
#    Check if username already exists           #
#################################################

function check_user_username_exists($db, $username, $current_username){
    
    global $smarty;
    
    // This prevents self-checking of the current username of the record being edited
    if ($username === $current_username) {return false;}
    
    $sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."user WHERE username =". $db->qstr($username);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if the username exists."));
        exit;
    } else {
        
        if ($rs->fields['num_users'] >= 1) {
            
            $smarty->assign('warning_msg', 'The Username, '.$username.',  already exists! Please use a different one.');
            
            return true;
            
        } else {
            
            return false;
            
        }        
        
    } 
    
}

    
#################################################
#    Check if user is an employee or customer   #  // is this needed as it is just a boolean
#################################################

function check_user_is_employee($db, $user_id) {
    
    if(get_user_details($db, $user_id, 'is_employee')) {
        return true;
    } else {
        return false;
    }    
    
}