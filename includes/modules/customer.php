<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

################################
# Display Customer Details     #
################################

function get_customer_details($db, $customer_id, $item = null){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else { 
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

#####################################
#    Search                         #
#####################################

function display_customer_search($db, $name, $page_no) {
    
    global $smarty;
    
    $safe_name = strip_tags($name);
    
    // Define the number of results per page
    $max_results = 25;
    
    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);
    
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE '%$safe_name%' ORDER BY CUSTOMER_DISPLAY_NAME LIMIT $from, $max_results";
    
    //print $sql;
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $customer_search_result = array();
    }
    
    while($row = $result->FetchRow()){
         array_push($customer_search_result, $row);
    }
    
    // Figure out the total number of results in DB: 
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE ".$db->qstr("%$safe_name%") );
    
    if(!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', strip_tags($total_results['Num']));
    }
        
    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results); 
    $smarty->assign('total_pages', strip_tags($total_pages));
    
    // Assign the first page
    if($page_no > 1) {
        $prev = ($page_no - 1);     
    }     

    // Build Next Link
    if($page_no < $total_pages){
        $next = ($page_no + 1); 
    }
    
    $smarty->assign('name', strip_tags($name));
    $smarty->assign('page_no', strip_tags($page_no));
    $smarty->assign("previous", strip_tags($prev));
    $smarty->assign("next", strip_tags($next));
    
    return $customer_search_result;
}




#####################################
#    Duplicate                      #
#####################################
    
function check_customer_ex($db, $displayName) {
    $sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME=".$db->qstr($displayName);
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $row = $result->FetchRow();
    }

    if ($row['num_users'] == 1) {
        return false;    
    } else {
        return true;
    }
}

#####################################
#    insert new customer            #
#####################################

function insert_new_customer($db,$VAR) {

//Remove Extra Slashes caused by Magic Quotes
$address_string = $VAR['address'];
$address_string = stripslashes($address_string);

$customerNotes_string = $VAR['customerNotes'];
$customerNotes_string = stripslashes($customerNotes_string);

//Display Name Logic
if ($VAR["displayName"] ==""){
 $displayname = $VAR["lastName"].", ".$VAR["firstName"] ;
} else {
$displayname =$VAR["displayName"] ;
}

    $sql = "INSERT INTO ".PRFX."TABLE_CUSTOMER SET
            CUSTOMER_DISPLAY_NAME           = ". $db->qstr( $displayname         ).",
            CUSTOMER_ADDRESS        = ". $db->qstr( $address_string      ).",
            CUSTOMER_CITY            = ". $db->qstr( $VAR["city"]         ).", 
            CUSTOMER_STATE            = ". $db->qstr( $VAR["state"]        ).", 
            CUSTOMER_ZIP            = ". $db->qstr( $VAR["zip"]          ).",
            CUSTOMER_PHONE            = ". $db->qstr( $VAR["homePhone"]    ).",
            CUSTOMER_WORK_PHONE             = ". $db->qstr( $VAR["workPhone"]    ).",
            CUSTOMER_MOBILE_PHONE           = ". $db->qstr( $VAR["mobilePhone"]  ).",
            CUSTOMER_EMAIL            = ". $db->qstr( $VAR["email"]        ).", 
            CUSTOMER_TYPE            = ". $db->qstr( $VAR["customerType"] ).", 
            CREATE_DATE            = ". $db->qstr( time()               ).",
            LAST_ACTIVE            = ". $db->qstr( time()               ).",
            CUSTOMER_FIRST_NAME        = ". $db->qstr( $VAR["firstName"]    ).", 
            DISCOUNT_RATE             = ". $db->qstr( $VAR['discount_rate']     ).",
            CUSTOMER_LAST_NAME        = ". $db->qstr( $VAR['lastName']     ).",
            CREDIT_TERMS                    = ". $db->qstr( $VAR['creditterms']  ).",
                        CUSTOMER_WWW                    = ". $db->qstr( $VAR['customerWww']  ).",
                        CUSTOMER_NOTES                  = ". $db->qstr( $customerNotes_string);
            
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $customer_id = $db->Insert_ID();
        return  $customer_id;
    }
    
} 

#####################################
#    Edit Customer          #
#####################################

function edit_info($db, $customer_id){
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $row = $result->FetchRow();
        return $row;
    }
}

#####################################
#    Update Customer                #
#####################################

