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

/* Insert */

############################################
#   Insert a single QWcrm setting file     #
############################################

function insert_qwcrm_config_setting($key, $value) {
    
    // Add the setting into the Registry
    QFactory::getConfig()->set($key, $value);
    
    // Get a fresh copy of the current settings as an array        
    $qwcrm_config = get_qwcrm_config_settings();  
    
    // Add the key/value pair into the array
    //$qwcrm_config[$key] = $value;
    
    // Prepare the config file content
    $qwcrm_config = build_config_file_content($qwcrm_config);
    
    // Write the configuration file.
    write_config_file($qwcrm_config);
    
    // Log activity
    write_record_to_activity_log(_gettext("The QWcrm config setting").' `'.$key.'` '._gettext("was inserted."));    

    return true;
    
}

/* Get Functions */

###################################################################
#  Load Config settings from file or use the registry if present  #
###################################################################

function get_qwcrm_config_settings() {

    // Verify the configuration.php file exists
    if(is_file('configuration.php')) {

        // if QConfig class does not exist, get the config settings directly from configuration.php and build a new Config Registry (This is needed for setup)
        if(!class_exists('QConfig')) {
            require_once('configuration.php');
            QFactory::$config = null;
            QFactory::getConfig();
            //return get_object_vars(new QConfig);
        } 
        
        // Use the config settings in the live Registry 
        if($registry_object = QFactory::getConfig()->toObject()) {
            return get_object_vars($registry_object);
        }
        
    }
        
    // If config does not exist (i.e. install)
    return array();
    
}

#################################
#   Get ACL Permissions         #
#################################

function get_acl_permissions() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."user_acl_page ORDER BY page";
    
    if(!$rs = $db->execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to load the Page ACL permissions from the database."));
    }
    
    return $rs->GetArray(); 

}

/* Update Functions */

############################################
#   Update a single QWcrm setting file     #
############################################

function update_qwcrm_config_setting($key, $value) {
    
    // Update a setting into the Registry
    QFactory::getConfig()->set($key, $value);
    
    // Get a fresh copy of the current settings as an array        
    $qwcrm_config = get_qwcrm_config_settings();
    
    // Add the key/value pair into the object
    //$qwcrm_config[$key] = $value;
    
    // Prepare the config file content
    $qwcrm_config = build_config_file_content($qwcrm_config);
    
    // Write the configuration file.
    write_config_file($qwcrm_config);
    
    // Log activity
    write_record_to_activity_log(_gettext("The QWcrm config setting").' `'.$key.'` '._gettext("was updated to").' `'.$value.'`.');    

    return true;
    
}

#################################
#   Update ACL Permissions      #
#################################

