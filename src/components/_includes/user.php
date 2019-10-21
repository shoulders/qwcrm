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
 * Login
 * Reset Password
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

#####################################
#    Display Users                  #  // 'display_name' and 'full_name' are the same. This is usability issues.
#####################################

function display_users($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $usergroup = null, $usertype = null, $status = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'user_id';
    $havingTheseRecords = '';
       
    /* Records Search */
        
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."user_records.user_id\n";
    
    // Search category (display) and search term
    if($search_category == 'display_name') {$havingTheseRecords .= " HAVING display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."user_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');}
    
    /* Filter the Records */
        
    // Restrict results by usergroup
    if($usergroup) {$whereTheseRecords .= " AND ".PRFX."user_records.usergroup =".$db->qstr($usergroup);}
    
    // Restrict results by user type
    if($usertype && !$usergroup) {
        
        if($usertype == 'client') { 
            $whereTheseRecords .= " AND ".PRFX."user_records.usergroup =".$db->qstr(7);}            
        
        if($usertype == 'employee') {
            
            $whereTheseRecords .= " AND ".PRFX."user_records.usergroup =".$db->qstr(1);
            $whereTheseRecords .= " OR ".PRFX."user_records.usergroup =".$db->qstr(2);
            $whereTheseRecords .= " OR ".PRFX."user_records.usergroup =".$db->qstr(3);
            $whereTheseRecords .= " OR ".PRFX."user_records.usergroup =".$db->qstr(4);
            $whereTheseRecords .= " OR ".PRFX."user_records.usergroup =".$db->qstr(5);
            $whereTheseRecords .= " OR ".PRFX."user_records.usergroup =".$db->qstr(6);
            
        }
        
    }
    
    // Restrict by Status (is null because using boolean/integer)
    if(!is_null($status)) {$whereTheseRecords .= " AND ".PRFX."user_records.active=".$db->qstr($status);}  
    
    /* The SQL code */    
    
    $sql = "SELECT
            ".PRFX."user_records.*,
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS display_name,
            CONCAT(".PRFX."user_records.first_name, ' ', ".PRFX."user_records.last_name) AS full_name,
            ".PRFX."user_usergroups.display_name
                
            FROM ".PRFX."user_records
            LEFT JOIN ".PRFX."user_usergroups ON (".PRFX."user_records.usergroup = ".PRFX."user_usergroups.usergroup_id)
            ".$whereTheseRecords."
            GROUP BY ".PRFX."user_records.".$order_by."
            ".$havingTheseRecords."
            ORDER BY ".PRFX."user_records.".$order_by."
            ".$direction; 
   
    /* Restrict by pages */
        
    if($use_pages) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the number of matching user records."));
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
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching user records."));
        
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

#####################################
#    Insert new user                #
#####################################

function insert_user($qform) {
    
    $db = QFactory::getDbo();
    
    $sql = "INSERT INTO ".PRFX."user_records SET
            client_id           =". $db->qstr( $qform['client_id']                            ).", 
            username            =". $db->qstr( $qform['username']                             ).",
            password            =". $db->qstr( \Joomla\CMS\User\UserHelper::hashPassword($qform['password'])  ).",
            email               =". $db->qstr( $qform['email']                                ).",
            usergroup           =". $db->qstr( $qform['usergroup']                            ).",
            active              =". $db->qstr( $qform['active']                               ).",
            register_date       =". $db->qstr( mysql_datetime()                             ).",   
            require_reset       =". $db->qstr( $qform['require_reset']                        ).",
            is_employee         =". $db->qstr( $qform['is_employee']                          ).", 
            first_name          =". $db->qstr( $qform['first_name']                           ).",
            last_name           =". $db->qstr( $qform['last_name']                            ).",
            work_primary_phone  =". $db->qstr( $qform['work_primary_phone']                   ).",
            work_mobile_phone   =". $db->qstr( $qform['work_mobile_phone']                    ).",
            work_fax            =". $db->qstr( $qform['work_fax']                             ).",                    
            home_primary_phone  =". $db->qstr( $qform['home_primary_phone']                   ).",
            home_mobile_phone   =". $db->qstr( $qform['home_mobile_phone']                    ).",
            home_email          =". $db->qstr( $qform['home_email']                           ).",
            home_address        =". $db->qstr( $qform['home_address']                         ).",
            home_city           =". $db->qstr( $qform['home_city']                            ).",  
            home_state          =". $db->qstr( $qform['home_state']                           ).",
            home_zip            =". $db->qstr( $qform['home_zip']                             ).",
            home_country        =". $db->qstr( $qform['home_country']                         ).", 
            based               =". $db->qstr( $qform['based']                                ).",  
            note                =". $db->qstr( $qform['note']                                 );                     
          
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the user record into the database."));
    } else {
        
        // Get user_id
        $user_id = $db->Insert_ID();
        
        // Update last active record        
        update_client_last_active($qform['client_id']);        
        
        // Log activity
        if($qform['client_id']) {
            $user_type = _gettext("Client");
        } else {
            $user_type = _gettext("Employee");
        }        
        $record = _gettext("User Account").' '.$user_id.' ('.$user_type.') '.'for'.' '.get_user_details($user_id, 'display_name').' '._gettext("created").'.';
        write_record_to_activity_log($record, $user_id);
                
        return $user_id;
        
    }
    
}

/** Get Functions **/

#####################################
#     Get User Details              #  // 'display_name' and 'full_name' are the same. This is usability issues.
#####################################

function get_user_details($user_id = null, $item = null) {
    
    $db = QFactory::getDbo();
    
    // This allows for workorder:status to work
    if(!$user_id){
        return;        
    }
    
    $sql = "SELECT * FROM ".PRFX."user_records WHERE user_id =".$db->qstr($user_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the user details."));
    } else {
        
        if($item === null) {
            
            $results = $rs->GetRowAssoc();
            
            // Add these dynamically created fields           
            $results['display_name'] = $results['first_name'].' '.$results['last_name'];
            $results['full_name'] = $results['first_name'].' '.$results['last_name'];
            
            return $results;
            
        } else {
            
            // Return the dynamically created 'display_name'
            if($item == 'display_name') {
                $results = $rs->GetRowAssoc();
                return $results['first_name'].' '.$results['last_name'];
            }
            
            // Return the dynamically created 'full_name'
            if($item == 'full_name') {
                $results = $rs->GetRowAssoc();
                return $results['first_name'].' '.$results['last_name'];
            }
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#########################################
# Get User ID by username               # // moved from core
#########################################

/* 
 * Not used in core anywhere
 * it was used for getting user specific stats in theme_header_block.php
 * $user->login_user_id is not set via the auth session
 * i will leave this here just for now
 * no longer needed as I stored the id in the session
 * 
 */

function get_user_id_by_username($username) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT user_id FROM ".PRFX."user_records WHERE username =".$db->qstr($username);
    
    if(!$rs = $db->execute($sql)){
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the User ID by their username."));
    } else {
        
        return $rs->fields['user_id'];
        
    }
        
}

#########################################
# Get User ID by username               #
#########################################

function get_user_id_by_email($email) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT user_id FROM ".PRFX."user_records WHERE email =".$db->qstr($email);
    
    if(!$rs = $db->execute($sql)){
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the User ID by their email."));
    } else {
        
        $result_count = $rs->RecordCount();
                
        if($result_count != 1) {
            
            return false;
            
        } else {
            
            return $rs->fields['user_id'];
            
        }
        
    }
        
}

##################################
# Get the usergroups             #
##################################

function get_usergroups($user_type = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."user_usergroups";
    
    // Filter the results by user type client/employee
    if($user_type === 'employees') {$sql .= " WHERE user_type='1'";}
    if($user_type === 'clients')   {$sql .= " WHERE user_type='2'";}    
    if($user_type === 'other')     {$sql .= " WHERE user_type='3'";}
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the usergroups."));
    } else {
        
        return $rs->GetArray();
        
    }
    
}

##################################################
# Get all active users display name and ID       #
##################################################
    
function get_active_users($user_type = null) {  
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT        
            user_id,
            CONCAT(first_name, ' ', last_name) AS display_name

            FROM ".PRFX."user_records
            WHERE active='1'";
    
    // Filter the results by user type client/employee
    if($user_type === 'clients')   {$sql .= " AND is_employee='0'";}
    if($user_type === 'employees') {$sql .= " AND is_employee='1'";}
       
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the active users."));
    } else {    
        
        return $rs->GetArray();    
        
    }
    
}

