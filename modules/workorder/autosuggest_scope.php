<?php

// Prevent direct Access
defined('_QWEXEC') or die;

// Prevent direct access to this page except from workorder:new or workorder:details_edit_description
if(check_page_accessed_via_qwcrm('workorder:new') || check_page_accessed_via_qwcrm('workorder:details_edit_description')) {

    // valid page has referred the autosuggest
    
} else {
    
    // referer is not in the allowed list
    die();
    
}

// Logged in and not Public or Guest
if (QFactory::getUser()->login_token && QFactory::getUser()->usergroup_id <= 6) {
    
    // Is there a posted query string and is the string length greater than 0?
    if(isset($VAR['posted_scope_string']) && strlen($VAR['posted_scope_string']) > 0) {

        $posted_scope_string    = $VAR['posted_scope_string'];
        $sql                    = "SELECT scope FROM ".PRFX."workorder WHERE scope LIKE '$posted_scope_string%' LIMIT 10";
        $rs                     = $db->Execute($sql);
        $record_count           = $rs->RecordCount();        
        
        if($record_count) {

            $autosuggest_items = $rs->GetArray(); 

            // loop over the rows, outputting them to the page object in the required format
            foreach($autosuggest_items as $key => $value) {
                $BuildPage .= '<li onClick="fill(\''.$value['scope'].'\');">'.$value['scope'].'</li>';
            } 
            
        } else {
            
            // No records found - do nothing            
            
        }
        
    } else {
        
        // the string length was zero or not submitted - do nothing
        
    }

}