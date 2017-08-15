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
#    Display Users                  #
#####################################

function display_users($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $status = null, $user_type = null, $usergroup = null) {
    
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
            username            =". $db->qstr( $VAR['username']                             ).",
            password            =". $db->qstr( JUserHelper::hashPassword($VAR['password'])  ).",
            email               =". $db->qstr( $VAR['email']                                ).",
            usergroup           =". $db->qstr( $VAR['usergroup']                            ).",
            status              =". $db->qstr( $VAR['status']                               ).",
            register_date       =". $db->qstr( time()                                       ).",   
            require_reset       =". $db->qstr( $VAR['require_reset']                        ).",
            is_employee         =". $db->qstr( $VAR['is_employee']                          ).", 
            customer_id         =". $db->qstr( $VAR['customer_id']                          ).",   
            display_name        =". $db->qstr( $VAR['display_name']                         ).",
            first_name          =". $db->qstr( $VAR['first_name']                           ).",
            last_name           =". $db->qstr( $VAR['last_name']                            ).",
            work_primary_phone  =". $db->qstr( $VAR['work_primary_phone']                   ).",
            work_mobile_phone   =". $db->qstr( $VAR['work_mobile_phone']                    ).",
            work_fax            =". $db->qstr( $VAR['work_fax']                             ).",                    
            home_primary_phone  =". $db->qstr( $VAR['home_primary_phone']                   ).",
            home_mobile_phone   =". $db->qstr( $VAR['home_mobile_phone']                    ).",
            home_email          =". $db->qstr( $VAR['home_email']                           ).",
            home_address        =". $db->qstr( $VAR['home_address']                         ).",
            home_city           =". $db->qstr( $VAR['home_city']                            ).",  
            home_state          =". $db->qstr( $VAR['home_state']                           ).",
            home_zip            =". $db->qstr( $VAR['home_zip']                             ).",
            home_country        =". $db->qstr( $VAR['home_country']                         ).", 
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
        
        return $rs->fields['user_id'];
        
    }
        
}

#########################################
# Get User ID by username               # // moved from core
#########################################

function get_user_id_by_email($db, $email) {
    
    $sql = "SELECT user_id FROM ".PRFX."user WHERE email =".$db->qstr($email);
    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the User ID by their email."));
        exit;
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
# Get all active users display name and ID       #
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
    
    $sql = "UPDATE ".PRFX."user SET
        username            =". $db->qstr( $VAR['username']                             ).",
        email               =". $db->qstr( $VAR['email']                                ).",
        usergroup           =". $db->qstr( $VAR['usergroup']                            ).",
        status              =". $db->qstr( $VAR['status']                               ).",                    
        require_reset       =". $db->qstr( $VAR['require_reset']                        ).",
        is_employee         =". $db->qstr( $VAR['is_employee']                          ).", 
        customer_id         =". $db->qstr( $VAR['customer_id']                          ).",          
        display_name        =". $db->qstr( $VAR['display_name']                         ).",
        first_name          =". $db->qstr( $VAR['first_name']                           ).",
        last_name           =". $db->qstr( $VAR['last_name']                            ).",
        work_primary_phone  =". $db->qstr( $VAR['work_primary_phone']                   ).",
        work_mobile_phone   =". $db->qstr( $VAR['work_mobile_phone']                    ).",
        work_fax            =". $db->qstr( $VAR['work_fax']                             ).",                    
        home_primary_phone  =". $db->qstr( $VAR['home_primary_phone']                   ).",
        home_mobile_phone   =". $db->qstr( $VAR['home_mobile_phone']                    ).",
        home_email          =". $db->qstr( $VAR['home_email']                           ).",
        home_address        =". $db->qstr( $VAR['home_address']                         ).",
        home_city           =". $db->qstr( $VAR['home_city']                            ).",  
        home_state          =". $db->qstr( $VAR['home_state']                           ).",
        home_zip            =". $db->qstr( $VAR['home_zip']                             ).",
        home_country        =". $db->qstr( $VAR['home_country']                         ).",
        based               =". $db->qstr( $VAR['based']                                ).",  
        notes               =". $db->qstr( $VAR['notes']                                )."
        WHERE user_id= ".$db->qstr($user_id);

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the user record."));
        exit;
    } else{
        
        // reset user password if required
        if($VAR['password'] != '') {
            reset_user_password($db, $user_id, $VAR['password']);
        }
        
        return true;
        
    }
    
}

#######################################
#    Update User's Last Active Date   #  // This is in include.php
#######################################

/*function update_user_last_active($db, $user_id) {
    
    $sql = "UPDATE ".PRFX."user SET LAST_ACTIVE=".$db->qstr(time())." WHERE USER_ID=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update a User's last active time."));
        exit;
    }
    
}*/

