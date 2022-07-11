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

defined('_QWEXEC') or die;

/* Insert */

class Administrator extends Components {

    ############################################
    #   Insert a single QWcrm setting file     #
    ############################################

    public function insertQwcrmConfigSetting($key, $value) {

        // Add the setting into the Registry
        $this->app->config->set($key, $value);

        // Get a fresh copy of the current settings as an array        
        $qwcrm_config = $this->getQwcrmConfigAsArray();  

        // Add the key/value pair into the array
        //$qwcrm_config[$key] = $value;

        // Prepare the config file content
        $qwcrm_config = $this->buildConfigFileContent($qwcrm_config);

        // Write the configuration file.
        $this->writeConfigFile($qwcrm_config);

        // Log activity
        $this->app->system->general->writeRecordToActivityLog(_gettext("The QWcrm config setting").' `'.$key.'` '._gettext("was inserted."));    

        return true;

    }

    /* Get Functions */

    ###################################################################
    #  Load Config settings from file or use the registry if present  #
    ###################################################################

    public function getQwcrmConfigAsArray() {

        // Use the config settings in the live Registry 
        return get_object_vars($this->app->config->toObject());
            
        // Return Settings Array Directly from the QConfig
        //if(!class_exists('QConfig')) { return get_object_vars(new \QConfig); }
                
    }  


 

    #################################
    #   Get ACL Permissions         #
    #################################

    public function getAclPermissions() {

        $sql = "SELECT * FROM ".PRFX."user_acl_page ORDER BY page";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray(); 

    }

    /* Update Functions */

    ############################################
    #   Update a single QWcrm setting file     #
    ############################################

    public function updateQwcrmConfigSetting($key, $value) {

        // Update a setting into the Registry
        $this->app->config->set($key, $value);

        // Get a fresh copy of the current settings as an array        
        $qwcrm_config = $this->getQwcrmConfigAsArray();

        // Add the key/value pair into the object
        //$qwcrm_config[$key] = $value;

        // Prepare the config file content
        $qwcrm_config = $this->buildConfigFileContent($qwcrm_config);

        // Write the configuration file.
        $this->writeConfigFile($qwcrm_config);

        // Log activity
        $this->app->system->general->writeRecordToActivityLog(_gettext("The QWcrm config setting").' `'.$key.'` '._gettext("was updated to").' `'.$value.'`.');    

        return true;

    }

    #################################
    #   Update ACL Permissions      #
    #################################