##################################################
# Get all active users display name and ID       #
##################################################
    
function get_user_locations() {  
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."user_locations";
       
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the user locations."));
    } else {    
        
        return $rs->GetArray();    
        
    }
    
}

/** Update Functions **/

#########################
#   Update Employee     #
#########################

function update_user($qform) {
    
    $db = QFactory::getDbo();
    
    $sql = "UPDATE ".PRFX."user_records SET        
            username            =". $db->qstr( $qform['username']                             ).",
            email               =". $db->qstr( $qform['email']                                ).",
            usergroup           =". $db->qstr( $qform['usergroup']                            ).",
            active              =". $db->qstr( $qform['active']                               ).",                    
            require_reset       =". $db->qstr( $qform['require_reset']                        ).",               
            first_name          =". $db->qstr( $qform['first_name']                           ).",
            last_name           =". $db->qstr( $qform['last_name']                            ).",
            work_primary_phone  =". $db->qstr( $qform['work_primary_phone']                   ).",
            work_mobile_phone   =". $db->qstr( $qform['work_mobile_phone']                    ).",
            work_fax            =". $db->qstr( $qform['work_fax']                             ).",                    
            home_primary_phone  =". $db->qstr( $qform['home_primary_phone']                   ).",
            home_mobile_phone   =". $db->qstr( $qform['home_mobile_phone']                    ).",
            home_email          =". $db->qstr( $qform['home_email']                           ).",
            home_address        =". $db->qstr( $qform['home_address']                         ).",
            home_city           =". $db->qstr( $qform['home_city']                            ).",  
            home_state          =". $db->qstr( $qform['home_state']                           ).",
            home_zip            =". $db->qstr( $qform['home_zip']                             ).",
            home_country        =". $db->qstr( $qform['home_country']                         ).",
            based               =". $db->qstr( $qform['based']                                ).",  
            note                =". $db->qstr( $qform['note']                                 )."
            WHERE user_id= ".$db->qstr($qform['user_id']);

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the user record."));
    } else {
        
        // Reset user password if required
        if($qform['password']) {
            reset_user_password($qform['user_id'], $qform['password']);
        }
        
        // Update last active record
        update_user_last_active($qform['user_id']);
        update_client_last_active(get_user_details($qform['user_id'], 'client_id'));        
        
        // Log activity        
        $record = _gettext("User Account").' '.$qform['user_id'].' ('.get_user_details($qform['user_id'], 'display_name').') '._gettext("updated.");
        write_record_to_activity_log($record, $qform['user_id']);
        
        return true;
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete User                    #
#####################################

function delete_user($user_id) {
    
    $db = QFactory::getDbo();
    
    // get user details before deleting
    $user_details = get_user_details($user_id);
    
    // Make sure the client can be deleted 
    if(!check_user_can_be_deleted($user_id)) {        
        return false;
    }
    
    /* we can now delete the user */
    
    // Delete User account
    $sql = "DELETE FROM ".PRFX."user_records WHERE user_id=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete the user from the database."));
    }
    
    // Log activity        
    $record = _gettext("User Account").' '.$user_id.' ('.$user_details['display_name'].') '._gettext("deleted.");
    write_record_to_activity_log($record, $user_id);

    // Update last active record
    update_client_last_active($user_details['client_id']);    
        
    return true;
    
}

