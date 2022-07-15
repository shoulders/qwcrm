<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

class General extends System {

    /* Get Functions */



    /* Update Functions */



    /* Other Functions */

   
    ############################################
    #  Error Handling - Data preperation       #
    ############################################

    function prepareErrorData($type, $data = null) {

        /* Error Page (by referring page) - only needed when using referrer - not currently used 
        if($type === 'error_page' && isset()) {
         */

            // extract the qwcrm page reference from the url (if present)
            //$referer = $_SERVER['HTTP_REFERER'] ?? null;   
            // preg_match('/^.*\?page=(.*)&.*/U', $referer, $page_string);

          /*  // compensate for home and login pages
            if($page_string[1] == '') {     
                // Must be Login or Home
                if(isset($this->app->login_token)) {
                    $error_page = 'home';
                } else {
                    $error_page = 'login';
                }    
            } else {
                $error_page = $page_string[1];            
            }       
            return $error_page;
        }
        */

        // Component (by using $_GET['component']
        if($type === 'error_component') {

            // compensate for home and dashboard
            if($data == '') {

                // Must be Login or Home
                if(isset($this->app->login_token)) {
                    $data = 'core';
                } else {
                    $data = 'core';
                } 

            }       

            return $data;

        }     

        // Page_tpl (by using $_GET['page'])
        if($type === 'error_page_tpl') {

            // compensate for home and dashboard
            if($data == '') {

                // Must be Login or Home
                if(isset($this->app->user->login_token)) {
                    $data = 'dashboard';
                } else {
                    $data = 'home';
                } 

            }      

            return $data;

        } 

        // Error Location
        if($type === 'error_location') {     

            // remove qwcrm base physical webroot path
            $data = str_replace(QWCRM_PHYSICAL_PATH, '', $data);

            // replace backslashes with forward slashes (Windows OS)
            $data = str_replace('\\','/',$data);

            // remove drive letter only (Windows OS)
            //$data = preg_replace('/^[a-zA-Z]:/', '', $data);

            // remove preceeding slash
            $data = preg_replace('/^\//', '', $data);

            return $data;

        }

        // PHP Function
        if($type === 'error_php_function') {

            // add () to the end of the php function name
            if($data != '') { $data.= '()'; }        
            return $data;
        }

        // Database Error
        if($type === 'error_database') {

            /* remove newlines from the database string (javascript version)
            if($data != '') {
                $data = str_replace("\r", '', $data);
                $data = str_replace("\n", '', $data);            
            }*/
            // remove newlines from the database string (new message system)
            if($data != '') {
                //$data = str_replace("\r", '<br>', $data);
                //$data = str_replace("\n", '<br>', $data);                
            }
            return $data;

        }    

        // SQL Query - for display
        if($type === 'error_sql_query') {

            // change newlines to <br>
            if($data != '') { $data = str_replace("\n", '<br>', $data); }        
            return $data;

        }      

        // SQL Query - for log
        if($type === 'sql_query_for_log') {

            // done seperate because used in MyITCRM migration with dirty data

            // change newlines to text \r\n
            if($data != '') {
                $data = str_replace("\r", '\r', $data);
                $data = str_replace("\n", '\n', $data);            
            }   
            return $data;

        }     

        // Database Connection Error
        if($type === 'error_database_connection') {

            /* remove newlines from the database string (javascript version)
            if($data != '') {
                $data = str_replace("\r", '', $data);
                $data = str_replace("\n", '', $data);
                $data = str_replace("'", "\\'", $data); 
            }*/
            // remove newlines from the database string (new message system)
            if($data != '') {
                //$data = str_replace("\r", '<br>', $data);
                //$data = str_replace("\n", '<br>', $data);
                //$data = str_replace("'", "\\'", $data); 
            }
            return $data;

        }  

    }

    ##########################################################
    #  Verify QWcrm install state and set routing as needed  #
    ##########################################################