/** Close Functions **/

/** Delete Functions **/

#####################################
#    Delete User                    #
#####################################

function delete_user($db, $user_id) {
    
    // User cannot delete their own account
    if($user_id == QFactory::getUser()->login_user_id) {
        postEmulationWrite('warning_msg', gettext("You can not delete your own account."));        
        return false;
    }
    
    // Cannot delete this account if it is the last administrator account
    if(get_user_details($db, $user_id, 'usergroup') == '7') {
        
        $sql = "SELECT count(*) as count FROM ".PRFX."user WHERE usergroup = '7'";    
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the users in the administrator usergroup."));
            exit;
        }  
        if($rs->fields['count'] <= 1 ) {
            postEmulationWrite('warning_msg', gettext("You can not delete the last administrator user account."));        
            return false;
        }
    }

    // Check if user has created any workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."workorder WHERE created_by=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the user's Workorders in the database."));
        exit;
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', gettext("You can not delete a user who has created work orders."));        
        return false;
    }
    
    // Check if user has any assigned workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."workorder WHERE employee_id=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the user's Workorders in the database."));
        exit;
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', gettext("You can not delete a user who has assigned work orders."));
        return false;
    }
    
    // Check if user has any invoices
    $sql = "SELECT count(*) as count FROM ".PRFX."invoice WHERE employee_id=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the user's Invoices in the database."));
        exit;
    }    
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', gettext("You can not delete a user who has invoices."));
        return false;
    }    
    
    // Check if user is assigned to any gift certificates
    $sql = "SELECT count(*) as count FROM ".PRFX."giftcert WHERE employee_id=".$db->qstr($user_id);
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the user's Gift Certificates in the database."));
        exit;
    }  
    if($rs->fields['count'] > 0 ) {
        postEmulationWrite('warning_msg', gettext("You can not delete a user who has gift certificates."));
        return false;
    }
    
    /* we can now delete the user */
    
    // Delete User account
    $sql = "DELETE FROM ".PRFX."user WHERE user_id=".$db->qstr($user_id);    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the user from the database."));
        exit;
    }
        
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

function check_user_username_exists($db, $username, $current_username = null){
    
    global $smarty;
    
    // This prevents self-checking of the current username of the record being edited
    if ($current_username != null && $username === $current_username) {return false;}
    
    $sql = "SELECT username FROM ".PRFX."user WHERE username =". $db->qstr($username);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if the username exists."));
        exit;
    } else {
        
        $result_count = $rs->RecordCount();
        
        if($result_count >= 1) {
            
            $smarty->assign('warning_msg', gettext("The Username").', '.$username.' ,'.gettext("already exists! Please use a different one."));
            
            return true;
            
        } else {
            
            return false;
            
        }        
        
    } 
    
}

######################################################
#  Check if an email address has already been used   #
######################################################

function check_user_email_exists($db, $email, $current_email = null){
    
    global $smarty;
    
    // This prevents self-checking of the current username of the record being edited
    if ($current_email != null && $email === $current_email) {return false;}
    
    $sql = "SELECT email FROM ".PRFX."user WHERE email =". $db->qstr($email);
    
    if(!$rs = $db->Execute($sql)) {
        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if the email address has been used."));
        exit;
        
    } else {
        
        $result_count = $rs->RecordCount();
        
        if($result_count >= 1) {
            
            $smarty->assign('warning_msg', gettext("The email address has already been used. Please use a different one."));
            
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

#################################################
#    Check if user already has login            #
#################################################

function check_customer_already_has_login($db, $customer_id) {
    
    global $smarty;
    
    $sql = "SELECT user_id FROM ".PRFX."user WHERE customer_id =". $db->qstr($customer_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if the customer already has a login."));
        exit;
    } else {
        
        $result_count = $rs->RecordCount();
        
        if($result_count >= 1) {
            
            $smarty->assign('warning_msg', gettext("The customer already has a login."));           
            
            return true;
            
        } else {
            
            return false;
            
        }        
        
    }     
    
}


#################################################
#    Check if user is active/enabled            #  // If user does nto exist it will return false
#################################################

function is_user_active($db, $user_id) {   
        
    if(get_user_details($db, $user_id, 'status')) {        
        return true;
    } else {        
        return false;
    }    
    
}

#####################################
#    reset a user's password        #    
#####################################

function reset_user_password($db, $user_id, $password) { 
    
    $sql = "UPDATE ".PRFX."user SET
            password        =". $db->qstr( JUserHelper::hashPassword($password) ).",
            require_reset   =". $db->qstr( 0                                    ).",   
            last_reset_time =". $db->qstr( time()                               ).",
            reset_count     =". $db->qstr( 0                                    )."
            WHERE user_id   =". $db->qstr( $user_id                             );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to add password reset authorization."));
        exit;
    } else{
        
        return;
        
    }      
    
}