function update_acl($permissions) {
    
    $db = QFactory::getDbo();
    
    /* Process Submitted Permissions */
    
    // Cycle through the submitted permissions and update the database
    foreach($permissions as $page_name => $page_permission) {
        
        // Enforce Administrators always have access to everything
        $page_permission['Administrator'] = '1';

        $sql = "UPDATE `".PRFX."user_acl_page` SET
                `Administrator` =". $db->qstr( $page_permission['Administrator']    ).",
                `Manager`       =". $db->qstr( $page_permission['Manager']          ).",
                `Supervisor`    =". $db->qstr( $page_permission['Supervisor']       ).",
                `Technician`    =". $db->qstr( $page_permission['Technician']       ).",
                `Clerical`      =". $db->qstr( $page_permission['Clerical']         ).",
                `Counter`       =". $db->qstr( $page_permission['Counter']          ).",
                `Client`        =". $db->qstr( $page_permission['Client']           ).",
                `Guest`         =". $db->qstr( $page_permission['Guest']            ).",
                `Public`        =". $db->qstr( $page_permission['Public']           )."
                WHERE `page`    =". $db->qstr( $page_name                           ).";";
          
        if(!$rs = $db->execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Submitted ACL permissions."));
        }                 

    }

    /* Restore Mandatory Permissions */
    
    // Mandatory permission array
    $mandatory_permissions =
            
        array(

            // Permission always granted for all
            'core:403'          => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '1', 'Guest' => '1', 'Public' => '1'),
            'core:404'          => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '1', 'Guest' => '1', 'Public' => '1'),
            'core:error'        => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '1', 'Guest' => '1', 'Public' => '1'),
            'core:home'         => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '1', 'Guest' => '1', 'Public' => '1'),
            'core:maintenance'  => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '1', 'Guest' => '1', 'Public' => '1'),
            'user:login'        => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '1', 'Guest' => '1', 'Public' => '1'),

            // Mixed Permissions
            //'core:dashboard'  => array('Administrator' => '1', 'Manager' => '1', 'Supervisor' => '1', 'Technician' =>'1', 'Clerical' => '1', 'Counter' => '1', 'Client' => '0', 'Guest' => '0', 'Public' => '0'),
            
            // Administrator Only
            'setup:install'     => array('Administrator' => '1', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Client' => '0', 'Guest' => '0', 'Public' => '0'),
            'setup:choice'      => array('Administrator' => '1', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Client' => '0', 'Guest' => '0', 'Public' => '0'),
            'setup:migrate'     => array('Administrator' => '1', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Client' => '0', 'Guest' => '0', 'Public' => '0'),
            'setup:upgrade'     => array('Administrator' => '1', 'Manager' => '0', 'Supervisor' => '0', 'Technician' =>'0', 'Clerical' => '0', 'Counter' => '0', 'Client' => '0', 'Guest' => '0', 'Public' => '0')

        ); 

    // Cycle through mandatory permissions and update the database
    foreach($mandatory_permissions as $page_name => $page_permission) {
                 
        $sql = "UPDATE `".PRFX."user_acl_page` SET
                `Administrator` =". $db->qstr( $page_permission['Administrator']  ).",
                `Manager`       =". $db->qstr( $page_permission['Manager']        ).",
                `Supervisor`    =". $db->qstr( $page_permission['Supervisor']     ).",
                `Technician`    =". $db->qstr( $page_permission['Technician']     ).",
                `Clerical`      =". $db->qstr( $page_permission['Clerical']       ).",
                `Counter`       =". $db->qstr( $page_permission['Counter']        ).",
                `Client`        =". $db->qstr( $page_permission['Client']         ).",
                `Guest`         =". $db->qstr( $page_permission['Guest']          ).",
                `Public`        =". $db->qstr( $page_permission['Public']         )."
                WHERE `page`    =". $db->qstr( $page_name                         ).";";

         if(!$rs = $db->execute($sql)) {
             force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the Mandatory ACL permissions."));
        }               
        
    }
    
    // Log activity        
    write_record_to_activity_log(_gettext("ACL permissions updated."));      

}

############################################
#   Update the QWcrm settings file         #
############################################

function update_qwcrm_config_settings_file($new_config) {
    
    // Get a fresh copy of the current settings as an array        
    $current_config = get_qwcrm_config_settings();
    
    // Perform miscellaneous options based on configuration settings/changes.
    $new_config = process_config_data($new_config);
    
    // Merge the new submitted config and the old one. We do this to preserve values that were not in the submitted form but are in the config.
    $merged_config = array_merge($current_config, $new_config);
    
    // Walk through the merged_config array and escape all apostophes (anonymous function)
    array_walk($merged_config, function(&$value) {
        $value = str_replace("'", "\\'", $value);
    });
    
    // Prepare the config file content
    $merged_config = build_config_file_content($merged_config);

    // Write the configuration file.
    write_config_file($merged_config);
    
    // Log activity        
    write_record_to_activity_log(_gettext("QWcrm config settings updated."));   

    return true;
    
}

/* Delete Functions */

############################################
#   Update s QWcrm setting                 #
############################################

function delete_qwcrm_config_setting($key) {
    
    // Remove the setting from the Registry
    QFactory::getConfig()->remove($key);
    
    // Get a fresh copy of the current settings as an array        
    $qwcrm_config = get_qwcrm_config_settings();
    
    // Remove the key from the array
    //unset($qwcrm_config[$key]);
    
    // Prepare the config file content
    $qwcrm_config = build_config_file_content($qwcrm_config);
    
    // Write the configuration file.
    write_config_file($qwcrm_config);
    
    // Log activity
    write_record_to_activity_log(_gettext("The QWcrm config setting").' `'.$key.'` '._gettext("was deleted."));    

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
    
    $smarty = QFactory::getSmarty();
    
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
        $smarty->assign('warning_msg', _gettext("Connection Error - cURL Error Number").': '.$curl_error);
        return;        
    }
    
    // If no response return with error message
    if(!$curl_response || $curl_error) {         
        $smarty->assign('warning_msg', _gettext("No response from the QWcrm update server."));
        $smarty->assign('update_response', 'no_response');
        return;        
    }

    // Parse the grabbed XML into an array
    $update_response = parse_xml_sting_into_array($curl_response);
    
    // Verify there is a real response and flag error if not
    if(!$update_response['name']) {
        $smarty->assign('warning_msg', _gettext("No response from the QWcrm update server."));
        $smarty->assign('update_response', 'no_response');
        return;       
    }
    
    
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
    write_record_to_activity_log(_gettext("QWcrm checked for updates."));

    return;

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
        chmod($file, 0644);
    
        // Open file
        $fp = fopen($file, 'w');
        
    }
    
    // Write file    
    fwrite($fp, $content);
    
    // Close file
    fclose($fp);

    // Make file 444
    chmod($file, 0444);      

    return true;
    
}    


