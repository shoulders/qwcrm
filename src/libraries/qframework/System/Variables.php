<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Variables extends System {

    ################################################
    #  Load and set the System's global variables  #
    ################################################

    public function loadSystemVariables() {

        ##########################################################
        #   Assign Global PHP Variables                          #
        ##########################################################

        // If there are DATABASE ERRORS, they will present here (white screen) when verify QWcrm function is not on
        if(!defined('QWCRM_SETUP')) {
            
            $company_details = $this->app->components->company->getRecord();
            define('DATE_FORMAT',   $company_details['date_format']);
            define('QW_TAX_SYSTEM', $company_details['tax_system'] );
        }

        ##########################################################################
        #   Assign variables into Smarty for use by component templates          #
        ##########################################################################

        // QWcrm System Folders
        $this->app->smarty->assign('base_path',                QWCRM_BASE_PATH             );      // set base path, useful for javascript links i.e. 404.tpl
        $this->app->smarty->assign('media_dir',                QW_MEDIA_DIR                );      // set media directory

        // QWcrm Theme Directory Template Variables
        $this->app->smarty->assign('theme_dir',                THEME_DIR                   );      // set theme directory
        $this->app->smarty->assign('theme_images_dir',         THEME_IMAGES_DIR            );      // set theme images directory
        $this->app->smarty->assign('theme_css_dir',            THEME_CSS_DIR               );      // set theme CSS directory
        $this->app->smarty->assign('theme_js_dir',             THEME_JS_DIR                );      // set theme JS directory

        // QWcrm Theme Directory Template Smarty File Include Path Variables
        $this->app->smarty->assign('theme_js_dir_finc',        THEME_JS_DIR_FINC           );

        // This assigns framework globals to smarty and also prevents undefined variable errors (mainly for the menu)
        $this->app->smarty->assign('user_id', \CMSApplication::$VAR['user_id'] ?? null);
        $this->app->smarty->assign('employee_id', \CMSApplication::$VAR['employee_id'] ?? null);
        $this->app->smarty->assign('client_id', \CMSApplication::$VAR['client_id'] ?? null);
        $this->app->smarty->assign('workorder_id', \CMSApplication::$VAR['workorder_id'] ?? null);
        $this->app->smarty->assign('schedule_id', \CMSApplication::$VAR['schedule_id'] ?? null);
        $this->app->smarty->assign('invoice_id', \CMSApplication::$VAR['invoice_id'] ?? null);
        $this->app->smarty->assign('voucher_id', \CMSApplication::$VAR['voucher_id'] ?? null);
        $this->app->smarty->assign('payment_id', \CMSApplication::$VAR['payment_id'] ?? null);        
        $this->app->smarty->assign('expense_id', \CMSApplication::$VAR['expense_id'] ?? null);
        $this->app->smarty->assign('otherincome_id', \CMSApplication::$VAR['otherincome_id'] ?? null);
        $this->app->smarty->assign('supplier_id', \CMSApplication::$VAR['supplier_id'] ?? null);
        $this->app->smarty->assign('cronjob_id', \CMSApplication::$VAR['cronjob_id'] ?? null);
        $this->app->smarty->assign('creditnote_id', \CMSApplication::$VAR['creditnote_id'] ?? null);

        // Used throughout the site
        if(!defined('QWCRM_SETUP')) {
            $this->app->smarty->assign('currency_sym',  $company_details['currency_symbol']     );
            $this->app->smarty->assign('company_logo',  QW_MEDIA_DIR . $company_details['logo'] );
            // Only build the link if there is a logo set.
            if($this->app->components->company->getRecord('logo'))
            {
                $this->app->smarty->assign('company_logo', QW_MEDIA_DIR . $this->app->components->company->getRecord('logo') );
            } else {
                $this->app->smarty->assign('company_logo', '');
            }
            $this->app->smarty->assign('qw_tax_system', QW_TAX_SYSTEM                           ); 
            $this->app->smarty->assign('date_format',   DATE_FORMAT                             );
        }

        #############################
        #        Exit Function      #
        #############################

        return;

    }

    #####################################
    #  Set the User's Smarty Variables  #  // Empty if not logged in or installing (except for usergroup)
    #####################################

    public function smartySetUserVariables() {     

        $this->app->smarty->assign('login_user_id',            $this->app->user->login_user_id          );
        $this->app->smarty->assign('login_username',           $this->app->user->login_username         );
        $this->app->smarty->assign('login_usergroup_id',       $this->app->user->login_usergroup_id     );
        $this->app->smarty->assign('login_display_name',       $this->app->user->login_display_name     );
        $this->app->smarty->assign('login_token',              $this->app->user->login_token            );
        $this->app->smarty->assign('login_is_employee',        $this->app->user->login_is_employee      );
        $this->app->smarty->assign('login_client_id',          $this->app->user->login_client_id        );

        return;

    }

    ###########################################
    #  POST Emulation - for server to server  #  // Might only work for logged in users, need to check, but fails on logout because session data is destroyed?
    ###########################################

    /*
     * this writes into the session registry/$data
     * the register_shutdown_function() in native.php registers the function save() to be run as the last thing run by the script
     * $post_emulation_variable is created in the session registry.
     * It does work but i cannot control if the post varibles stay in the database store. Is this correct???
     * There is a timer to prevent abuse of this emulation and to keep messages valid. It is set to 5 seconds.
     */

    // This writes to the $post_emulation_varible and then the varible to the store
    public function postEmulationWrite($key, $value) {

        // Refresh the store timer to keep it fresh
        \Factory::getSession()->set('post_emulation_timer', time());

        // Set the varible in the $post_emulation_store variable
        \Factory::getSession()->post_emulation_store[$key] = $value;

        // Save the whole $post_emulation_store varible into the registry (does this for every variable write)
        \Factory::getSession()->set('post_emulation_store', \Factory::getSession()->post_emulation_store);

    }

    // This reads the data from $post_emulation_varible
    public function postEmulationRead($key) {

        // Refresh the store timer to keep it fresh
        \Factory::getSession()->set('post_emulation_timer', time());

        // Read a varible from the store and return it
        return \Factory::getSession()->post_emulation_store[$key];

    }

    public function postEmulationReturnStore($keep_store = false) {

        // Make temporary copy of the post store
        $post_store = \Factory::getSession()->get('post_emulation_store');

        // Delete Stale Post Store - make sure the store is not an old one by putting a time limit on the validity
        if(time() - \Factory::getSession()->get('post_emulation_timer') > 5 ) {        

            // Empty the registry store -  but keep it as an array
            \Factory::getSession()->set('post_emulation_store', array());

            // Empty the $post_emulation_store - not 100% i need this
            \Factory::getSession()->post_emulation_store = array();

        }

        // This is used for testing that the varibles get stored
        if($keep_store === true) {

            \Factory::getSession()->set('post_emulation_store', $post_store);

        } else {

            // Empty the registry store -  but keep it as an array
            \Factory::getSession()->set('post_emulation_store', array());

            // Empty the $post_emulation_store - not 100% i need this
            \Factory::getSession()->post_emulation_store = array();

        }

        // Set the store timer to zero
        \Factory::getSession()->set('post_emulation_timer', '0');

        // Return the post store - this compensates for logout
        if(!is_array($post_store)) {
            return array();
        } else {
            return $post_store;
        }

    }


    ######################################  // This builds the message store which set message display order
    #  System Messages                   #  // This will take any messages from \CMSApplication::$VAR and put them into \CMSApplication::$messages
    ######################################  // This has all of the bootstrap message types here

    // Build the System Messages Store
    public function systemMessagesBuildStore($grabVar = false) {

        // Build the array in the correct order (for display purposes)
        $types = array('primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark');

        // Loop through the different types or system message    
        foreach ($types as $type) {

            // Add this Message Type to the global System Message Store
            \CMSApplication::$messages[$type] = array();

            // Get single message varibles from \CMSApplication::$VAR (legacy)
            if($grabVar) {
                // Check for this message type in \CMSApplication::$VAR and set to system message store
                if(isset(\CMSApplication::$VAR['msg_'.$type])) {
                    \CMSApplication::$messages[$type][] = strip_tags(\CMSApplication::$VAR['msg_'.$type], '<br>');
                    unset(\CMSApplication::$VAR['msg_'.$type]);
                }     
            }   

        } 

        if($grabVar) {

            // Merge Force Page Message Store into the System Message Store
            \CMSApplication::$messages = array_merge(\CMSApplication::$messages, $this->systemMessagesReturnForcePageStore());

        }

        return;

    }

    ########################################
    #  Write a system message to the store #
    ########################################
    public function systemMessagesWrite($type, $message) {

        \CMSApplication::$messages[$type][] = $message;

        return;

    }

    ###############################
    #  Return the Messages Store  #  //  in html format or array
    ###############################
    
    public function systemMessagesReturnStore($keep_store = false, $format = 'html') {

        // Remove all empty message type holders (}not sure why i need this)
        \CMSApplication::$messages = array_filter(\CMSApplication::$messages);
        
        // Return Message store as an array
        if($format === 'array') {
            $messages = \CMSApplication::$messages;
        }

        // Return Message store as formatted HTML
        if($format === 'html') {

            // HTML holder
            $html = '';    

            // Loop through the different types of message and build HTML
            foreach (\CMSApplication::$messages as $messageStoreType => $messages) {

                foreach ($messages as $message) {

                    $html .= "<div class=\"alert alert-$messageStoreType\" role=\"alert\">$message</div>\n";

                }        

            }

        }

        // Wipe the message store
        if($keep_store === false) {
            $this->systemMessagesBuildStore();
        }

        // Return selected format
        if($format === 'array') {
            return $messages;
        } else {    
            return $html;
        }

    }

    // Get forcePage() Message Store and merge (if passed/present)
    public function systemMessagesReturnForcePageStore() {

        // If a System Message Store has been passed by forcePage(), merge this array in to the System Message Store
        if(isset(\CMSApplication::$VAR['forcePageSystemMessageStore']) && is_array(\CMSApplication::$VAR['forcePageSystemMessageStore'])) {
            $message_store = \CMSApplication::$VAR['forcePageSystemMessageStore'];
            unset(\CMSApplication::$VAR['forcePageSystemMessageStore']);
        } else {        
            $message_store = array();
        }

        return $message_store;

    }

    // This will parse the page payload and add the system messages, (only works on an empty HTML `system_messages` div)
    public function systemMessagesParsePage(&$pagePayload) {    

        if($systemMessageStore = $this->systemMessagesReturnStore()) {
            $search = '<div id="system_messages" style="display: none;"></div>';
            $replace = "<div id=\"system_messages\">\n".$systemMessageStore."</div>\n";
            $count = (int)1;
            $pagePayload = str_replace($search, $replace, $pagePayload, $count);
        }

    }
    
}