#################################################
#    Check if user must change their password   #
#################################################

function require_password_reset($db, $user_id) {
    
    return get_user_details($db, $user_id, 'require_reset');
    
}

/* Login */

####################################
#  Login authentication function   #
####################################

function login($credentials, $options = array())
{   
    
    global $smarty;
    
    // If username or password is missing
    if (!isset($credentials['username']) || $credentials['username'] == '' || !isset($credentials['password']) || $credentials['password'] == '') {
        
        $smarty->assign('warning_msg', gettext("Username or Password Missing."));
        return false;
        
    } 

    $auth = QFactory::getAuth();

    if($auth->login($credentials, $options)) {

        /* Login Successful */

        $user = QFactory::getUser();

        // Log activity       
        write_record_to_activity_log(gettext("Login successful for").' '.$user->login_username); 

        // set success message to survice the login event
        postEmulationWrite('information_msg', gettext("Login successful."));
        
        return true;

    } else {

        /* Login failed */

        $smarty->assign('warning_msg', gettext("Login Failed. Check you username and password."));
        return false;

    }
}

####################################
#  Login authentication function   # // could add silent to logout?
####################################

function logout($silent = null)
{                    
    // Build logout message while user details exist
    $record = gettext("Logout successful for").' '.QFactory::getUser()->login_username;
    
    // Logout
    QFactory::getAuth()->logout();    

    // Log activity       
    write_record_to_activity_log($record);        

    // Reload Homepage
    if($silent) {
        
        // Without message
        force_page('index.php');
        exit;
        
    } else {
        
        // With message - only $_GET will work because the session store is destroyed (this is good behaviour)
        force_page('index.php?information_msg='.gettext("Logout successful."));
        exit;
        
    }    

} 

/* Reset Password */

#####################################
#    Verify submitted reCAPTCHA     #    
#####################################

function authenticate_recaptcha($recaptcha_secret_key, $recaptcha_response) {
    
    // Load ReCaptcha library
    require_once(LIBRARIES_DIR.'recaptchalib.php');    
    $reCaptcha = new ReCaptcha($recaptcha_secret_key);
    
    // Get response from Google
    $response = $reCaptcha->verifyResponse($_SERVER['REMOTE_ADDR'], $recaptcha_response);
    
    //  and if successfull authenticate
    if ($response != null && $response->success) {
        
        // Success
        return true;
        
    } else {
        
        // Failed
        global $smarty;
        $smarty->assign('warning_msg', gettext("Google reCAPTCHA Verification Failed."));
        return false;
        
    }  
    
}

######################################################################################
#    Validate that the email submitted belongs to a valid account and can be reset   #    
######################################################################################

function validate_reset_email($db, $email) {
    
    // get the user_id if the user exists
    if(!$user_id = get_user_id_by_email($db, $email)) {
        return false;        
    }
    
    // is the user active
    if(!is_user_active($db, $user_id)) {
        return false;
    }
    
    return $user_id;
    
}

#####################################
#    Build and send a reset email   #    
#####################################

function send_reset_email($db, $user_id) {
    
    // Get recipient email
    $recipient_email = get_user_details($db, $user_id, 'email');
            
    // Set subject  
    $subject = gettext("Your QWcrm password reset request");    
        
    // Create Token
    $token = create_reset_token($db, $user_id);
    
    /* Build Email body
    $body .= gettext("Hello").','."\r\n\r\n";

    $body .= gettext("A request has been made to reset your QWcrm account password.").' ';
    $body .= gettext("To reset your password, you will need to submit this verification code in order to verify that the request was legitimate.")."\r\n\r\n";

    $body .= gettext("The verification code is").' '.$token."\r\n\r\n";

    $body .= gettext("Select the URL below and proceed with resetting your password.")."\r\n\r\n";

    $body .= QWCRM_PROTOCOL. QWCRM_DOMAIN . QWCRM_PATH."index.php?page=user:reset&token=".$token."\r\n\r\n";
        
    $body .= gettext("Thank you.");*/
    
    
    // Build Email body
    $body .= '<p>'.gettext("Hello").','.'</p>';
    
    $body .= '<p>'.gettext("A request has been made to reset your QWcrm account password.").' ';
    $body .= gettext("To reset your password, you will need to submit this verification code in order to verify that the request was legitimate.").'</p>';
    
    $body .= '<p>'.gettext("The verification code is").' '.$token.'</p>';
    
    $body .= '<p>'.gettext("Select the URL below and proceed with resetting your password.").'</p>';
    
    $body .= '<p>'. QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_PATH ."index.php?page=user:reset&token=".$token.'</p>';  
    
    $body .= '<p>'.gettext("Thank you.").'</p>';    
    
    // Send Reset Email    
    send_email($recipient_email, $subject, $body);
    
}