/** Other Functions **/

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
 * I will use Smarty for this feature
 * 
 */

function build_active_employee_form_option_list($assigned_user_id) {
    
    $db = QFactory::getDbo();
    
    // select all employees and return their display name and ID as an array
    $sql = "SELECT
            CONCAT(".PRFX."first_name, ' ', ".PRFX."last_name) AS display_name,
            user_id
        
            FROM ".PRFX."user_records
            WHERE active=1 AND is_employee=1";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed build and return and User list."));
    } else {
        
        // Get ADODB to build the form using the loaded dataset
        return $rs->GetMenu2('assign_user', $assigned_user_id, false);
        
    }
    
}

#################################################
#    Check if username already exists           #
#################################################

function check_user_username_exists($username, $current_username = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // This prevents self-checking of the current username of the record being edited
    if ($current_username != null && $username === $current_username) {return false;}
    
    $sql = "SELECT username FROM ".PRFX."user_records WHERE username =". $db->qstr($username);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the username exists."));
    } else {
        
        $result_count = $rs->RecordCount();
        
        if($result_count >= 1) {
            
            $smarty->assign('warning_msg', _gettext("The Username")." '".$username."' "._gettext("already exists! Please use a different one."));
            
            return true;
            
        } else {
            
            return false;
            
        }        
        
    } 
    
}

