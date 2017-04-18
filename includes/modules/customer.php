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
#   Display Customers               #  // is also used for searches
#####################################

function display_customers($db, $status = 'all', $direction = 'DESC', $use_pages = false, $page_no = 1, $records_per_page = 25, $search_type = null, $search_term = null) {

    global $smarty;    
    
    /* Filter the Records */
    
    // Perform Standard Search
    if($search_type != null) {
        
        // Restrict by status
        $whereTheseRecords = " WHERE CUSTOMER_DISPLAY_NAME LIKE '%$search_term%'";
        
    
    // Display Records with filters    
    } else {

        // Status Restriction
        if($status != 'all') {
            // Restrict by status
            $whereTheseRecords = " WHERE ".PRFX."TABLE_CUSTOMER.ACTIVE=".$db->qstr($status);        
        } else {            
            // Do not restrict by status
            $whereTheseRecords = " WHERE ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID = *";
        }
    
    }
    
    /* The SQL code */    
    
    $sql = "SELECT *              
        FROM ".PRFX."TABLE_CUSTOMER".        
        $whereTheseRecords.
        " GROUP BY ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID".            
        " ORDER BY ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID ".$direction;  
   
    /* Restrict by pages */
        
    if($use_pages == true) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_customer_error_message_function_'.__FUNCTION__.'_count'));
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
        if($page_no > 1){
            $prev = ($page_no - 1);
            $smarty->assign('previous', $prev);
        } 
        
        // Assign the next page
        if($page_no < $total_pages){
            $next = ($page_no + 1);
            $smarty->assign('next', $next);
        }  
        
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
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_customer_error_message_function_'.__FUNCTION__.'_failed'));
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






/*
#####################################
#    Search Customers               #
#####################################

function search_customers($db, $search_term, $page_no) {
    
    global $smarty;    
      
    // Define the number of results per page
    $max_results = 25;
    
    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);
    
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE '%$search_term%' ORDER BY CUSTOMER_DISPLAY_NAME LIMIT $from, $max_results";
    
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
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE ".$db->qstr("%$search_term%") );
    
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
    
    $smarty->assign('name', strip_tags($search_term));
    $smarty->assign('page_no', strip_tags($page_no));
    $smarty->assign('previous', strip_tags($prev));
    $smarty->assign('next', strip_tags($next));
    
    return $customer_search_result;
}
*/







#########################################
#    check for Duplicate display name   #
#########################################
    
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

    // If the display name is empty on submission, create it using the customer's name
    if ($VAR['displayName'] == ''){
        $displayname = $VAR['lastName'].', '.$VAR['firstName'];
    }

    $sql = "INSERT INTO ".PRFX."TABLE_CUSTOMER SET
            CUSTOMER_DISPLAY_NAME   =". $db->qstr( $displayname             ).",
            CUSTOMER_ADDRESS        =". $db->qstr( $VAR['address']          ).",
            CUSTOMER_CITY           =". $db->qstr( $VAR['city']             ).", 
            CUSTOMER_STATE          =". $db->qstr( $VAR['state']            ).", 
            CUSTOMER_ZIP            =". $db->qstr( $VAR['zip']              ).",
            CUSTOMER_PHONE          =". $db->qstr( $VAR['homePhone']        ).",
            CUSTOMER_WORK_PHONE     =". $db->qstr( $VAR['workPhone']        ).",
            CUSTOMER_MOBILE_PHONE   =". $db->qstr( $VAR['mobilePhone']      ).",
            CUSTOMER_EMAIL          =". $db->qstr( $VAR['email']            ).", 
            CUSTOMER_TYPE           =". $db->qstr( $VAR['customerType']     ).", 
            CREATE_DATE             =". $db->qstr( time()                   ).",
            LAST_ACTIVE             =". $db->qstr( time()                   ).",
            CUSTOMER_FIRST_NAME     =". $db->qstr( $VAR['firstName']        ).", 
            DISCOUNT_RATE           =". $db->qstr( $VAR['discount_rate']    ).",
            CUSTOMER_LAST_NAME      =". $db->qstr( $VAR['lastName']         ).",
            CREDIT_TERMS            =". $db->qstr( $VAR['creditterms']      ).",
            CUSTOMER_WWW            =". $db->qstr( $VAR['customerWww']      ).",
            CUSTOMER_NOTES          =". $db->qstr( $VAR['customerNotes']    );
            
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        return $db->Insert_ID();       
    }
    
} 

