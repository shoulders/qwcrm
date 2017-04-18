<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/**
 * Method to get the PHP info
 *
 * @return  string  PHP info
 *
 * @since   1.6
 * 
 * from joomla sysinfo.php - it strips dodgy formatting
 */

/**
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
function getPHPInfo()
{
    ob_start();
    date_default_timezone_set('UTC');
    phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
    $phpInfo = ob_get_contents();
    ob_end_clean();
    preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpInfo, $output);
    $output = preg_replace('#<table[^>]*>#', '<table class="table table-striped adminlist">', $output[1][0]);
    $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
    $output = preg_replace('#<hr />#', '', $output);
    $output = str_replace('<div class="center">', '', $output);
    $output = preg_replace('#<tr class="h">(.*)<\/tr>#', '<thead><tr class="h">$1</tr></thead><tbody>', $output);
    $output = str_replace('</table>', '</tbody></table>', $output);
    $output = str_replace('</div>', '', $output);    

    return $output;
}

#################################
#   Check for QWcrm update      #
#################################

function checkForQWcrmUpdate() {
    
    global $smarty;
    
    // Get curent version and check against quantumwarp.com
    $updatePage    = 'https://quantumwarp.com/ext/updates/app=qwcrm&ver='.QWCRM_VERSION;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, MYITCRM);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $updatePage);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec ($ch); # This returns HTML
    curl_close ($ch);

    // If no response return with error message
    if( $content == '') {
        $smarty->assign('status','0');
        $smarty->assign('warning_msg','No response from server');
        return false;        
    }

    // Parse the grabbed XML
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $content, $values, $tags);
    xml_parser_free($parser);

    foreach($values as $xml){
            if($xml['tag'] == "UPDATE_STATUS" && $xml['value'] != ""){
                $status = $xml['value'];
            }

            if($xml['tag'] == "UPDATE_FILE" && $xml['value'] != ""){
                $file= $xml['value'];
            }

            if($xml['tag'] == "UPDATE_DATE" && $xml['value'] != ""){
                $date= $xml['value'];
            }

            if($xml['tag'] == "UPDATE_MESSAGE" && $xml['value'] != ""){
                $message= $xml['value'];
            }

            if($xml['tag'] == "LATEST_VERSION" && $xml['value'] != ""){
                $cur_version = $xml['value'];
            }

    }
    
    // Build the update message
    if (version_compare(QWCRM_VERSION, LATEST_VERSION, '<')){
        $update_message = 'There is a newer version available';
    } else {
        $update_message = 'You have the latest version';
    }

    // Assign Variables
    $smarty->assign('status',$status);
    $smarty->assign('file',$file);
    $smarty->assign('date',$date);
    $smarty->assign('latest_version',$latest_version);
    $smarty->assign('update_message',$update_message);

    return;

}

#################################
#   Load ACL Permissions        #
#################################

function loadACL($db) {
    
    $sql = "SELECT * FROM ".PRFX."ACL ORDER BY page";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_administrtor_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
    return $rs->GetArray(); 

}

#################################
#   Update ACL Permissions      #
#################################

function updateACL($db, $permissions) {
    
    // Cycle through $_POST and parse the submitted data
    foreach($permissions as $ACLpage => $ACLrow){
        
        //print_r($val);// val is users / 01
        
        // if not the submit button
        if($ACLpage != 'submit') {            
                
            foreach($ACLrow as $ACLgroup => $ACLstatus) {
                
                $values .= $ACLgroup."='".$ACLstatus."',";                
            }

            // Enforce Administrators always have access to everything
            $values .= "Administrator='1' ";

            $sql = "UPDATE ".PRFX."ACL SET ".$values."WHERE page='".$ACLpage."'";

            if(!$rs = $db->execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_administrtor_error_message_function_'.__FUNCTION__.'_failed'));
                exit;    
            }

            $values = '';

        }

    }    
   
    // Make these pages permissions available to all User Account Types - This prevents systems errors
    $sql = "UPDATE ".PRFX."ACL SET `Administrator`= 1, `Manager`=1, `Supervisor`=1,`Technician`=1, `Clerical`=1, `Counter`=1, `Customer`=1, `Guest`=1, `Public`=1
            WHERE `page`= 'core:error'
            OR `page`= 'core:404'
            OR `page`= 'core:login'
            OR `page`= 'core:maintenance'
            OR `page`= 'user:password_reset'            
            ";            

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_administrtor_error_message_function_'.__FUNCTION__.'_failed'));
        exit;    
    }   
        
    return;

}