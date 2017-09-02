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

/* Get Functions */

############################################
#   get current config details             #
############################################

function get_qwcrm_config() {
    
    if(class_exists(QConfig)) {
        
        // Return the config values if defined
        return get_object_vars(new QConfig);
        
    } else {
        
        // if not config does not exist yet (i.e. install)
        return array();
        
    }
    
}

/* Update Functions */


#################################
#   Update ACL Permissions      #
#################################

function update_acl($db, $permissions) {
    
    /* Process Submitted Permissions */
    
    // Cycle through the submitted permissions and update the database
    foreach($permissions as $page_name => $page_permission) {
        
        // Compensate for non 'Page ACL' variables being submitted - skip the record
        if($page_name == 'page') { continue; }
        if($page_name == 'submit') { continue; } 
                
        // Enforce Administrators always have access to everything
        $page_permission['Administrator'] = '1';

        $sql = "UPDATE `".PRFX."user_acl` SET
                `Administrator` ='". $page_permission['Administrator']  ."',
                `Manager`       ='". $page_permission['Manager']        ."',
                `Supervisor`    ='". $page_permission['Supervisor']     ."',
                `Technician`    ='". $page_permission['Technician']     ."',
                `Clerical`      ='". $page_permission['Clerical']       ."',
                `Counter`       ='". $page_permission['Counter']        ."',
                `Customer`      ='". $page_permission['Customer']       ."',
                `Guest`         ='". $page_permission['Guest']          ."',
                `Public`        ='". $page_permission['Public']         ."'
                WHERE `page`    ='". $page_name."';";
            
        if(!$rs = $db->execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the Submitted ACL permissions."));
            exit;    
        }                 

    }

    /* Restore Mandatory Permissions */
    
    // Mandatory permission array
    $mandatory_permissions =
            
        array(

            // Permission always granted
            'core:404'          => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Customer' => '1', 'Guest' => '1', 'Public' => '1'),            
            'core:error'        => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Customer' => '1', 'Guest' => '1', 'Public' => '1'),
            'core:home'         => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Customer' => '1', 'Guest' => '1', 'Public' => '1'),
            'core:maintenance'  => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Customer' => '1', 'Guest' => '1', 'Public' => '1'),
            'user:login'        => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Customer' => '1', 'Guest' => '1', 'Public' => '1'),

            // Mixed Permissions
            //'core:dashboard'  => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Customer' => '0', 'Guest' => '0', 'Public' => '0'),
            'user:reset'  => array('Administrator' => '0', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Customer' => '0', 'Guest' => '0', 'Public' => '1'),

            // Administrator Only
            //'administrator:acl' => array('Administrator' => '1', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Customer' => '0', 'Guest' => '0', 'Public' => '0'),

            // All permissions removed
            'setup:install'     => array('Administrator' => '0', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Customer' => '0', 'Guest' => '0', 'Public' => '0'),
            'setup:choice'      => array('Administrator' => '0', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Customer' => '0', 'Guest' => '0', 'Public' => '0'),
            'setup:migrate'     => array('Administrator' => '0', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Customer' => '0', 'Guest' => '0', 'Public' => '0'),   
            'setup:upgrade'     => array('Administrator' => '0', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Customer' => '0', 'Guest' => '0', 'Public' => '0')

        ); 

    // Cycle through mandatory permissions and update the database
    foreach($mandatory_permissions as $page_name => $page_permission) {
                 
        $sql = "UPDATE `".PRFX."user_acl` SET
                `Administrator` ='". $page_permission['Administrator']  ."',
                `Manager`       ='". $page_permission['Manager']        ."',
                `Supervisor`    ='". $page_permission['Supervisor']     ."',
                `Technician`    ='". $page_permission['Technician']     ."',
                `Clerical`      ='". $page_permission['Clerical']       ."',
                `Counter`       ='". $page_permission['Counter']        ."',
                `Customer`      ='". $page_permission['Customer']       ."',
                `Guest`         ='". $page_permission['Guest']          ."',
                `Public`        ='". $page_permission['Public']         ."'
                WHERE `page`    ='". $page_name."';";

         if(!$rs = $db->execute($sql)) {
             force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the Mandatory ACL permissions."));
             exit;    
        }               
        
    }
    
    // Log activity        
    write_record_to_activity_log(gettext("ACL permissions updated."));      

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
    
    // Log activity        
    write_record_to_activity_log(gettext("QWcrm config settings updated."));   

    return true;
    
}

/* Other Functions */

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

function check_for_qwcrm_update() {
    
    global $smarty;
    
    // Get curent version and check against quantumwarp.com
    $update_page    = 'https://quantumwarp.com/ext/updates/qwcrm/qwcrm.xml';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        // FALSE to stop cURL from verifying the peer's certificate (need unless i bundle certs with my install)
    curl_setopt($ch, CURLOPT_URL, $update_page);            // The URL to fetch. This can also be set when initializing a session with curl_init(). 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly. 
    
    $curl_response = curl_exec($ch);     
    $curl_error = curl_errno($ch);  
    curl_close($ch);

    // If there is a connection error
    if($curl_error) {         
        $smarty->assign('warning_msg', gettext("Connection Error - cURL Error Number").': '.$curl_error);
        return;        
    }
    
    // If no response return with error message
    if($curl_response == '' || $curl_error) {         
        $smarty->assign('warning_msg', gettext("No response from the QWcrm update server."));
        return;        
    }

    // Parse the grabbed XML into an array
    $update_response = parse_xml_sting_into_array($curl_response);
    
    // Build the update message
    if (version_compare(QWCRM_VERSION, $update_response['version'], '<')) {
        
        // An Update is available        
        $smarty->assign('version_compare', '1');
        
    } else {
        
        // No Updates available      
        $smarty->assign('version_compare', '0');
        
    }

    // Assign Variables    
    $smarty->assign('update_response', $update_response);
    
    // Log activity        
    write_record_to_activity_log(gettext("QWcrm checked for updates."));

    return;

}

#################################
#   Load ACL Permissions        #
#################################

function load_acl($db) {
    
    $sql = "SELECT * FROM ".PRFX."user_acl ORDER BY page";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to load the Page ACL permissions from the database."));
        exit;
    }
    
    return $rs->GetArray(); 

}

############################################
#   Prepare the Config file data layout    #
############################################

function build_config_file_content($config_data)
{
    $output = "<?php\r\n";
    $output .= "class QConfig {\r\n";

    foreach ($config_data as $key => $value)
    {
        $output .= "    public $$key = '$value';\r\n";
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
    
    // if the file does not exist - Create and Open
    if(!is_file($file)) {
        
        // Create and Open file
        $fp = fopen($file, 'x');
    
    // if file exists - Open    
    } else {
        
        // Make file is writable - is this needed?
        chmod($file, '0644');
    
        // Open file
        $fp = fopen($file, 'w');
        
    }
    
    // Write file    
    fwrite($fp, $content);
    
    // Close file
    fclose($fp);

    // Make file 444
    chmod($file, '0444');      

    return true;
    
}    


############################################
#   Process config data before saving      #  // joomla\administrator\components\com_config\model\application.php  -  public function save($data)
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

############################################
#      Send Test Mail                      #
############################################

function send_test_mail($db) {
    
    $user_details = get_user_details($db, QFactory::getUser()->login_user_id);
    
    send_email($user_details['email'], gettext("Test mail from QWcrm"), 'This is a test mail sent using'.' '.QFactory::getConfig()->get('email_mailer').'. '.'Your email settings are correct!', $user_details['display_name']);
    
    // Log activity        
    write_record_to_activity_log(gettext("Test email initiated."));
    
}

############################################
#      Clear Smarty Cache                  #
############################################

function clear_smarty_cache() {
    
    global $smarty;
    
    // Clear any onscreen notifications - this allows for mutiple errors to be displayed
    clear_onscreen_notifications();
    
    // clear the entire cache
    $smarty->clearAllCache();

    // clears all files over one hour old
    //$smarty->clearAllCache(3600);
    
    // Output the system message to the browser   
    output_notifications_onscreen(gettext("The Smarty cache has been emptied successfully."), '');
    
    // Log activity        
    write_record_to_activity_log(gettext("Smarty Cache Cleared."));
    
}

############################################
#      Clear Smarty Compile                #
############################################

function clear_smarty_compile() {
    
    global $smarty;
    
    // Clear any onscreen notifications - this allows for mutiple errors to be displayed
    clear_onscreen_notifications();
    
    // clear a specific template resource
    //$smarty->clearCompiledTemplate('index.tpl');

    // clear entire compile directory
    $smarty->clearCompiledTemplate();
    
    // Output the system message to the browser   
    output_notifications_onscreen(gettext("The Smarty compile directory has been emptied successfully."), '');
    
    // Log activity        
    write_record_to_activity_log(gettext("Smarty Compile Cache Cleared."));    
    
}

#################################
#   Reset ACL Permissions       #
#################################

function reset_acl_permissions($db) {
 
    // Remove current permissions
    $sql = "TRUNCATE ".PRFX."user_acl";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed reset default permissions."));
        exit;
        
    } else {
    
        // Insert default permissions 
        $sql = "INSERT INTO `".PRFX."user_acl` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Customer`, `Guest`, `Public`) VALUES
                ('administrator:acl', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('administrator:config', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('administrator:phpinfo', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('administrator:update', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('company:business_hours', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('company:edit', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('core:404', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:dashboard', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('core:error', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:home', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:maintenance', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('customer:delete', 1, 1, 1, 0, 1, 0, 0, 0, 0),
                ('customer:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('customer:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('customer:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('customer:note_delete', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('customer:note_edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('customer:note_new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('customer:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('expense:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('giftcert:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('giftcert:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('giftcert:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('giftcert:new', 1, 1, 0, 0, 1, 1, 0, 0, 0),
                ('giftcert:print', 1, 1, 0, 0, 1, 1, 0, 0, 0),
                ('giftcert:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:about', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:attribution', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:license', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:delete_labour', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:delete_parts', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:paid', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:prefill_items', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:search', 1, 1, 1, 0, 1, 1, 0, 0, 0),
                ('invoice:unpaid', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('payment:options', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('report:basic_stats', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('report:financial', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('schedule:day', 1, 1, 1, 1, 0, 0, 0, 0, 0),
                ('schedule:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('schedule:details', 1, 1, 1, 1, 0, 0, 0, 0, 0),
                ('schedule:edit', 1, 1, 1, 1, 0, 0, 0, 0, 0),
                ('schedule:icalendar', 1, 1, 1, 1, 0, 0, 0, 0, 0),
                ('schedule:new', 1, 1, 1, 1, 0, 0, 0, 0, 0),
                ('schedule:search', 1, 1, 1, 1, 0, 0, 0, 0, 0),
                ('setup:choice', 0, 0, 0, 0, 0, 0, 0, 0, 0),
                ('setup:install', 0, 0, 0, 0, 0, 0, 0, 0, 0),
                ('setup:migrate', 0, 0, 0, 0, 0, 0, 0, 0, 0),
                ('setup:upgrade', 0, 0, 0, 0, 0, 0, 0, 0, 0),
                ('supplier:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:details', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('supplier:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:search', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('user:delete', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('user:details', 1, 1, 1, 0, 1, 0, 0, 0, 0),
                ('user:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('user:login', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('user:new', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('user:reset', 0, 0, 0, 0, 0, 0, 0, 0, 1),
                ('user:search', 1, 1, 1, 0, 1, 0, 0, 0, 0),
                ('workorder:autosuggest_scope', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:closed', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:details', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:details_edit_comments', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:details_edit_description', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:details_edit_resolution', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:note_delete', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:note_edit', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:note_new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:open', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:overview', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:print', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:search', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:status', 1, 1, 1, 0, 0, 0, 0, 0, 0);";

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed reset default permissions."));
            exit;

        }
        
    }
    
    // Log activity        
    write_record_to_activity_log(gettext("ACL permissions reset to default settings."));    
    
    return;
    
}