###################################################################################
#   Set time limited reset code to allow new passwords to be submitted securely   #
###################################################################################

function authorise_password_reset($db, $token) {
          
    $reset_code = JUserHelper::genRandomPassword(64);     // 64 character token
    $reset_code_expiry_time = time() + (60 * 5);          // sets a 5 minute expiry time
    
    $sql = "UPDATE ".PRFX."user_reset
            SET
            reset_code              =". $db->qstr( $reset_code              ).",
            reset_code_expiry_time  =". $db->qstr( $reset_code_expiry_time  )."                   
            
            WHERE token= ".$db->qstr($token);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to add password reset authorization."));
        exit;
    } else{
        
        return $reset_code;
        
    }    
    
}

#####################################
#    create a reset user token      #    
#####################################

function create_reset_token($db, $user_id) {
    
    // check for previous tokens for this user and delete them
    $sql = "SELECT * FROM ".PRFX."user_reset WHERE user_id=".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check for existing tokens for the submitted user."));
        exit;
    } else {        
        $result_count = $rs->RecordCount();       
    } 
    
    // Delete any reset tokens for this user
    if($result_count >= 1) {
        
        delete_user_reset_code($db, $user_id);
        
    }
    
    // Insert a new token
    $expiry_time = time() + (60 * 15);              // 15 minute expiry time
    $token = JUserHelper::genRandomPassword(64);    // 64 character token
    
    $sql = "INSERT INTO ".PRFX."user_reset SET              
            user_id         =". $db->qstr( $user_id     ).", 
            expiry_time     =". $db->qstr( $expiry_time ).",   
            token           =". $db->qstr( $token       );                     
          
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the user reset token into the database."));
        exit;
    }
    
    // Return the token
    return $token;    
    
}

#########################################
# Get User ID by reset code             #
#########################################

function get_user_id_by_reset_code($db, $reset_code) {
    
    $sql = "SELECT user_id FROM ".PRFX."user_reset WHERE reset_code =".$db->qstr($reset_code);
    
    if(!$rs = $db->execute($sql)){
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the User ID by secret code."));
        exit;
    } else {
        
        return $rs->fields['user_id'];
        
    }
        
}

##############################################
#    validate the reset token can be used    #    
##############################################

function validate_reset_token($db, $token) {
    
    global $smarty;
    
    // check for previous tokens for this user and delete them
    $sql = "SELECT * FROM ".PRFX."user_reset WHERE token=".$db->qstr($token);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check for existing tokens for the submitted user."));
        exit;
    } else {
        
        // Check there is only 1 record
        if($rs->RecordCount() != 1) {
            $smarty->assign('warning_msg', 'The reset token does not exist.');
            return false;
        }
        
        // check if user is block
        if(!is_user_active($db, $rs->fields['user_id'])){
            $smarty->assign('warning_msg', 'The user is blocked.');
            return false;
        }
        
        // Check not expired
        if($rs->fields['expiry_time'] < time()){
            $smarty->assign('warning_msg', 'The reset token has expired.');
            return false;
        }
        
        // All checked passed
        $smarty->assign('information_msg', gettext("Token Accepted."));
        return true;
        
        
    }
    
}

#########################################################
#    validate reset code - submitted with password form #
#########################################################

function validate_reset_code($db, $reset_code) {
    
    global $smarty;
    
    // Check for previous tokens for this user and delete them
    $sql = "SELECT * FROM ".PRFX."user_reset WHERE reset_code=".$db->qstr($reset_code);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check for the submitted reset code."));
        exit;
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
        $smarty->assign('information_msg', gettext("Reset code accepted."));        
        return true;
        
        
    }
    
}

##########################################
#    Delete user reset codes             #
##########################################

function delete_user_reset_code($db, $user_id) {    

    $sql = "DELETE FROM ".PRFX."user_reset WHERE user_id = ".$db->qstr($user_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete existing tokens for the submitted user."));
        exit;
    }
    
}

##########################################
#    Delete all expired reset codes      #
##########################################

function delete_expired_reset_codes($db) {    

    $sql = "DELETE FROM ".PRFX."user_reset WHERE expiry_time < ".$db->qstr(time());
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete existing tokens for the submitted user."));
        exit;
    }
    
}


#####################################
#    Update users reset count       #    
#####################################

 function update_user_reset_count($db, $user_id) {
     
    $sql = "UPDATE ".PRFX."user SET       
            reset_count     = reset_count + 1
            WHERE user_id   =". $db->qstr( $user_id  );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to add password reset authorization."));
        exit;
        
    } else{
        
        return;
        
    }
     
 }