#####################################
#    Edit Customer                  #
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

function update_customer($db, $VAR) {
    
    $sql = "UPDATE ".PRFX."TABLE_CUSTOMER SET
            CUSTOMER_DISPLAY_NAME   = ". $db->qstr( $VAR['displayName']    ).",
            CUSTOMER_ADDRESS        = ". $db->qstr( $VAR['address']        ).",
            CUSTOMER_CITY           = ". $db->qstr( $VAR['city']            ).", 
            CUSTOMER_STATE          = ". $db->qstr( $VAR['state']           ).", 
            CUSTOMER_ZIP            = ". $db->qstr( $VAR['zip']             ).",
            CUSTOMER_PHONE          = ". $db->qstr( $VAR['homePhone']       ).",
            CUSTOMER_WORK_PHONE     = ". $db->qstr( $VAR['workPhone']       ).",
            CUSTOMER_MOBILE_PHONE   = ". $db->qstr( $VAR['mobilePhone']     ).",
            CUSTOMER_EMAIL          = ". $db->qstr( $VAR['email']           ).", 
            CUSTOMER_TYPE           = ". $db->qstr( $VAR['customerType']    ).", 
            CUSTOMER_FIRST_NAME     = ". $db->qstr( $VAR['firstName']       ).", 
            CUSTOMER_LAST_NAME      = ". $db->qstr( $VAR['lastName']        ).",
            DISCOUNT_RATE           = ". $db->qstr( $VAR['discount_rate']   ).",
            CREDIT_TERMS            = ". $db->qstr( $VAR['creditterms']     ).",
            CUSTOMER_WWW            = ". $db->qstr( $VAR['customerWww']     ).",
            CUSTOMER_NOTES          = ". $db->qstr( $VAR['customerNotes']   )."
            WHERE CUSTOMER_ID       = ". $db->qstr( $VAR['customer_id']     );
            
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
      return true;
    }
    
} 

#####################################
#    Delete Customer                # // this needs inproving check for invoices, workorders etc.. giftcerts
#####################################

function delete_customer($db, $customer_id){
    
    // Check if customer has any workorders
    $sql = "SELECT count(*) as count FROM ".PRFX."TABLE_WORK_ORDER            
            WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    if($rs->fields['count'] > 0 ) {
        //force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has work orders.');
        //exit;
        return false;
    }
    
    // Check if customer has any invoices
    $sql = "SELECT count(*) as count FROM ".PRFX."TABLE_INVOICE            
            WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    if($rs->fields['count'] > 0 ) {
        //force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has invoices.');
        //exit;
        return false;
    }
    
    
    // Check if customer has any gift certificates
    $sql = "SELECT count(*) as count FROM ".PRFX."GIFTCERT            
            WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    if($rs->fields['count'] > 0 ) {
        //force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has gift certificates.');
        //exit;
        return false;
    }
    
    // Check if customer has any customer notes
    $sql = "SELECT count(*) as count FROM ".PRFX."CUSTOMER_NOTES            
            WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    if($rs->fields['count'] > 0 ) {
        //force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has customer notes.');
        //exit;
        return false;
    }
    
    // Check if customer has any customer notes
    $sql = "SELECT count(*) as count FROM ".PRFX."CUSTOMER_MEMO           
            WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    if($rs->fields['count'] > 0 ) {
        //force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has memos.');
        //exit;
        return false;
    } 
        
    // Delete Customer
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
#     Display the customers memo    #  //this seems to be unused
#####################################

function display_memo($db,$customer_id) {
    
    $sql = "SELECT * FROM ".PRFX."CUSTOMER_NOTES WHERE CUSTOMER_ID=".$db->qstr( $customer_id );
    if(!$rs = $db->execute($sql)) {
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

#############################
#    insert customer memo   #
#############################

function insert_customer_memo($db, $customer_id, $memo) {
    
    $sql = "INSERT INTO ".PRFX."CUSTOMER_NOTES SET
            CUSTOMER_ID =". $db->qstr( $customer_id ) .",
            DATE        =". $db->qstr( time()       ) .",
            NOTE        =". $db->qstr( $memo        );

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
}

##############################
#    delete customer memo    #
##############################

function delete_customer_memo($db, $memo_id) {
    
    $sql = "DELETE FROM ".PRFX."CUSTOMER_NOTES WHERE ID=".$db->qstr( $memo_id );

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
}