############################################
#   Process config data before saving      #  // joomla\administrator\components\com_config\model\application.php  -  public function save($data)
############################################

function process_config_data($new_config) {    
    
    // Get a fresh copy of the current settings as an array        
    $current_config = get_qwcrm_config_settings();
    
    // Purge the database session table if we are changing to the database handler.
    if(!defined('QWCRM_SETUP')) {
        
        // Get the database object
        $db = QFactory::getDbo();
        
        // Empty the session table if changing to database session handling from non-database session handling
        if ($current_config['session_handler'] != 'database' && $new_config['session_handler'] == 'database')
        {
            $sql = "TRUNCATE ".PRFX."session";                    

            if(!$rs = $db->Execute($sql)) {
                force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to empty the database session table."));

            }

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

function send_test_mail() {
    
    $user_details = get_user_details(QFactory::getUser()->login_user_id);
    
    send_email($user_details['email'], _gettext("Test mail from QWcrm"), 'This is a test mail sent using'.' '.QFactory::getConfig()->get('email_mailer').'. '.'Your email settings are correct!', $user_details['display_name']);
    
    // Log activity        
    write_record_to_activity_log(_gettext("Test email initiated."));
    
}

#################################
#   Reset ACL Permissions       #
#################################

function reset_acl_permissions() {
    
    $db = QFactory::getDbo();
 
    // Remove current permissions
    $sql = "TRUNCATE ".PRFX."user_acl_page";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed reset default permissions."));
        
    } else {
    
        // Insert default permissions 
        $sql = "INSERT INTO `".PRFX."user_acl_page` (`page`, `Administrator`, `Manager`, `Supervisor`, `Technician`, `Clerical`, `Counter`, `Client`, `Guest`, `Public`) VALUES
                ('administrator:acl', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('administrator:config', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('administrator:phpinfo', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('administrator:update', 1, 0, 0, 0, 0, 0, 0, 0, 0),
                ('client:delete', 1, 1, 1, 0, 1, 0, 0, 0, 0),
                ('client:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('client:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('client:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('client:note_delete', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('client:note_edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('client:note_new', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('client:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),                
                ('company:business_hours', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('company:edit', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('core:403', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:404', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:dashboard', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('core:error', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:home', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('core:maintenance', 1, 1, 1, 1, 1, 1, 1, 1, 1),
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
                ('giftcert:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('help:about', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:attribution', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:license', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),                
                ('invoice:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:delete_labour', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:delete_parts', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),                
                ('invoice:overview', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:prefill_items', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:search', 1, 1, 1, 0, 1, 1, 0, 0, 0),
                ('invoice:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),                
                ('payment:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),                
                ('payment:options', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('payment:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),                
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
                ('setup:choice', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('setup:install', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('setup:migrate', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('setup:upgrade', 1, 1, 1, 1, 1, 1, 1, 1, 1),
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
                ('workorder:delete', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:details', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:details_edit_comment', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:details_edit_description', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:details_edit_resolution', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:new', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:note_delete', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:note_edit', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:note_new', 1, 1, 1, 1, 0, 1, 0, 0, 0),                
                ('workorder:overview', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:print', 1, 1, 1, 1, 0, 1, 0, 0, 0),
                ('workorder:search', 1, 1, 1, 0, 0, 0, 0, 0, 0),
                ('workorder:status', 1, 1, 1, 0, 0, 0, 0, 0, 0);";

        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed reset default permissions."));

        }
        
    }
    
    // Log activity        
    write_record_to_activity_log(_gettext("ACL permissions reset to default settings."));    
    
    return;
    
}