    public function updateAcl($permissions) {

        /* Process Submitted Permissions */

        // Cycle through the submitted permissions and update the database
        foreach($permissions as $page_name => $page_permission) {

            // Prevent undefined variable errors
            $page_permission['Administrator'] = $page_permission['Administrator'] ?? 0;
            $page_permission['Manager'] = $page_permission['Manager'] ?? 0;
            $page_permission['Supervisor'] = $page_permission['Supervisor'] ?? 0;
            $page_permission['Technician'] = $page_permission['Technician'] ?? 0;
            $page_permission['Clerical'] = $page_permission['Clerical'] ?? 0;
            $page_permission['Counter'] = $page_permission['Counter'] ?? 0;
            $page_permission['Client'] = $page_permission['Client'] ?? 0;
            $page_permission['Guest'] = $page_permission['Guest'] ?? 0;
            $page_permission['Public'] = $page_permission['Public'] ?? 0;        

            // Enforce Administrators always have access to everything
            $page_permission['Administrator'] = '1';

            $sql = "UPDATE `".PRFX."user_acl_page` SET
                    `Administrator` =". $this->app->db->qStr( $page_permission['Administrator']    ).",
                    `Manager`       =". $this->app->db->qStr( $page_permission['Manager']          ).",
                    `Supervisor`    =". $this->app->db->qStr( $page_permission['Supervisor']       ).",
                    `Technician`    =". $this->app->db->qStr( $page_permission['Technician']       ).",
                    `Clerical`      =". $this->app->db->qStr( $page_permission['Clerical']         ).",
                    `Counter`       =". $this->app->db->qStr( $page_permission['Counter']          ).",
                    `Client`        =". $this->app->db->qStr( $page_permission['Client']           ).",
                    `Guest`         =". $this->app->db->qStr( $page_permission['Guest']            ).",
                    `Public`        =". $this->app->db->qStr( $page_permission['Public']           )."
                    WHERE `page`    =". $this->app->db->qStr( $page_name                           ).";";

            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}               

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
                    `Administrator` =". $this->app->db->qStr( $page_permission['Administrator']  ).",
                    `Manager`       =". $this->app->db->qStr( $page_permission['Manager']        ).",
                    `Supervisor`    =". $this->app->db->qStr( $page_permission['Supervisor']     ).",
                    `Technician`    =". $this->app->db->qStr( $page_permission['Technician']     ).",
                    `Clerical`      =". $this->app->db->qStr( $page_permission['Clerical']       ).",
                    `Counter`       =". $this->app->db->qStr( $page_permission['Counter']        ).",
                    `Client`        =". $this->app->db->qStr( $page_permission['Client']         ).",
                    `Guest`         =". $this->app->db->qStr( $page_permission['Guest']          ).",
                    `Public`        =". $this->app->db->qStr( $page_permission['Public']         )."
                    WHERE `page`    =". $this->app->db->qStr( $page_name                         ).";";

            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}              

        }

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("ACL permissions updated."));      

    }

    ############################################
    #   Update the QWcrm settings file         #
    ############################################

    public function updateQwcrmConfigSettingsFile($new_config) {

        // Perform miscellaneous operations based on configuration settings/changes. (not currently need for setup
        $new_config = $this->processSubmittedConfigData($new_config);

        // Get a fresh copy of the current settings as an array        
        $current_config = $this->getQwcrmConfigAsArray();

        // Merge the new_config and the current_config. We do this to preserve values that were not in the submitted form but are in the config.    
        $merged_config = array_merge($current_config, $new_config);

        // Walk through the merged_config array and escape all apostophes (anonymous public function)
        array_walk($merged_config, function(&$value) {
            $value = str_replace("'", "\\'", $value);
        });

        // Prepare the config file content
        $merged_config = $this->buildConfigFileContent($merged_config);

        // Write the configuration file.
        $this->writeConfigFile($merged_config);

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("QWcrm config settings updated."));   

        return true;

    }

    /* Delete Functions */

    ############################################
    #   Delete s QWcrm setting                 #
    ############################################

    public function deleteQwcrmConfigSetting($key) {

        // Remove the setting from the Registry
        $this->app->config->remove($key);

        // Get a fresh copy of the current settings as an array        
        $qwcrm_config = $this->getQwcrmConfigAsArray();

        // Remove the key from the array
        //unset($qwcrm_config[$key]);

        // Prepare the config file content
        $qwcrm_config = $this->buildConfigFileContent($qwcrm_config);

        // Write the configuration file.
        $this->writeConfigFile($qwcrm_config);

        // Log activity
        $this->app->system->general->writeRecordToActivityLog(_gettext("The QWcrm config setting").' `'.$key.'` '._gettext("was deleted."));    

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
    /**
     * Method to get the PHP info
     *
     * @return  string  PHP info
     *
     * @since   1.6
     * 
     * From {Joomla}administrator/components/com_admin/models/sysinfo.php - it strips dodgy formatting
     */
    public function getPhpInfo()
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
    #   Check for QWcrm update      #  // Get QWcrm curent version and check against latest version info on quantumwarp.com
    #################################

    public function checkQwcrmUpdateAvailability() {
        
        $update_page    = 'https://quantumwarp.com/ext/updates/qwcrm/qwcrm.xml';
        $useragent      = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:79.0) Gecko/20100101 Firefox/79.0';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        // FALSE to stop cURL from verifying the peer's certificate (need unless i bundle certs with my install)
        curl_setopt($ch, CURLOPT_URL, $update_page);            // The URL to fetch. This can also be set when initializing a session with curl_init(). 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly. 
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);        // Make the http request using this user agent string

        $curl_response = curl_exec($ch);     
        $curl_error = curl_errno($ch);  
        curl_close($ch);

        // If there is a connection error
        if($curl_error) {         
            $this->app->components->vaiables->systemMessagesWrite('danger', _gettext("Connection Error - cURL Error Number").': '.$curl_error);
            return;        
        }

        // If no response return with error message
        if(!$curl_response) {         
            $this->app->components->vaiables->systemMessagesWrite('danger', _gettext("No response from the QWcrm update server."));
            $this->app->smarty->assign('update_response', 'no_response');
            return;        
        }

        // Parse the grabbed XML into an array
        $update_response = $this->app->system->general->parseXmlStingIntoArray($curl_response);

        // Verify there is a real response and flag error if not
        if(!$update_response['name']) {
            $this->app->components->vaiables->systemMessagesWrite('danger', _gettext("No response from the QWcrm update server."));
            $this->app->smarty->assign('update_response', 'no_response');
            return;       
        }


        // Build the update message
        if (version_compare(QWCRM_VERSION, $update_response['version'], '<')) {

            // An Update is available        
            $this->app->smarty->assign('version_compare', '1');

        } else {

            // No Updates available      
            $this->app->smarty->assign('version_compare', '0');

        }

        // Assign Variables    
        $this->app->smarty->assign('update_response', $update_response);

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("QWcrm checked for updates."));

        return;

    }
    

    ##############################################
    #   Reload configuration registry from file  #
    ##############################################
    
    function refreshQwcrmConfig() {        
            
        // Wipe the live registry - i dont think this works (because of context ?)
        //$this->app->system->config = null;        

        // Wipe the live registry - Must call the static directly because of context
        \Factory::$config = null;

        // Re-populate the Config Registry
        $this->app->config = \Factory::getConfig();
        
        // Log activity
        $this->app->system->general->writeRecordToActivityLog(_gettext("The QWcrm live config registry has been refreshed from the config file.")); 
        
        return;
                
    }        

    ############################################
    #   Prepare the Config file data layout    #
    ############################################

    public function buildConfigFileContent($config_data)
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

    public function writeConfigFile($content)
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


    ###########################################################
    #   Process form SUBMITTED config data before saving      #  // joomla/administrator/components/com_config/model/application.php  -  public public function save($data)
    ###########################################################

    public function processSubmittedConfigData($new_config) {    

        // Get a fresh copy of the current settings as an array        
        $current_config = $this->getQwcrmConfigAsArray();

        // Process Google server URL (makes ure there is a https?:// - the isset prevents an install error becasue the variable is not present yet
        if(isset($new_config['google_server'])) { $new_config['google_server'] = $this->app->system->general->processInputtedUrl($new_config['google_server']); }

        // Purge the database session table if we are changing to the database handler.
        if(!defined('QWCRM_SETUP')) {

            // Empty the session table if changing to database session handling from non-database session handling
            if ($current_config['session_handler'] != 'database' && $new_config['session_handler'] == 'database')
            {
                $sql = "TRUNCATE ".PRFX."session";                    

                if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

            }
        }

        // Set the shared session configuration
        if (isset($new_config['shared_session']))
        {
            $currentShared = $current_config['shared_session'] ?? '0';

            // Has the user enabled shared sessions?
            if ($new_config['shared_session'] == 1 && $currentShared == 0)
            {
                // Generate a random shared session name (by doing this the old session becomes detached and the user is logged out)
                $new_config['session_name'] = \Joomla\CMS\User\UserHelper::genRandomPassword(16);                       
            }

            // Has the user disabled shared sessions?
            if ($new_config['shared_session'] == 0 && $currentShared == 1)
            {
                // Remove 'session_name' from $new_config - Does not exist in $new_config - so this not needed - remove when ready
                //unset($new_config['session_name']);

                // Remove 'session_name' from the live config registry and configuration.php (prevents 'session_name' getting remerged from these sources)
                $this->deleteQwcrmConfigSetting('session_name');

                // Logout the current user out silently (this should be for all users ie.e TRUNCATE #_session when on database handler, but this a work around for the current user)
                $this->app->user->logout(true);
            }
        }     

        // Return the processed config   
        return $new_config;

    }

    ############################################
    #      Send Test Email                     #
    ############################################

    public function sendTestEmail() {

        $user_details = $this->app->components->user->getRecord($this->app->user->login_user_id);

        $this->app->system->email->send($user_details['email'], _gettext("Test mail from QWcrm"), _gettext("This is a test mail sent using").' '.$this->app->config->get('email_mailer').'. '._gettext("Your email settings are correct!"), $user_details['display_name']);

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Test email initiated."));

    }

    #################################
    #   Reset ACL Permissions       #
    #################################

    public function resetAclPermissions() {

        // Remove current permissions
        $sql = "TRUNCATE ".PRFX."user_acl_page";
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

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
                ('cronjob:details', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
                ('cronjob:edit', '1', '0', '0', '0', '0', '0', '0', '0', '0'),
                ('cronjob:overview', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
                ('cronjob:run', '1', '1', '0', '0', '0', '0', '0', '0', '0'),
                ('cronjob:unlock', '1', '0', '0', '0', '0', '0', '0', '0', '0'),
                ('expense:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('expense:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('help:about', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:attribution', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('help:license', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),                
                ('invoice:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),                    
                ('invoice:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:edit', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('invoice:email', '1', '1', '1', '1', '1', '1', '0', '0', '0'),
                ('invoice:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),                
                ('invoice:overview', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:prefill_items', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('invoice:print', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('invoice:search', 1, 1, 1, 0, 1, 1, 0, 0, 0),
                ('invoice:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('otherincome:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('payment:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),                
                ('payment:new', 1, 1, 1, 1, 1, 1, 0, 0, 0),                
                ('payment:options', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('payment:search', 1, 1, 0, 0, 1, 0, 0, 0, 0), 
                ('payment:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:details', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:search', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('refund:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
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
                ('supplier:cancel', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:details', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('supplier:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:new', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('supplier:search', 1, 1, 1, 1, 1, 0, 0, 0, 0),
                ('supplier:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('user:delete', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('user:details', 1, 1, 1, 0, 1, 0, 0, 0, 0),
                ('user:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('user:login', 1, 1, 1, 1, 1, 1, 1, 1, 1),
                ('user:new', 1, 1, 0, 0, 0, 0, 0, 0, 0),
                ('user:reset', 0, 0, 0, 0, 0, 0, 0, 0, 1),
                ('user:search', 1, 1, 1, 0, 1, 0, 0, 0, 0),
                ('voucher:delete', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('voucher:details', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('voucher:edit', 1, 1, 0, 0, 1, 0, 0, 0, 0),
                ('voucher:email', '1', '1', '0', '1', '1', '1', '0', '0', '0'),
                ('voucher:new', 1, 1, 0, 0, 1, 1, 0, 0, 0),
                ('voucher:print', 1, 1, 0, 0, 1, 1, 0, 0, 0),
                ('voucher:search', 1, 1, 1, 1, 1, 1, 0, 0, 0),
                ('voucher:status', 1, 1, 0, 0, 1, 0, 0, 0, 0),
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
        
        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("ACL permissions reset to default settings."));    

        return;

    }

}