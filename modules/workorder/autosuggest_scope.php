<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// Prevent direct Access
defined('_QWEXEC') or die;

// Prevent direct access to this page - Pexcept from workorder:new or workorder:details_edit_description
if(!check_page_accessed_via_qwcrm('workorder:new') || !check_page_accessed_via_qwcrm('workorder:details_edit_description')) {
    die(gettext("No Direct Access Allowed"));    
}
    
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

// Skip page logging
$skip_logging = true;