######################################################
#  Check if an email address has already been used   #
######################################################

function check_user_email_exists($email, $current_email = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // This prevents self-checking of the current username of the record being edited
    if ($current_email != null && $email === $current_email) {return false;}
    
    $sql = "SELECT email FROM ".PRFX."user_records WHERE email =". $db->qstr($email);
    
    if(!$rs = $db->Execute($sql)) {
        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the email address has been used."));
        
    } else {
        
        $result_count = $rs->RecordCount();
        
        if($result_count >= 1) {
            
            $smarty->assign('warning_msg', _gettext("The email address has already been used. Please use a different one."));
            
            return true;
            
        } else {
            
            return false;
            
        }        
        
    } 
    
}

#################################################
#    Check if user already has login            #
#################################################

function check_client_already_has_login($client_id) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    $sql = "SELECT user_id FROM ".PRFX."user_records WHERE client_id =". $db->qstr($client_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the client already has a login."));
    } else {
        
        $result_count = $rs->RecordCount();
        
        if($result_count >= 1) {
            
            $smarty->assign('warning_msg', _gettext("The client already has a login."));           
            
            return true;
            
        } else {
            
            return false;
            
        }        
        
    }     
    
}

#####################################
#    Reset a user's password        #    
#####################################

function reset_user_password($user_id, $password = null) { 
    
    $db = QFactory::getDbo();
    
    // if no password supplied generate a random one
    if($password == null) { $password = \Joomla\CMS\User\UserHelper::genRandomPassword(16); }
    
    $sql = "UPDATE ".PRFX."user_records SET
            password        =". $db->qstr( \Joomla\CMS\User\UserHelper::hashPassword($password) ).",
            require_reset   =". $db->qstr( 0                                    ).",   
            last_reset_time =". $db->qstr( mysql_datetime()                     ).",
            reset_count     =". $db->qstr( 0                                    )."
            WHERE user_id   =". $db->qstr( $user_id                             );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to add password reset authorization."));
        
    } else {
        
        // Log activity        
        $record = _gettext("User Account").' '.$user_id.' ('.get_user_details($user_id, 'display_name').') '._gettext("password has been reset.");
        write_record_to_activity_log($record, $user_id);
        
        // Update last active record
        update_user_last_active($user_id);
        update_client_last_active(get_user_details($user_id, 'client_id'));
                
        return;
        
    }      
    
}