    function verifyQwcrmInstallState() {

        // Temporary Development Override (stop the need to delete the /setup/ folder)- Keep
        //$this->app->db = \Factory::getDbo(); - This DB call might not be needed
        return;

        /* Is a QWcrm installation or MyITCRM migration in progress */

        // Installation is fine
        if (is_file('configuration.php') && !is_dir(SETUP_DIR)) {      
            return;        
        }

        // Prevent undefined variable errors
        \CMSApplication::$VAR['component'] = \CMSApplication::$VAR['component'] ?? null;
        \CMSApplication::$VAR['page_tpl']  = \CMSApplication::$VAR['page_tpl']  ?? null;

        // Installation is in progress
        if ($this->app->system->security->checkPageAccessedViaQwcrm('setup', 'install', 'refered-index_allowed-route_matched', \CMSApplication::$VAR['component'], \CMSApplication::$VAR['page_tpl'])) {

            \CMSApplication::$VAR['component'] = 'setup';
            \CMSApplication::$VAR['page_tpl']  = 'install';
            \CMSApplication::$VAR['themeVar']  = 'menu_off';        
            define('QWCRM_SETUP', 'install');  

            return;        


        // Migration is in progress (but if migration is passing to upgrade, ignore)
        } elseif ($this->app->system->security->checkPageAccessedViaQwcrm('setup', 'migrate', 'refered-index_allowed-route_matched', \CMSApplication::$VAR['component'], \CMSApplication::$VAR['page_tpl'])) {

            \CMSApplication::$VAR['component'] = 'setup';
            \CMSApplication::$VAR['page_tpl']  = 'migrate';
            \CMSApplication::$VAR['themeVar']  = 'menu_off';
            define('QWCRM_SETUP', 'migrate'); 

            return;        


        // Upgrade is in progress
        } elseif ($this->app->system->security->checkPageAccessedViaQwcrm('setup', 'upgrade', 'refered-index_allowed-route_matched', \CMSApplication::$VAR['component'], \CMSApplication::$VAR['page_tpl'])) {

            \CMSApplication::$VAR['component'] = 'setup';
            \CMSApplication::$VAR['page_tpl']  = 'upgrade';
            \CMSApplication::$VAR['themeVar']  = 'menu_off';        
            define('QWCRM_SETUP', 'upgrade');

            return;

        /* Redirect to choice page (optional)
        elseif (!is_file('configuration.php') && is_dir(SETUP_DIR)) && !$this->app->system->security->checkPageAccessedViaQwcrm() && !isset(\CMSApplication::$VAR['component'], \CMSApplication::$VAR['page_tpl'])) {        

            force_page('setup', 'choice');

        }*/        

        // Choice - Fresh Installation/Migrate/Upgrade (1st Run) (or refered from the migration process)
        } elseif (!is_file('configuration.php') && is_dir(SETUP_DIR) && !$this->app->system->security->checkPageAccessedViaQwcrm()) {

            // Prevent direct access to this page
            if(!$this->app->system->security->checkPageAccessedViaQwcrm(null, null, 'no_referer-routing_disallowed', \CMSApplication::$VAR['component'], \CMSApplication::$VAR['page_tpl'])) {
                header('HTTP/1.1 403 Forbidden');
                die(_gettext("No Direct Access Allowed."));
            }

            // Allow only root or index.php
            if($_SERVER['REQUEST_URI'] != QWCRM_BASE_PATH && $_SERVER['REQUEST_URI'] != QWCRM_BASE_PATH.'index.php') {
                header('HTTP/1.1 404 Not Found');
                die(_gettext("This page does not exist."));
            }        

            // Move Direct page access control to the pages controller (i.e. I might allow direct access to setup:choice)        
            \CMSApplication::$VAR['component'] = 'setup';
            \CMSApplication::$VAR['page_tpl']  = 'choice';
            \CMSApplication::$VAR['themeVar']  = 'menu_off';        

            /* This allows the use of the database ASAP in the setup process
            if (defined('PRFX') && \Factory::getDbo()->isConnected() && $this->app->system->general->get_qwcrm_database_version_number()) {
                define('QWCRM_SETUP', 'database_allowed'); 
            } else {
                define('QWCRM_SETUP', 'install'); 
            }*/
            define('QWCRM_SETUP', 'install');

            return;       

        // Appears to be a valid installation but the setup directory is still present
        } elseif (is_file('configuration.php') && is_dir(SETUP_DIR)) {

            // Prevent direct access to this page
            if(!$this->app->system->security->checkPageAccessedViaQwcrm(null, null, 'no_referer-routing_disallowed', \CMSApplication::$VAR['component'], \CMSApplication::$VAR['page_tpl'])) {
                header('HTTP/1.1 403 Forbidden');
                die(_gettext("No Direct Access Allowed."));
            }        

            // Allow only root or index.php
            if(!$this->app->system->security->checkPageAccessedViaQwcrm(null, null, 'root_only')) {
                header('HTTP/1.1 404 Not Found');
                die(_gettext("This page does not exist."));
            }               

            // This will compare the database and filesystem and automatically start the upgrade if valid (no need for setup:choice)       
            $this->app->system->general->compareQwcrmFilesystemAndDatabase(\CMSApplication::$VAR);    

        // Fallback option for those situations I have not thought about
        } else {

            die('
                    <div style="color: red;">'.
                    _gettext("Something went wrong with your installation of QWcrm.").'<br>'.
                    _gettext("You might have a configuration.php file that should not be present or is corrupt.").'<br>'.
                    _gettext("Check your setup folder is present.").
                    '</div>'
                ); 

        }

        return;

    }

