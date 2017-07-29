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
 * From {Joomla}administrator/components/com_admin/models/sysinfo.php - it strips dodgy formatting
 */

defined('_QWEXEC') or die;

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
    
    $sql = "SELECT * FROM ".PRFX."user_acl ORDER BY page";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to load the Page ACL permissions from the database."));
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
        
        // val is users / 01
        
        // Compensate for the page and submit variables being sent in $VAR
        if($ACLpage != 'page' && $ACLpage != 'submit') {            
                
            foreach($ACLrow as $ACLgroup => $ACLstatus) {
                
                $values .= $ACLgroup."='".$ACLstatus."',";                
            }

            // Enforce Administrators always have access to everything
            $values .= "Administrator='1' ";

            $sql = "UPDATE ".PRFX."user_acl SET ".$values."WHERE page='".$ACLpage."'";

            if(!$rs = $db->execute($sql)) {
                force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to enforce administrators to always have access to all pages."));
                exit;    
            }

            $values = '';

        }

    }    
   
    // Make these pages permissions available to all User Account Types - This prevents systems errors
    $sql = "UPDATE ".PRFX."user_acl SET `Administrator`= 1, `Manager`=1, `Supervisor`=1,`Technician`=1, `Clerical`=1, `Counter`=1, `Customer`=1, `Guest`=1, `Public`=1
            WHERE `page`= 'core:error'
            OR `page`= 'core:404'
            OR `page`= 'core:home'
            OR `page`= 'core:maintenance'                      
            ";            

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update teh Page ACL permissions."));
        exit;    
    } else {
        
        return;
        
    }

}

############################################
#   load current config details            #
############################################

function get_qwcrm_config() {
    
    // Return the config values
    return get_object_vars(new QConfig);
    
}

############################################
#   Update the QWcrm settings file         #
############################################

function update_qwcrm_config($new_config) {
    
    // Get a fresh copy of the current settings as an array        
    $current_config = get_qwcrm_config();
    
    // Perform miscellaneous options based on configuration settings/changes.
    $new_config = prepare_config_data($new_config);
    
    // Merge the new submitted config and the old one. We do this to preserve values that were not in the submitted form but are in the config.
    $merged_config = array_merge($current_config, $new_config);
    
    // Prepare the config file content
    $merged_config = build_config_file_content($merged_config);

    // Write the configuration file.
    write_config_file($merged_config);

    return true;
    
}

############################################
#   Prepare the Config file data layout    #
############################################

function build_config_file_content($config_data)
{
    $output = "<?php\n";
    $output .= "class QConfig {\n";

    foreach ($config_data as $key => $value)
    {
        $output .= "    public $$key = '$value';\n";
    }

   $output .= "}";

   return $output;   
}    



############################################
#      Write data to config file           #
############################################

function write_config_file($content)
{
    // Set the configuration file path.
    $file = 'configuration.php';

    // Check file is writable
    chmod($file, '0644');

    // Write file
    $fp = fopen($file, 'w');
    fwrite($fp, $content);
    fclose($fp);

    // Make file 444
    chmod($file, '0444');      

    return true;
}    


############################################
#   Process config data before saving      #  - // joomla\administrator\components\com_config\model\application.php  -  public function save($data)
############################################

function prepare_config_data($new_config) {
    
    // Get the database object
    $db = QFactory::getDbo();
    
    // remove unwanted varibles from the new_config
    unset($new_config['page']);
    unset($new_config['submit']);
    
    // Get a fresh copy of the current settings as an array        
    $current_config = get_qwcrm_config();
    
    // Purge the database session table if we are changing to the database handler.
    if ($current_config['session_handler'] != 'database' && $new_config['session_handler'] == 'database')
    {
        $sql = "TRUNCATE ".PRFX."session";                    
          
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to empty the database session table."));
            exit;
            
        }
    
    }
                
    // Set the shared session configuration
    if (isset($new_config['shared_session']))
    {
        $currentShared = isset($current_config['shared_session']) ? $current_config['shared_session'] : '0';

        // Has the user enabled shared sessions?
        if ($new_config['shared_session'] == 1 && $currentShared == 0)
        {
            // Generate a random shared session name
            $new_config['session_name'] = JUserHelper::genRandomPassword(16);
        }

        // Has the user disabled shared sessions?
        if ($new_config['shared_session'] == 0 && $currentShared == 1)
        {
            // Remove the session name value
            unset($new_config['session_name']);
        }
    } 
    
    // Return the processed config   
    return $new_config;
    
}