#####################################
#    Reset all user's passwords     #   // used for migrations or security
#####################################

function reset_all_user_passwords() { 
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT user_id FROM ".PRFX."user_records";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to read all users from the database."));
        
    } else {
        
        // Loop through all users
        while(!$rs->EOF) { 
            
            // Reset User's password
            reset_user_password($rs->fields['user_id']);
            
            // Advance the INSERT loop to the next record            
            $rs->MoveNext();            
            
        }
        
        // Log activity        
        write_record_to_activity_log(_gettext("All User Account passwords have been reset."));
        
        return;
        
    }      
    
}

/* Login */

####################################
#  Login authentication function   #
####################################

function login($qform, $credentials, $options = array())
{   
    $smarty = QFactory::getSmarty();   
    
    // If username or password is missing
    if (!isset($credentials['username']) || $credentials['username'] == '' || !isset($credentials['password']) || $credentials['password'] == '') {
        
        // Set error message
        $smarty->assign('warning_msg', _gettext("Username or Password Missing."));
        
        return false;
        
    } 
    
    // Does the account require the password to be reset, if so force it
    if(get_user_details(get_user_id_by_username($qform['login_username']), 'require_reset')) {
        
        // Set error message
        $smarty->assign('warning_msg', _gettext("You must reset your password before you are allowed to login."));
        
        return false;
        
    }
    
    // If user is blocked - QFramework returns True for a blocked user, but does blocks it.
    if(get_user_details(get_user_id_by_username($qform['login_username']), 'active') === '0') {  

        // Set error message
        $smarty->assign('warning_msg', _gettext("Login denied! Your account has either been blocked or you have not activated it yet."));

        // Log activity       
        write_record_to_activity_log(_gettext("Login denied for").' '.$qform['login_username'].'.');

        return false;

    }
    
    if(QFactory::getAuth()->login($credentials, $options)) {

        /* Login Successful */

        $user = QFactory::getUser();       

        // Log activity       
        $record = _gettext("Login successful for").' '.$user->login_username.'.';
        write_record_to_activity_log($record, $user->login_user_id);        
        
        // Update last active record        
        update_client_last_active($user->login_client_id);        

        // set success message to survice the login event
        postEmulationWrite('information_msg', _gettext("Login successful."));
        
        return true;

    } else {

        /* Login failed */
        
        // Log activity       
        write_record_to_activity_log(_gettext("Login unsuccessful for").' '.$credentials['username'].'.');

        $smarty->assign('warning_msg', _gettext("Login Failed. Check you username and password."));
        
        return false;

    }
}

####################################
#  Login authentication function   # // could add silent to logout?
####################################

function logout($silent = null)        
{   
    $user = QFactory::getUser();
    
    // Build logout message (while user details exist)
    $record = _gettext("Logout successful for").' '.$user->login_username.'.';
    
    // Logout
    QFactory::getAuth()->logout();    

    // Log activity       
    write_record_to_activity_log($record, $user->login_user_id);
    
    // Update last active record 
    update_user_last_active($user->login_user_id);
    update_client_last_active($user->login_client_id);
    
    // Action after logout
    if($silent) {
        
        // No message or redirect
        
        return;        
   
    } else {
        
        // Reload Homepage with message (default)
        
        // only $_GET will work because the session store is destroyed (this is good behaviour)
        force_page('index.php', null, 'information_msg='._gettext("Logout successful."), 'get');
        
    }

} 

####################################
#  Logout all online users         #  // This terminates sessions fo those currently connected (Logged in and Guests). This does not handle users with 'remember me' enabled. 
####################################