    #########################################################
    #  Compare the QWcrm file system and database versions  #  // This is only run if the /setup/ dir exists
    #########################################################

    function compareQwcrmFilesystemAndDatabase() {
        
        // Get the QWcrm database version number (assumes database connection is good)
        $qwcrm_database_version = $this->app->system->general->getQwcrmDatabaseVersionNumber();

        // File System and Database versions match(not needed handles in opening 'if' statement, left for reference)
        if(version_compare(QWCRM_VERSION, $qwcrm_database_version,  '=')) {

            die(
                '<div style="color: red;">'.
                _gettext("You must delete the 'Setup' directory before you can use QWcrm.").'<br>'.
                '<strong>'.QWCRM_PART_URL.SETUP_DIR.'</strong><br>'.
                '<strong>'.QWCRM_PHYSICAL_PATH.SETUP_DIR.'</strong>'.
                '</div>'
                ); 

        } 

        /* If the file system is newer than the database - run upgrade (this loads setup:upgrade directly)
        if(version_compare(QWCRM_VERSION, $qwcrm_database_version, '>')) {             
            \CMSApplication::$VAR['component']     = 'setup';
            \CMSApplication::$VAR['page_tpl']      = 'upgrade';
            \CMSApplication::$VAR['themeVar']      = 'menu_off';
            define('QWCRM_SETUP', 'install'); 
            return;
        }*/

        // If the file system is newer than the database - run upgrade (this loads setup:choice but flags it as an upgrade directly)
        if(version_compare(QWCRM_VERSION, $qwcrm_database_version, '>')) {             
            \CMSApplication::$VAR['component']     = 'setup';
            \CMSApplication::$VAR['page_tpl']      = 'choice';
            \CMSApplication::$VAR['themeVar']      = 'menu_off';
            \CMSApplication::$VAR['setup_type']    = 'upgrade';
            define('QWCRM_SETUP', 'install'); 
            return;
        }

        // Setup failed / Invalid configuration.php
        if($qwcrm_database_version == false) { 
            die('<div style="color: red;">'._gettext("A previous setup attempt never completed successfully and/or there is an invalid configuration.php file present or the database prefix is wrong.").'</div>');            
        }

        // Failed upgrade
        if($qwcrm_database_version == '0.0.0') { 
            die('<div style="color: red;">'._gettext("The upgrade never completed successfully. Check the upgrade and error logs.").'</div>');
        }

        // If the file system is older than the database
        if(version_compare(QWCRM_VERSION, $qwcrm_database_version,  '<')) {             
            die('<div style="color: red;">'._gettext("The file system is older than the database. Check the logs and your settings.").'</div>');
        }

        return;

    }

    ################################################
    #  Get QWcrm version number from the database  #
    ################################################

    function getQwcrmDatabaseVersionNumber() {

        $sql = "SELECT * FROM ".PRFX."version ORDER BY ".PRFX."version.database_version DESC LIMIT 1";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->fields['database_version'];

    }

    ####################################################################
    #  check the selected template is valid for this version of QWcrm  #  // not currently used, it should be
    ####################################################################

    function checkTemplateCompatible() {

        // Get template details
        $template_details = $this->parseXmlFileIntoArray(THEME_DIR.'templateDetails.xml');

        // is the QWCRM version too low to run the template
        if (version_compare(QWCRM_VERSION, $template_details['qwcrm_min_version'], '<')) {

            return false;
            /*echo _gettext("The current version or QWcrm is too low to use this template.").'<br>';
            echo _gettext("Your current version of QWcrm is").' '.QWCRM_VERSION.'<br>';
            echo _gettext("The template supports QWcrm versions in the range").': '.$template_details['qwcrm_min_version'].' -> '.$template_details['qwcrm_max_version'];
            die();*/

        }

        // is the QWCRM version to high to run the template
        if (version_compare(QWCRM_VERSION, $template_details['qwcrm_max_version'], '>')) {

            return false;
            /*echo _gettext("The current version or QWcrm is too high to use this template.").'<br>';
            echo _gettext("Your current version of QWcrm is").' '.QWCRM_VERSION.'<br>';
            echo _gettext("The template supports QWcrm versions in the range").': '.$template_details['qwcrm_min_version'].' -> '.$template_details['qwcrm_max_version'];
            die();*/

        }

        return true;

    }

    ################################################
    #   Get MySQL version                          #
    ################################################

