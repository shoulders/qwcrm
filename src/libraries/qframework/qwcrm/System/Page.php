<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Page extends System {

    ############################
    #  Load the page           #  // $mode = 'get_payload': return a variable pouplated with a rendered page, $mode = 'set_controller': load page normally
    ############################

    public function load_page($mode, $component = null, $page_tpl = null, $themeVar = null) {

        // Get the page as a variable, dont set routing
        if($mode == 'get_payload') { 

            // Get the page controller
            $pageController = $this->app->system->router->page_controller($mode, $component, $page_tpl, $themeVar);

            // Return the page as a variable
            return $this->get_page_content($pageController, $mode, $component, $page_tpl, $themeVar);
        }

        // Normal Behaviour, set the routing, get the page, load the page into the browser
        if($mode == 'set_controller') { 

            // Get and set the page controller
            \CMSApplication::$VAR['page_controller'] = $this->app->system->router->page_controller();

            // Build the page
            \CMSApplication::$BuildPage = $this->get_page_content(\CMSApplication::$VAR['page_controller'], $mode);

            return;

        }

    }

    ############################
    #  Build the page content  #    // All variables should be passed by \CMSApplication::$VAR because it is its own scope
    ############################

    public function get_page_content($page_controller, $mode = null, $component = null, $page_tpl = null, $themeVar = null) {    
        
        $pagePayload = '';      // Local store for page content
        $rawHtml = false;       // Is the payload Raw HTML? This can be altered by the specified page controller (included file), if required (i.e. autosuggest)
        
        // Set the correct theme specification, either manually supplied or from the system
        $component = isset($component) ? $component : ( isset(\CMSApplication::$VAR['component']) ? \CMSApplication::$VAR['component'] : null);
        $page_tpl = isset($page_tpl) ? $page_tpl : ( isset(\CMSApplication::$VAR['page_tpl']) ? \CMSApplication::$VAR['page_tpl'] : null);
        $themeVar = isset($themeVar) ? $themeVar : ( isset(\CMSApplication::$VAR['themeVar']) ? \CMSApplication::$VAR['themeVar'] : null);                       

        // This is currently not used, and is only so i know where the page controller section is
        page_controller:

        // Fetch the specified Page Controller
        require($page_controller);         
        
        // If themeVar is set to Print mode or Raw HTML is enables, Skip adding Header, Footer and Debug sections to the page        
        if ((isset($themeVar) && $themeVar === 'print') || $rawHtml) {        

            // If Raw HTMl dont load a non-existent template
            if (!$rawHtml) {
                $pagePayload .= $this->app->smarty->fetch($component.'/'.$page_tpl.'.tpl');
            }

            goto page_parse_payload;
        }
        
        // This is currently not used, and is only so i know where the payload build start is
        page_build:

        // Set Page Header and Meta Data
        $this->set_page_header_and_meta_data($component, $page_tpl);

        // Fetch Header Block
        if(!isset($themeVar) || $themeVar != 'off') {     
            require(COMPONENTS_DIR.'core/blocks/theme_header_block.php');
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_header_block.tpl');
        } else {
            //echo '<!DOCTYPE html><head></head><body>';
            require(COMPONENTS_DIR.'core/blocks/theme_header_theme_off_block.php');
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_header_theme_off_block.tpl');
        }

        // Fetch Header Legacy Template Code and Menu Block - Must be logged in, Clients, Guests and Public users will not see the menu
        if((!isset($themeVar) || $themeVar != 'off') && $this->app->user->login_token && $this->app->user->login_usergroup_id <= 6) {       
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_header_legacy_supplement_block.tpl');

            // is the menu disabled
            if(!isset($themeVar) || $themeVar != 'menu_off') {
                require(COMPONENTS_DIR.'core/blocks/theme_menu_block.php');
                $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_menu_block.tpl');
            }

        }  

        // Fetch the specified Page Template
        $pagePayload .= $this->app->smarty->fetch($component.'/'.$page_tpl.'.tpl');

        // Fetch Footer Legacy Template code Block (closes content table)
        if((!isset($themeVar) || $themeVar != 'off') && $this->app->user->login_token && $this->app->user->login_usergroup_id <= 6) {
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_footer_legacy_supplement_block.tpl');             
        }

        // Fetch the Footer Block
        if(!isset($themeVar) || $themeVar != 'off'){        
            require(COMPONENTS_DIR.'core/blocks/theme_footer_block.php'); 
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_footer_block.tpl');
        }    

        // Fetch the Debug Block
        if(!defined('QWCRM_SETUP') && $this->app->config->get('qwcrm_debug')){
            require(COMPONENTS_DIR.'core/blocks/theme_debug_block.php');
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_debug_smarty_debug_block.tpl'); /////////////////// This TPL needs sorting  
            $pagePayload .= "\r\n</body>\r\n</html>";
        } else {
            $pagePayload .= "\r\n</body>\r\n</html>";
        }

        page_parse_payload:

        // Modules code goes here
        // ......................

        // Plugins code goes here
        // ......................

        // Process Page links
        if(!defined('QWCRM_SETUP')) {  
            //page_links_acl_replace($pagePayload);
            $this->page_links_acl_removal($pagePayload);
            $this->page_links_sdmenu_cleanup($pagePayload);        
        }

        // Add system messages
        $this->app->system->variables->systemMessagesParsePage($pagePayload);

        // Will error out if there are any issues with content replacement
        if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
            echo _gettext("Backtrack limit was exhausted!");
        }

        // Convert to SEF (if enabled and NOT running setup)
        if (!defined('QWCRM_SETUP') && $this->app->config->get('sef')) { 
            $this->page_links_to_sef($pagePayload);        
        }    

        return $pagePayload;

    }

    ############################################################
    #  This checks the links in templates for permissions      #
    ############################################################

    public function check_page_link_permission($url) {

        // index.php is a special case
        if($url === 'index.php') { return true; }

        // Get routing variables from URL
        $url_routing = $this->app->system->router->get_routing_variables_from_url($url);

        // Check to see if user is allowed to use the asset
        if(isset($url_routing['component'], $url_routing['page_tpl']) && $this->app->system->router->check_page_acl($url_routing['component'], $url_routing['page_tpl'])) {

            return true;

        } else {

            return false;
        }
    }

    #######################################################
    #  Replace all unauthorised page links with href="#"  #
    #######################################################

    public function page_links_acl_replace(&$pagePayload) {

        $pagePayload = preg_replace_callback('/(["\'])(index\.php.*)(["\'])/U',
            function($matches) {

                 // Check to see if user is allowed to use the link
                if($this->check_page_link_permission($matches[2])) { 

                    // Return un-modified link
                    return $matches[0];

                } else {

                    // Return modifed link (or use &nbsp; )
                    return $matches[1].'#'.$matches[3];  
                }

            }, $pagePayload);

    }

    #######################################
    #  Remove all unauthorised page links #
    #######################################

    public function page_links_acl_removal(&$pagePayload) {

        // This allows for <a>...</a> being split over several lines. The opening <a .....> must be on one line - This is also optimized with atomic groups
        $pagePayload = preg_replace_callback('/<(?>a|button|input|form)[^\r\n]*["\'](index\.php[^\r\n]*)["\'][^\r\n]*>.*<\/(?>a|button|input|form)>/Us',
            function($matches) {

                // Check to see if user is allowed to use the link
                if($this->check_page_link_permission($matches[1])) { 

                    // Return un-modified link
                    return $matches[0];

                } else {

                    // Return modifed link (i.e. removed)           
                    return '';
                }

            }, $pagePayload);

    }

    #########################################
    #  Remove unpopulated SD Menu groups    #
    #########################################

    public function page_links_sdmenu_cleanup(&$pagePayload) {

        $pagePayload = preg_replace_callback('/<div class="menugroup">.*<\/div>/Us',
            function($matches) {

                if(preg_match('/<a.*<\/a>/Us', $matches[0])) {

                    // leave the group as is because there are menu item(s)
                    return $matches[0];

                } else {

                    // remove the menu group because there are no menu item(s)
                    return '';

                }

            }, $pagePayload);

    }

    ###########################################
    #  Change all internal page links to SEF  #
    ###########################################

    public function page_links_to_sef(&$pagePayload) {

        // Replace nonsef links within "" and ''
        $pagePayload = preg_replace_callback('|(["\'])(index\.php.*)(["\'])|U',
            function($matches) {

                return $matches[1].$this->app->system->router->build_sef_url($matches[2]).$matches[3];

            }, $pagePayload);

    }

    ############################################
    #      Set Page Header and Meta Data       #
    ############################################

    public function set_page_header_and_meta_data($component, $page_tpl) {

        // Page Title
        $this->app->smarty->assign('page_title', _gettext(strtoupper($component).'_'.strtoupper($page_tpl).'_PAGE_TITLE'));    

        // Meta Tags
        $this->app->smarty->assign('meta_description', _gettext(strtoupper($component).'_'.strtoupper($page_tpl).'_META_DESCRIPTION')  );
        $this->app->smarty->assign('meta_keywords',    _gettext(strtoupper($component).'_'.strtoupper($page_tpl).'_META_KEYWORDS')     );

        return;

    }

    ###########################################
    #  Compress page output and send headers  #
    ###########################################

    /**
     * Checks the accept encoding of the browser and compresses the data before
     * sending it to the client if possible.
     *
     * @return  void
     *
     * @since   11.3
     *
     * From {Joomla}libraries/joomla/application/web.php
     */

    /**
     * @package     Joomla.Platform
     * @subpackage  Application
     *
     * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
     * @copyright   Copyright (C) 2017 - Jon Brown / Quantumwarp.com
     * @license     GNU General Public License version 2 or later; see LICENSE
     */

    public function compress_page_output($pagePayload)
    {
        // Supported compression encodings.
        $supported = array(
            'x-gzip'    => 'gz',
            'gzip'      => 'gz',
            'deflate'   => 'deflate'
        );

        // Get the supported encoding.
        $encodings = array_intersect($this->browserSupportedCompressionEncodings(), array_keys($supported));

        // If no supported encoding is detected do nothing and return.
        if (empty($encodings))
        {
            return $pagePayload;
        }

        // Verify that headers have not yet been sent, and that our connection is still alive.
        if (headers_sent() || (connection_status() !== CONNECTION_NORMAL))
        {
            return $pagePayload;
        }

        // Iterate through the encodings and attempt to compress the data using any found supported encodings.
        foreach ($encodings as $encoding)
        {
            if (($supported[$encoding] == 'gz') || ($supported[$encoding] == 'deflate'))
            {
                // Verify that the server supports gzip compression before we attempt to gzip encode the data.            
                if (!extension_loaded('zlib') || ini_get('zlib.output_compression'))
                {
                    continue;
                }           

                // Attempt to gzip encode the page with an optimal level 4.            
                $gzPagePayload = gzencode($pagePayload, 4, ($supported[$encoding] == 'gz') ? FORCE_GZIP : FORCE_DEFLATE);

                // If there was a problem encoding the data just try the next encoding scheme.            
                if ($gzPagePayload === false)
                {
                    continue;
                }            

                // Set the encoding headers.
                header("Content-Encoding: $encoding");

                // Return the compressed payload           
                return $gzPagePayload;            

            }
        }

        // Default action if nothin has happened
        return $pagePayload;

    }

    ####################################################################
    #  Get the supported compression algorithms in the client browser  #
    ####################################################################

    public function browserSupportedCompressionEncodings() {

        // the isset() is needed for mPDF (might not be needed)
        if(isset($_SERVER['HTTP_ACCEPT_ENCODING']))
        {
            return array_map('trim', (array) explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']));
        } else {
            return array();   
        }

    }
 
    
    #####################################
    #   force_page - Page Redirector    #  // Can send variables as a GET string or POST variables
    #####################################

    /*
     * If no $page_tpl and $variables are supplied then this function 
     * will force a URL redirect exactly how it was supplied 
     */

    function force_page($component, $page_tpl = null, $variables = null, $method = 'auto', $url_sef = 'auto', $url_protocol = 'auto') {

        // Preserve the Message Store (if there are any messages) for the next page load
        if($forcePageSystemMessageStore = $this->app->system->variables->systemMessagesReturnStore(false, 'array')) {
            $this->app->system->variables->postEmulationWrite('forcePageSystemMessageStore', $forcePageSystemMessageStore);
        }
        
        // Wipe the system variables (workaround because I need to hive of the Query variables)
        if(isset($variables['system']))
        {
            unset($variables['system']);
        }

        /* Process Options */

        // Set method to be used
        if($method == null || $method == 'auto') { $method = 'post'; }    

        // Set URL SEF type to be used
        if ($url_sef == 'sef') { $makeSEF = true; }
        elseif ($url_sef == 'nonsef') { $makeSEF = false; }
        elseif(class_exists('\CMSApplication')) { $makeSEF = $this->app->config->get('sef'); }
        else { $makeSEF = false; }

        // Configure and set URL protocol and domain segment (allows for https to http, http to https using QWcrm style force_page() links)
        if ($url_protocol == 'https') { $protocol_domain_segment = 'https://'.QWCRM_DOMAIN; }
        elseif ($url_protocol == 'http') { $protocol_domain_segment = 'http://'.QWCRM_DOMAIN; }
        //else { $protocol_domain_segment = null; }                         // This makes relative links
        else { $protocol_domain_segment = QWCRM_PROTOCOL.QWCRM_DOMAIN; }    // This makes absolute links using define settings

        /* Standard URL Redirect */

        if($component != 'index.php' && $page_tpl == null) {       

            // Build the URL and perform the redirect
            $this->perform_redirect($protocol_domain_segment.$component);        

        }

        /* GET - Send Variables via $_GET / Return URL*/

        if($method == 'get' || $method == 'url') {

            // If variables exist 
            if($variables) {

                // If variables are in an array convert into an encoded string
                if($variables && is_array($variables)) {

                    // Remove routing variables here to prevent 'Double Bubble' (might not be needed)
                    unset($variables['component']);
                    unset($variables['page_tpl']); 

                    $variables = http_build_query($variables);            
                }

            }

            // If home, dashboard or maintenance do not show module:page
            if($component == 'index.php') { 

                // If there are variables, prepare them as a query string
                if($variables) { $variables = '?'.$variables; }

                // Build URL with/without variables
                $url = QWCRM_BASE_PATH.'index.php'.$variables;

                // Convert to SEF if enabled            
                if ($makeSEF) { $url = $this->app->system->router->build_sef_url($url); }

                // Perform redirect
                if($method == 'get') {
                    $this->perform_redirect($protocol_domain_segment.$url);
                } else {
                    return $url;
                }

            // Page Name and Variables (QWcrm Style Redirect)  
            } else {

                // If there are variables, prepare them as additional GET variables
                if($variables) { $variables = '&'.$variables; }

                // Build URL with/without variables
                $url = QWCRM_BASE_PATH.'index.php?component='.$component.'&page_tpl='.$page_tpl.$variables;

                // Convert to SEF if enabled            
                if ($makeSEF) { $url = $this->app->system->router->build_sef_url($url); }

                // Perform redirect
                if($method == 'get') {
                    $this->perform_redirect($protocol_domain_segment.$url);            
                } else {
                    return $url;
                }
            }

        }

        /* POST - Send Varibles via POST Emulation (was $_SESSION but now using Joomla session store)*/    

        if($method == 'post') {

            // If there are variables, prepare them
            if($variables) {

                // If variables are in an encoded string convert to an array
                if(is_string($variables)) {
                    parse_str($variables, $variable_array);
                } else {
                    $variable_array = $variables;
                }

                // Set the page varible in the session - it does not matter page varible is set twice 1 in $_SESSION and 1 in $_GET the array merge will fix that
                foreach($variable_array as $key => $value) {                    
                    $this->app->system->variables->postEmulationWrite($key, $value);
                }               

            }

            // If home, dashboard or maintenance do not show module:page
            if($component == 'index.php') { 

                // Build URL
                $url = QWCRM_BASE_PATH.'index.php';

                // Convert to SEF if enabled            
                if ($makeSEF) { $url = $this->app->system->router->build_sef_url($url); }

                // Perform redirect
                $this->perform_redirect($protocol_domain_segment.$url);

            // Page Name and Variables (QWcrm Style Redirect)     
            } else {

                // Build URL
                $url = QWCRM_BASE_PATH.'index.php?component='.$component.'&page_tpl='.$page_tpl;

                // Convert to SEF if enabled            
                if ($makeSEF) { $url = $this->app->system->router->build_sef_url($url);}

                // Perform redirect
                $this->perform_redirect($protocol_domain_segment.$url);

            }

        }

    }    
    
    ############################################
    #     Perform a Browser Redirect           #
    ############################################

    function perform_redirect($url, $type = 'header') {

        // Redirect using Headers (cant always use this method in QWcrm)
        if($type == 'header') {

            // From http://php.net/manual/en/function.headers-sent.php
            // Note that $filename and $linenum are passed in for later use.
            // Do not assign them values beforehand.
            if (!headers_sent($filename, $linenum)) {

                header('Location: ' . $url);
                exit;

            // If headers already sent, log and output this error
            } else {

                // Build the error message
                $error_msg = '<p>'._gettext("Headers already sent in").' '.$filename.' '._gettext("on line").' '.$linenum.'.</p>';

                // Get routing variables
                $routing_variables = $this->app->system->router->get_routing_variables_from_url($_SERVER['REQUEST_URI']);

                // Log errors to log if enabled
                if($this->app->config->get('qwcrm_error_log')) {    
                    $this->app->system->general->write_record_to_error_log($routing_variables['component'].':'.$routing_variables['page_tpl'], 'redirect', '', debug_backtrace()[1]['function'], '', $error_msg, '');    
                }

                // Output the message and stop processing
                die($error_msg);            

            }

        }

        // Redirect using Javascript
        if($type == 'javascript') {         
            echo('
                    <script>
                        window.location = "'.$url.'"
                    </script>
                ');
            exit;
        }

    }    
    
    
    ############################################
    #           force_error_page               #
    ############################################

    function force_error_page($error_type, $error_location, $error_php_function, $error_database, $error_sql_query, $error_msg) { 

        // Get routing variables
        $routing_variables = $this->app->system->router->get_routing_variables_from_url($_SERVER['REQUEST_URI']);

        // Prepare Variables
        \CMSApplication::$VAR['error_component']     = $this->app->system->general->prepare_error_data('error_component', $routing_variables['component']);
        \CMSApplication::$VAR['error_page_tpl']      = $this->app->system->general->prepare_error_data('error_page_tpl', $routing_variables['page_tpl']);
        \CMSApplication::$VAR['error_type']          = $error_type;
        \CMSApplication::$VAR['error_location']      = $this->app->system->general->prepare_error_data('error_location', $error_location);
        \CMSApplication::$VAR['error_php_function']  = $this->app->system->general->prepare_error_data('error_php_function', $error_php_function);
        \CMSApplication::$VAR['error_database']      = $error_database ;
        \CMSApplication::$VAR['error_sql_query']     = $this->app->system->general->prepare_error_data('error_sql_query', $error_sql_query);
        \CMSApplication::$VAR['error_msg']           = $error_msg;

        \CMSApplication::$VAR['error_enable_override'] = 'override'; // This is required to prevent page looping when an error occurs early on (i.e. in a root page)

        // raw_output mode is very basic, error logging still works, bootloops are prevented, page building and compression are skipped
        if($this->app->config->get('error_page_raw_output')) {

            // Error page main content and processing logic
            require(COMPONENTS_DIR.'core/error.php');

            // Output the error page
            die($pagePayload);
            
        // This will show errors within the template as normal - but occassionaly can cause boot loops during development
        } else {  

            // Load Error Page (normally) and output
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("An error has occured while accessing the database."));
            die($this->app->system->page->load_page('get_payload', 'core', 'error'));

        }

    }    
        
}