function logout_all_users($except_me = false) {
    
    //truncate something like `#__user_keys` destroys the remember_me link, the session kills the imediate session
    
    $db = QFactory::getDbo();

    // Logout all users
    if(!$except_me) {

        // Sessions
        $sql = "TRUNCATE ".PRFX."session";
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to empty the Session table."));
        }
        
        // Remember Me
        $sql = "TRUNCATE ".PRFX."user_keys";
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to empty the Remember Me table."));
        }

    // Delete all sessions except the currently logged in user 
    } else {
        
        $sql = "DELETE FROM ".PRFX."session WHERE userid <> ".$db->qstr(QFactory::getUser()->login_user_id);
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to empty the Session table."));
        }
        
        $sql = "DELETE FROM ".PRFX."user_keys WHERE userid <> ".$db->qstr(QFactory::getUser()->login_user_id);
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to empty the Remember Me table."));
        }

    }
    
    return;
        
}

/* Reset Password */

#####################################
#    Verify submitted reCAPTCHA     #    
#####################################

function authenticate_recaptcha($recaptcha_secret_key, $recaptcha_response) {
    
    // Load ReCaptcha library       
    $recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret_key);
    
    // Get response from Google
    $response = $recaptcha->verify($recaptcha_response, $_SERVER['REMOTE_ADDR']);
    
    //  and if successfull authenticate
    if ($response->isSuccess()) {
        
        // Success
        return true;
        
    } else {
        
        $smarty = QFactory::getSmarty();        
        
        /* If it's not successful, then one or more error codes will be returned.      
        $error_msg .= '<h2>Something went wrong</h2>';
        $error_msg .= '<p>The following error was returned:';
            foreach ($response->getErrorCodes() as $error_code) {
                $error_msg .= '<kbd>'.$error_code.'</kbd> ';
            }
        $error_msg .= '</p>';
        $error_msg .= '<p>Check the error code reference at <kbd><a href="https://developers.google.com/recaptcha/docs/verify#error-code-reference">https://developers.google.com/recaptcha/docs/verify#error-code-reference</a></kbd>.';
        $error_msg .= '<p><strong>Note:</strong> Error code <kbd>missing-input-response</kbd> may mean the user just didn\'t complete the reCAPTCHA.</p>';
        $error_msg .= '<p><a href="/">Try again</a></p>';*/        
        
        $smarty->assign('warning_msg', _gettext("Google reCAPTCHA Verification Failed."));
        return false;
        
    }  
    
}

######################################################################################
#    Validate that the email submitted belongs to a valid account and can be reset   #    
######################################################################################

function validate_reset_email($email) {
    
    // get the user_id if the user exists
    if(!$user_id = get_user_id_by_email($email)) {
        return false;        
    }
    
    // is the user active
    if(!get_user_details($user_id, 'active')) {
        return false;
    }
    
    return $user_id;
    
}

#####################################
#    Build and send a reset email   #    
#####################################

function send_reset_email($user_id) {
    
    // Get recipient email
    $recipient_email = get_user_details($user_id, 'email');
            
    // Set subject  
    $subject = _gettext("Your QWcrm password reset request");    
        
    // Create Token
    $token = create_reset_token($user_id);
    
    /* Build Email body
    $body = '';
    
    $body .= _gettext("Hello").','."\r\n\r\n";

    $body .= _gettext("A request has been made to reset your QWcrm account password.").' ';
    $body .= _gettext("To reset your password, you will need to submit this verification code in order to verify that the request was legitimate.")."\r\n\r\n";

    $body .= _gettext("The verification code is").' '.$token."\r\n\r\n";

    $body .= _gettext("Select the URL below and proceed with resetting your password.")."\r\n\r\n";

    $body .= QWCRM_PROTOCOL. QWCRM_DOMAIN . QWCRM_BASE_PATH."index.php?component=user&page_tpl=reset&token=".$token."\r\n\r\n";
        
    $body .= _gettext("Thank you.");*/
    
    
    // Build Email body
    $body = '';
    
    $body .= '<p>'._gettext("Hello").','.'</p>';
    
    $body .= '<p>'._gettext("A request has been made to reset your QWcrm account password.").' ';
    $body .= _gettext("To reset your password, you will need to submit this verification code in order to verify that the request was legitimate.").'</p>';
    
    $body .= '<p>'._gettext("The verification code is").' '.$token.'</p>';
    
    $body .= '<p>'._gettext("Select the URL below and proceed with resetting your password.").'</p>';
    
    $body .= '<p>'. QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH ."index.php?component=user&page_tpl=reset&token=".$token.'</p>';  
    
    $body .= '<p>'._gettext("Thank you.").'</p>';    
    
    // Send Reset Email (no onscreen notifications to prevent headers already sent error)
    send_email($recipient_email, $subject, $body, null, null, null, null, null, null, true);
    
    // Log activity        
    $record = _gettext("User Account").' '.$user_id.' ('.get_user_details($user_id, 'display_name').') '._gettext("reset email has been sent.");
    write_record_to_activity_log($record, $user_id);
    
    return;
    
}