    function getMysqlVersion() {

        // adodb.org prefered method - does not bring back complete string - [server_info] =&gt; 5.5.5-10.1.13-MariaDB - Array ( [description] => 10.1.13-MariaDB [version] => 10.1.13 ) 
        //$this->app->db->ServerInfo();

        // Extract and return the MySQL version - print_r() this and it gives you all of the values - 5.5.5-10.1.13-MariaDB
        preg_match('/^[vV]?(\d+\.\d+\.\d+)/', $this->app->db->_connectionID->server_info, $matches);
        return $matches[1];    

    }

    ############################################
    #   Parse XML file into an array           #
    ############################################

    function parseXmlStingIntoArray($string) {

        // SimpleXML - Convert an XML file into a SimpleXMLElement object, then output keys and elements of the object:
        $xml_object = simplexml_load_string($string);

        // Convert Object into an array
        $xml_object = get_object_vars($xml_object);

        // Return the array
        return $xml_object;

    }

    ############################################
    #   Parse XML file into an array           #
    ############################################

    function parseXmlFileIntoArray($file) {

        // Remove base path to make reference relative
        $file = str_replace(QWCRM_BASE_PATH, '', $file);

        // SimpleXML - Convert an XML file into a SimpleXMLElement object, then output keys and elements of the object:
        $xml_object = simplexml_load_file($file);

        // Convert Object into an array
        $xml_object = get_object_vars($xml_object);

        // Return the array
        return $xml_object;

        /*
        ALTERNATIVE Version - reference only

        // xml_parse_into_struct() old method - keep for reference

        // Load file into memory
        if (!($fp = fopen($file, 'r'))) {
           die(_gettext("Unable to open XML file.").' : '.$file);
        }
        $xmldata = fread($fp, filesize($file));
        fclose($fp);

        // Start the XML parser
        $xmlparser = xml_parser_create();

        // Convert XML data into an array
        xml_parse_into_struct($xmlparser, $xmldata, $values, $index);

        // Frees the given XML parser - I assume to reduce memory usage
        xml_parser_free($xmlparser);    

        return $index;
        */

    }

    /* Logging */

    ############################################
    #  Write a record to the Access Log        #  // This will create an apache compatible access log (Combined Log Format)
    ############################################