function update_customer($db,$VAR) {

//Remove Extra Slashes caused by Magic Quotes
$address_string = $VAR['address'];
$address_string = stripslashes($address_string);

$customerNotes_string = $VAR['customerNotes'];
$customerNotes_string = stripslashes($customerNotes_string);

    $sql = "UPDATE ".PRFX."TABLE_CUSTOMER SET
            CUSTOMER_DISPLAY_NAME           = ". $db->qstr( $VAR["displayName"]    ).",
            CUSTOMER_ADDRESS        = ". $db->qstr( $address_string        ).",
            CUSTOMER_CITY            = ". $db->qstr( $VAR["city"]        ).", 
            CUSTOMER_STATE            = ". $db->qstr( $VAR["state"]        ).", 
            CUSTOMER_ZIP            = ". $db->qstr( $VAR["zip"]        ).",
            CUSTOMER_PHONE            = ". $db->qstr( $VAR["homePhone"]    ).",
            CUSTOMER_WORK_PHONE             = ". $db->qstr( $VAR["workPhone"]    ).",
            CUSTOMER_MOBILE_PHONE           = ". $db->qstr( $VAR["mobilePhone"]    ).",
            CUSTOMER_EMAIL            = ". $db->qstr( $VAR["email"]        ).", 
            CUSTOMER_TYPE            = ". $db->qstr( $VAR["customerType"]    ).", 
            CUSTOMER_FIRST_NAME        = ". $db->qstr( $VAR["firstName"]    ).", 
            CUSTOMER_LAST_NAME        = ". $db->qstr( $VAR["lastName"]    ).",
            DISCOUNT_RATE                        = ". $db->qstr( $VAR['discount_rate']    ).",
                        CREDIT_TERMS                    = ". $db->qstr( $VAR['creditterms']     ).",
                        CUSTOMER_WWW                    = ". $db->qstr( $VAR['customerWww']     ).",
                        CUSTOMER_NOTES                  = ". $db->qstr( $customerNotes_string   )."
            WHERE CUSTOMER_ID        = ". $db->qstr( $VAR['customer_id']    );
            
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
      return true;
    }
    
} 

#####################################
#    Delete Customer                #
#####################################

function delete_customer($db,$customer_id){
    $sql = "DELETE FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;    
    } else {
        return true;
    }    
}

##################################################################
# The select array we will change this to database options later #  // what is this?
##################################################################

    $customer_type = array('Residential'=>'Residential', 'Comercial'=>'Comercial');



#####################################
#     Display the cusomters memo    #
#####################################

function display_memo($db,$customer_id) {
    
    $q = "SELECT * FROM ".PRFX."CUSTOMER_NOTES WHERE CUSTOMER_ID=".$db->qstr( $customer_id );
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
        
    return $rs->GetArray(); 
    
}

#####################################
#    Build a Google map string      #
#####################################

function build_googlemap_directions_string($db, $customer_id, $employee_id)  {
    
    $company_details    = get_company_details($db);
    $customer_details   = get_customer_details($db, $customer_id);
    $employee_details   = get_employee_details($db, $employee_id);
    
    // Make the google string country aware - if needed
    $google_server = "https://maps.google.com";
    
    // Determine the employee's start location (home or office)
    if ($employee_details['0']['EMPLOYEE_BASED'] == 1){
        
        // Works from the office
        $employee_address  = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $company_details['0']['ADDRESS']);
        $employee_city     = $company_details['0']['CITY'];
        $employee_zip      = $company_details['0']['ZIP'];
        
    } else {        
        
        // Works from home
        $employee_address  = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $employee_details['0']['EMPLOYEE_ADDRESS']);
        $employee_city     = $employee_details['0']['EMPLOYEE_CITY'];
        $employee_zip      = $employee_details['0']['EMPLOYEE_ZIP'];
        
    }
    
    // Get Customer's Address    
    $customer_address   = preg_replace('/(\r|\n|\r\n){2,}/', ', ', $customer_details['0']['CUSTOMER_ADDRESS']);
    $customer_city      = $customer_details['0']['CUSTOMER_CITY'];
    $customer_zip       = $customer_details['0']['CUSTOMER_ZIP'];
    
    // return the built google map string
    return "$google_server/maps?f=d&source=s_d&hl=en&geocode=&saddr=$employee_address,$employee_city,$employee_zip&daddr=$customer_address,$customer_city,$customer_zip";
   
}