###################################################################################
#   Set time limited reset code to allow new passwords to be submitted securely   #
###################################################################################

function authorise_password_reset($token) {
    
    $db = QFactory::getDbo();
          
    $reset_code = \Joomla\CMS\User\UserHelper::genRandomPassword(64);   // 64 character token
    $reset_code_expiry_time = time() + (60 * 5);                        // sets a 5 minute expiry time
    
    $sql = "UPDATE ".PRFX."user_reset
            SET
            reset_code              =". $db->qstr( $reset_code              ).",
            reset_code_expiry_time  =". $db->qstr( $reset_code_expiry_time  )."            
            WHERE token             =". $db->qstr( $token                   );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to add password reset authorization."));
    } else{
        
        return $reset_code;
        
    }    
    
}

#####################################
#    create a reset user token      #    
#####################################

function create_reset_token($user_id) {
    
    $db = QFactory::getDbo();
    
    // check for previous tokens for this user and delete them
    $sql = "SELECT * FROM ".PRFX."user_reset WHERE user_id=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check for existing tokens for the submitted user."));
    } else {        
        $result_count = $rs->RecordCount();       
    } 
    
    // Delete any reset tokens for this user
    if($result_count >= 1) {
        
        delete_user_reset_code($user_id);
        
    }
    
    // Insert a new token
    $expiry_time = time() + (60 * 15);              // 15 minute expiry time
    $token = \Joomla\CMS\User\UserHelper::genRandomPassword(64);    // 64 character token
    
    $sql = "INSERT INTO ".PRFX."user_reset SET              
            user_id         =". $db->qstr( $user_id     ).", 
            expiry_time     =". $db->qstr( $expiry_time ).",   
            token           =". $db->qstr( $token       );                     
          
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the user reset token into the database."));
    }
    
    // Return the token
    return $token;    
    
}

#########################################
# Get User ID by reset code             #
#########################################

function get_user_id_by_reset_code($reset_code) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT user_id FROM ".PRFX."user_reset WHERE reset_code =".$db->qstr($reset_code);
    
    if(!$rs = $db->execute($sql)){
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the User ID by secret code."));
    } else {
        
        return $rs->fields['user_id'];
        
    }
        
}

##############################################
#    validate the reset token can be used    #    
##############################################

function validate_reset_token($token) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // check for previous tokens for this user and delete them
    $sql = "SELECT * FROM ".PRFX."user_reset WHERE token =".$db->qstr($token);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check for existing tokens for the submitted user."));
    } else {
        
        // Check there is only 1 record
        if($rs->RecordCount() != 1) {
            $smarty->assign('warning_msg', _gettext("The reset token does not exist."));
            return false;
        }
        
        // check if user is blocked        
        if(!get_user_details($rs->fields['user_id'], 'active')){
            $smarty->assign('warning_msg', _gettext("The user is blocked."));
            return false;
        }
        
        // Check not expired
        if($rs->fields['expiry_time'] < time()){
            $smarty->assign('warning_msg', _gettext("The reset token has expired."));
            return false;
        }
        
        // All checked passed
        $smarty->assign('information_msg', _gettext("Token accepted."));
        return true;
        
        
    }
    
}