    function writeRecordToAccessLog() {    

        // Apache log format
        // https://httpd.apache.org/docs/2.4/logs.html
        // http://docstore.mik.ua/orelly/webprog/pcook/ch11_14.htm
        /* Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined */    
        // $remote_host, $logname, $user, $time, $method, $request, $protocol, $status, $bytes, $referer, $user_agent

        $remote_ip      = $_SERVER['REMOTE_ADDR'];                              // only using IP - not hostname lookup
        $logname        = '-';                                                  //  This is the RFC 1413 identity of the client determined by identd on the clients machine. This information is highly unreliable and should almost never be used except on tightly controlled internal networks.

        // Login User - substituting qwcrm user for the traditional apache HTTP Authentication
        if(!$this->app->user->login_username) {
            $username = '-';
        } else {
            $username = $this->app->user->login_username;
        }  

        $time           = date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']);    // Time in apache log format

        // The Following 3 items make up the Request
        $method         = $_SERVER['REQUEST_METHOD'];                           // GET/POST
        $uri            = $_SERVER['REQUEST_URI'];                              // the URL
        $protocol       = $_SERVER['SERVER_PROTOCOL'];                          // HTTP/1.0    

        $status         = '-';                                                  // page returned status - dont think I can get this 200,401,403,404 etc..
        $bytes          = '-';                                                  // cant get this - page size / payload size

        // Referring URL
        if(isset($_SERVER['HTTP_REFERER'])) {
            $referring_url = $_SERVER['HTTP_REFERER']; 
        } else {
            $referring_url = '-';
        }   

        // User Agent - if there is no user agent or it cannot be detected then apache uses "-"
        if(isset($_SERVER['HTTP_USER_AGENT']) && ($_SERVER['HTTP_USER_AGENT'] != '')) {
            $user_agent = $_SERVER['HTTP_USER_AGENT']; 
        } else {
            $user_agent = '-';
        } 

        $log_entry = $remote_ip.' '.$logname.' '.$username.' '.$time.' "'.$method.' '.$uri.' '.$protocol.'" '.$status.' '.$bytes.' "'.$referring_url.'" "'.$user_agent.'"'."\r\n";

        // Write log entry   
        if(!$fp = fopen(ACCESS_LOG, 'a')) {        
            $this->app->system->page->forceErrorPage('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Access Log to save the record."));
        }

        fwrite($fp, $log_entry);
        fclose($fp);

        return;

    }

    ############################################
    #  Write a record to the Activity Log      #
    ############################################

    function writeRecordToActivityLog($record, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null) {

        // if activity logging not enabled exit
        if($this->app->config->get('qwcrm_activity_log') != true) { return; }

        /* Use any supplied IDs instead of $GLOBALS[] counterpart
        if(!$employee_id)   { $employee_id  = $GLOBALS['employee_id'];  }
        if(!$client_id)     { $client_id  = $GLOBALS['client_id'];      }
        if(!$workorder_id)  { $workorder_id = $GLOBALS['workorder_id']; }
        if(!$invoice_id)    { $invoice_id   = $GLOBALS['invoice_id'];   }*/   

        // Apache Login User - using qwcrm user to emulate the traditional apache HTTP Authentication
        if(!$this->app->user->login_username) {
            $username = '-';
        } else {
            $username = $this->app->user->login_username;
        } 

        // Build log entry
        $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", time()).','.$this->app->user->login_user_id.','.$employee_id.','.$client_id.','.$workorder_id.','.$invoice_id.','.'"'.$record.'"'."\r\n";

        // Write log entry  
        if(!$fp = fopen(ACTIVITY_LOG, 'a')) {        
            $this->app->system->page->forceErrorPage('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Activity Log to save the record."));
        }

        fwrite($fp, $log_entry);
        fclose($fp);

        return;

    }

    ############################################
    #  Write a record to the Error Log         #
    ############################################

    function writeRecordToErrorLog($error_page, $error_type, $error_location, $php_function, $database_error, $error_msg) {

        // it is not - $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to count the matching Work Orders."));

        // Apache Login User - using qwcrm user to emulate the traditional apache HTTP Authentication
        if(!$this->app->user->login_username) {
            $username = '-';
        } else {
            $username = $this->app->user->login_username;
        }

        // Build log entry - perhaps use the apache time stamp below
        $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", $_SERVER['REQUEST_TIME']).','.$error_page.','.$error_type.','.$error_location.','.$php_function.','.$database_error.','.$error_msg."\r\n";

        // Write log entry  
        if(!$fp = fopen(ERROR_LOG, 'a')) {        
            $this->app->system->page->forceErrorPage('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Error Log to save the record."));
        }

        fwrite($fp, $log_entry);
        fclose($fp);

        return;

    }

    /* Date and Time */

    ##########################################
    #      Get Date Formats                  #
    ##########################################

    function getDateFormats() {

        $sql = "SELECT * FROM ".PRFX."company_date_formats";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();

    }    

    ##########################################
    #   Convert Date into Unix Timestamp     #  // $date_format is not currently used
    ##########################################

    function dateToTimestamp($date_to_convert, $date_format = null) {   

        // http://php.net/manual/en/datetime.createfromformat.php
        // Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
        // can also use - instead of /
        // the ! allows the use without supplying the time portion
        // this works for all formats of dates where as mktime() might be a bit dodgy

        switch(!is_null($date_format) ? $date_format : DATE_FORMAT) {

            case '%d/%m/%Y':   
            return DateTime::createFromFormat('!d/m/Y', $date_to_convert)->getTimestamp();

            case '%d/%m/%y':    
            return DateTime::createFromFormat('!d/m/y', $date_to_convert)->getTimestamp();

            case '%m/%d/%Y':   
            return DateTime::createFromFormat('!m/d/Y', $date_to_convert)->getTimestamp();

            case '%m/%d/%y':    
            return DateTime::createFromFormat('!m/d/y', $date_to_convert)->getTimestamp();

            case '%Y-%m-%d':         
            return DateTime::createFromFormat('!Y-m-d', $date_to_convert)->getTimestamp();

            // This should be for MySQL DATETIME format
            case 'Y-m-d H:i:s' || 'datetime':   
            return DateTime::createFromFormat('Y-m-d H:i:s', $date_to_convert)->getTimestamp();

        }

        return;

    }

    ################################################
    #    Smarty Date and Time to Unix Timestamp    #  // only used in schedule at the minute - smartytime_to_otherformat
    ################################################

    /*
     * Examples taken from schedule insert and update
     * 
        // Get times in MySQL DATETIME from Smartytime (12 Hour Clock Format) (date/hour/minute/second)
        $start_time = smartytime_to_otherformat('datetime', $start_date, $start_time['Time_Hour'], $start_time['Time_Minute'], '0', '12', $start_time['time_meridian']);
        $end_time   = smartytime_to_otherformat('datetime', $end_date, $end_time['Time_Hour'], $end_time['Time_Minute'], '0', '12', $end_time['time_meridian']);

        // Get times in MySQL DATETIME from Smartytime (24 Hour Clock Format) (date/hour/minute/second)
        $start_time = smartytime_to_otherformat('datetime', $VAR['start_date'], $VAR['StartTime']['Time_Hour'], $VAR['StartTime']['Time_Minute'], '0', '24');
        $end_time   = smartytime_to_otherformat('datetime', $VAR['end_date'], $VAR['EndTime']['Time_Hour'], $VAR['EndTime']['Time_Minute'], '0', '24');
     */

    function smartytimeToOtherformat($format, $date, $hour, $minute, $second, $clock, $meridian = null) {

        // When using a 12 hour clock
        if($clock == '12') {

            // Create timestamp from date
            $timestamp = $this->dateToTimestamp($date);

            // if hour is 12am set hour as 0 - for correct calculation as no zero hour
            if($hour == '12' && $meridian == 'am') {$hour = '0';}

            // Convert hours into seconds and then add - AM/PM aware
            if($meridian == 'pm') {$timestamp += ($hour * 60 * 60 + 43200 );} else {$timestamp += ($hour * 60 * 60);}    

            // Convert minutes into seconds and add
            $timestamp += ($minute * 60);

            // Add seconds
            $timestamp += $second;        

            // Return time in DATETIME format
            if($format === 'datetime') {
                return date('Y-m-d H:i:s', $timestamp);
            }

            // Return a Timestamp
            if($format === 'timestamp') {
                return $timestamp;
            }

        }

        // When using a 24 hour clock
        if($clock == '24') {

            // Create timestamp from date
            $timestamp = $this->dateToTimestamp($date);        

            // Convert hours into seconds and then add
            $timestamp += ($hour * 60 * 60 );

            // Convert minutes into seconds and add
            $timestamp += ($minute * 60);

            // Add seconds
            $timestamp += $second;        

            // Return time in DATETIME format
            if($format === 'datetime') {
                return date('Y-m-d H:i:s', $timestamp);
            }

            // Return a Timestamp
            if($format === 'timestamp') {
                return $timestamp;
            }

        }

    }

    #############################################
    #    Get Timestamp from year/month/day      #
    #############################################

    function convertYearMonthDayToTimestamp($year, $month, $day) {  

            return DateTime::createFromFormat('!Y/m/d', $year.'/'.$month.'/'.$day)->getTimestamp();   

    }

    ##########################################
    #   Timestamp to calendar date format    #
    ##########################################

    function timestampToCalendarFormat($timestamp) {

        return date('Ymd', $timestamp);

    }

    ##########################################
    #     Timestamp to date                  #  // not used anywhere at the minute
    ##########################################

    function timestampToDate($timestamp, $date_format = null) {    

        switch(!is_null($date_format) ? $date_format : DATE_FORMAT) {

            case '%d/%m/%Y':
            return date('d/m/Y', $timestamp);        

            case '%d/%m/%y':
            return date('d/m/y', $timestamp);       

            case '%m/%d/%Y':
            return date('m/d/Y', $timestamp);        

            case '%m/%d/%y':
            return date('m/d/y', $timestamp);

            case '%Y-%m-%d':
            return date('Y-m-d', $timestamp);

        }

    }

    #####################################################
    #   Convert a timestamp into MySQL DATE Format      #
    #####################################################

    function timestampMysqlDate($timestamp) {       

       // If there is no timestamp return an empty MySQL DATE
        if(!$timestamp) {

            return null;

        } else {        

            return date('Y-m-d', $timestamp);

        }

    }

    #####################################################
    #   Convert a timestamp into MySQL DATETIME Format  #  // not currently used
    #####################################################

    function timestampMysqlDatetime($timestamp) { 

        // If there is no timestamp return an empty MySQL DATETIME
        if(!$timestamp) {

            return null;

        } else {

            return date('Y-m-d H:i:s', $timestamp);

        }   

    }

    ############################################
    #   Convert Date into MySQL DATE Format    #  // $date_format is not currently used
    ############################################

    function dateToMysqlDate($date_to_convert, $date_format = null) {   

        // http://php.net/manual/en/datetime.createfromformat.php
        // Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
        // can also use - instead of /
        // the ! allows the use without supplying the time portion    

        switch(!is_null($date_format) ? $date_format : DATE_FORMAT) {

            case '%d/%m/%Y':   
            return DateTime::createFromFormat('!d/m/Y', $date_to_convert)->format('Y-m-d');

            case '%d/%m/%y':    
            return DateTime::createFromFormat('!d/m/y', $date_to_convert)->format('Y-m-d');

            case '%m/%d/%Y':   
            return DateTime::createFromFormat('!m/d/Y', $date_to_convert)->format('Y-m-d');

            case '%m/%d/%y':    
            return DateTime::createFromFormat('!m/d/y', $date_to_convert)->format('Y-m-d');

            case '%Y-%m-%d':   
            return DateTime::createFromFormat('!Y-m-d', $date_to_convert)->format('Y-m-d');

        }

        return;

    }

    ##################################################
    #   Get current date in MySQL DATE Format        #  // gives current datetime unless a timstamp is used then that is converted
    ##################################################

    function mysqlDate($timestamp = null) {       

        // These do the same job and are for reference
        //(new DateTime('now'))->format('Y-m-d H:i:s');    
        //date('Y-m-d', time());  // The time() argument is redundant for current time

        return is_null($timestamp) ? date('Y-m-d') : date('Y-m-d', $timestamp);

    }

    ##################################################
    #   Get current time in MySQL DATETIME Format    #  // gives current datetime unless a timstamp is used then that is converted
    ##################################################

    function mysqlDatetime($timestamp = null) {       

        // These do the same job and are for reference
        //(new DateTime('now'))->format('Y-m-d H:i:s');    
        //date('Y-m-d H:i:s', time());  // the time() argument is redundant for current time

        return is_null($timestamp) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $timestamp);

    }

    ##############################################
    #   Build MySQL DATETIME                     # 
    ##############################################

    function buildMysqlDatetime($hour = null, $minute = null, $second = null, $month = null, $day = null, $year = null) {

        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        return date('Y-m-d H:i:s', $timestamp);

    }

    #########################################################
    #   Return Date in correct format from year/month/day   #  // only used in schedule
    #########################################################

    function convertYearMonthDayToDate($year, $month, $day) {    

        // Ensure months supplied as 2 digits
        if(strlen($month) == 1) {$month = '0'.$month;}

        // Ensure days supplied as 2 digits
        if(strlen($day) == 1) {$day = '0'.$day;}

        switch(DATE_FORMAT) {

            case '%d/%m/%Y':
            return $day."/".$month."/".$year;

            case '%d/%m/%y':
            return $day.'/'.$month.'/'.substr($year, 2);

            case '%m/%d/%Y':
            return $month.'/'.$day.'/'.$year;

            case '%m/%d/%y':
            return $month.'/'.$day.'/'.substr($year, 2);

            case '%Y-%m-%d':
            return $year.'-'.$month.'-'.$day;

        }

    }

    /* Other */

    ##############################################
    #  Clear any onscreen notifications          #   // not currently used
    ##############################################

    public function ajaxClearOnscreenNotifications() {

        echo "<script>clearSystemMessages();</script>";

    }

    ##############################################  // this is needed for messages when pages are requested via AJAX (emails/config)
    #  Output System Messages store onscreen     #  // Currently only the complete message store is sent, not individual messages
    ##############################################

    public function ajaxOutputSystemMessagesOnscreen() {        
        
        // Clear any onscreen notifications
        echo "<script>clearSystemMessages();</script>";
        
        // Output the new messages via the template JS function
        echo "<script>processSystemMessages('".$this->escapeForJavascript($this->app->system->variables->systemMessagesReturnStore())."');</script>";

    }

    ##############################################
    #  Escape string for use in Javascript       #
    ##############################################

    function escapeForJavascript($text){

        return strtr(nl2br($text), array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/'));

    }

    ##############################################
    #  Used for setup and button control         #   // this is needed for messages when pages are requested via ajax (emails/config)
    ##############################################

    function toggleElementById($element_id, $action = 'hide') {

        /* JQuery Version */
        if($action == 'hide') {

            echo '
            <script>                
                $("#'.$element_id.'").hide();
            </script>';

        } 

        if ($action == 'show') {

            echo '
            <script>              

                $("#'.$element_id.'").show();              

            </script>';

        }

        if($action == 'disable') {

            echo '
            <script>                
                $("#'.$element_id.'").prop("disabled", true);
            </script>';

        } 

        if ($action == 'enable') {

            echo '
            <script>
                $("#'.$element_id.'").prop("disabled", false);
            </script>';

        }

        /* Javascript Version (for reference only) 
        if($action == 'hide') {

            echo '
                <script>                

                    var x = document.getElementById("'.$element_id.'");
                    if (x.style.display !== "none") {
                        x.style.display = "none";
                    }             

                </script>';

        } elseif ($action == 'show') {

            echo '
                <script>              

                    var x = document.getElementById("'.$element_id.'");
                    if (x.style.display !== "block") {
                        x.style.display = "block";
                    }               

                </script>';

        }*/

    }

    /* Smarty Section */

    ############################################
    #      Clear Smarty Cache                  #
    ############################################

    function clearSmartyCache() {

        // clear the entire cache
        $this->app->smarty->clearAllCache();

        // clears all files over one hour old
        //$this->app->smarty->clearAllCache(3600);

        // Output the system message to the browser   
        $this->app->system->variables->systemMessagesWrite('success', _gettext("The Smarty cache has been emptied successfully."));
        $this->ajaxOutputSystemMessagesOnscreen();

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Smarty Cache Cleared."));

    }

    ############################################
    #      Clear Smarty Compile                #
    ############################################

    function clearSmartyCompile() {

        // clear a specific template resource
        //$this->app->smarty->clearCompiledTemplate('index.tpl');

        // clear entire compile directory
        $this->app->smarty->clearCompiledTemplate();

        // Output the system message to the browser
        $this->app->system->variables->systemMessagesWrite('success', _gettext("The Smarty compile directory has been emptied successfully."));
        $this->ajaxOutputSystemMessagesOnscreen();

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Smarty Compile Cache Cleared."));    

    }

    ################################################
    #         Load Languages                       #  List the available languages and return as an array
    ################################################

    function loadLanguages() {

        // Get the array of directories
        $languages = glob(LANGUAGE_DIR . '*', GLOB_ONLYDIR);

        // Remove path from directory and just leave the directory name (i.e. en_GB)
        $languages = array_map('basename', $languages);

        // Make sure that en_GB is always first in the list (find it by value, delete and then re-add)
        if (($key = array_search('en_GB', $languages)) !== false) {
            unset($languages[$key]);
            array_unshift($languages, 'en_GB');
        }

        // Remove '_gettext_only' directory    
        if (($key = array_search('_gettext_only', $languages)) !== false) {
            unset($languages[$key]);
        }

        // Re-index the array - This is not needed but keeps things neat
        $languages = array_values($languages);

        return $languages;

    }

    ################################################
    #         Load Language                        #  // Most people use $locale instead of $language
    ################################################

    function loadLanguage() {

        // Load compatibility layer (motranslator)
        PhpMyAdmin\MoTranslator\Loader::loadFunctions();

        // Autodetect Language - I18N support information here
        if(function_exists('locale_accept_from_http') && ($this->app->config->get('autodetect_language') == '1' || $this->app->config->get('autodetect_language') == null)) {

            // Use the locale language if detected or default language or british english (format = en_GB) - // the isset() is needed for mPDF (might not be needed)
            if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || !$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

                // Set default language as the chosen language or fallback to british english
                if(!$language = $this->app->config->get('default_language')) {
                    $language = 'en_GB';
                }

            }

            // If there is no language file for the locale, set language to british english - This allows me to use CONSTANTS in translations but bypasses normal fallback mechanism for _gettext()
            if(!is_file(LANGUAGE_DIR.$language.'/LC_MESSAGES/site.po')) {
                $language = 'en_GB';    
            }

        } else {

            // Set default language or fallback to british english
            if(!$language = $this->app->config->get('default_language')) {
                $language = 'en_GB';
            }

        }

        // Here we define the global system locale given the found language (apparently can also use putenv("LANGUAGE=$language");)
        putenv("LANG=$language");

        // https://www.php.net/manual/en/function.setlocale.php

        // This sets local for all these settings - LC_COLLATE, LC_CTYPE, LC_MONETARY, LC_NUMERIC, LC_TIME, LC_MESSAGES
        // This might be useful for date or money formatting etc...
        _setlocale(LC_ALL, $language);

        // Set the LC_MESSAGES store - This sets the folder name which stores the LC_MESSAGES folder - This does not work
        //_setlocale(LC_MESSAGES, $language);

        // Set the text domain - This sets the name of the .mo file
        $textdomain = 'site';

        // This will make _gettext look for ../language/<lang>/LC_MESSAGES/site.mo
        _bindtextdomain($textdomain, LANGUAGE_DIR);

        // Indicates in what encoding the file should be read
        _bind_textdomain_codeset($textdomain, 'UTF-8');

        // Here we indicate the default domain the _gettext() calls will respond to - The default .mo file
        _textdomain($textdomain);

    }

    ################################################
    #   Process and correct user inputted URLs     #  // make sure the url has a https?:// before being added to the database, if not add one
    ################################################

    function processInputtedUrl($url) {

        // If no URL has been submitted return nothing
        if($url == '') {
            return '';
        }

        if ($parsed_url = parse_url($url)) {

            // Check if there is a protocol(scheme) set
            if (!isset($parsed_url['scheme'])) {

                return 'http://'.$url;

            } else {

                return $url;

            }        

        // If the url is corrupt return nothing    
        } else {

            return '';


        }

    }
    
}