#########################################################
#   validate reset code - submitted with password form  #
#########################################################

function validate_reset_code($reset_code) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Check for previous tokens for this user and delete them
    $sql = "SELECT * FROM ".PRFX."user_reset WHERE reset_code =".$db->qstr($reset_code);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check for the submitted reset code."));
    } else {
        
        // Check there is only 1 record
        if($rs->RecordCount() != 1) {            
            $smarty->assign('warning_msg', 'The reset code does not exist.');
            return false;
        }
        
        // Check not expired
        if($rs->fields['reset_code_expiry_time'] < time()){
            $smarty->assign('warning_msg', 'The reset code has expired.');
            return false;
        }
        
        // All checked passed
        $smarty->assign('information_msg', _gettext("Reset code accepted."));        
        return true;
        
        
    }
    
}

##########################################
#    Delete user reset codes             #
##########################################

function delete_user_reset_code($user_id) {  
    
    $db = QFactory::getDbo();

    $sql = "DELETE FROM ".PRFX."user_reset WHERE user_id = ".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete existing tokens for the submitted user."));
    }
    
}

##########################################
#    Delete all expired reset codes      #
##########################################

function delete_expired_reset_codes() {   
    
    $db = QFactory::getDbo();

    $sql = "DELETE FROM ".PRFX."user_reset WHERE expiry_time < ".$db->qstr( time() );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete existing tokens for the submitted user."));
    }
    
}


#####################################
#    Update users reset count       #    
#####################################

 function update_user_reset_count($user_id) {
     
    $db = QFactory::getDbo();
     
    $sql = "UPDATE ".PRFX."user_records SET       
            reset_count     = reset_count + 1
            WHERE user_id   =". $db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to add password reset authorization."));
        
    } else{
        
        return;
        
    }
     
 }
 
###############################################################
#   Check to see if the user can be deleted                   #
###############################################################

function check_user_can_be_deleted($user_id) {
    
    $db = QFactory::getDbo();
    
    // Get the user details
    $user_details = get_user_details($user_id);
    
    // User cannot delete their own account
    if($user_id == QFactory::getUser()->login_user_id) {
        postEmulationWrite('warning_msg', _gettext("You can not delete your own account."));        
        return false;
    }
    
    // Cannot delete this account if it is the last administrator account
    if($user_details['usergroup'] == '1') {
        
        $sql = "SELECT count(*) as count FROM ".PRFX."user_records WHERE usergroup = '1'";    
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the users in the administrator usergroup."));
        }  
        if($rs->fields['count'] <= 1 ) {
            postEmulationWrite('warning_msg', _gettext("You can not delete the last administrator user account."));        
            return false;
        }        
    }

    // Check if user has created any workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."workorder_records WHERE created_by=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the user's Workorders in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', _gettext("You can not delete a user who has created work orders."));        
        return false;
    }
    
    // Check if user has any assigned workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."workorder_records WHERE employee_id=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the user's Workorders in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', _gettext("You can not delete a user who has assigned work orders."));
        return false;
    }
    
    // Check if user has any invoices
    $sql = "SELECT count(*) as count FROM ".PRFX."invoice_records WHERE employee_id=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the user's Invoices in the database."));
    }    
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', _gettext("You can not delete a user who has invoices."));
        return false;
    }    
    
    // Check if user is assigned to any Vouchers
    $sql = "SELECT count(*) as count FROM ".PRFX."voucher_records WHERE employee_id=".$db->qstr($user_id);
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the user's Vouchers in the database."));
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', _gettext("You can not delete a user who has Vouchers."));
        return false;
    }
     
    // All checks passed
    return true;
    
}