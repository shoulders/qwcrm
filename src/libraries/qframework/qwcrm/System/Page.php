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

            // Get and set the page controller
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

        // Local store for page content
        $pagePayload = '';             
        
        if(!defined('QWCRM_SETUP')) { $user = $this->app->user; }

        // Set the correct theme specification, either manually supplied or from the system
        $component = isset($component) ? $component : ( isset(\CMSApplication::$VAR['component']) ? \CMSApplication::$VAR['component'] : null);
        $page_tpl = isset($page_tpl) ? $page_tpl : ( isset(\CMSApplication::$VAR['page_tpl']) ? \CMSApplication::$VAR['page_tpl'] : null);
        $themeVar = isset($themeVar) ? $themeVar : ( isset(\CMSApplication::$VAR['theme']) ? \CMSApplication::$VAR['theme'] : null);          

        // This is currently not used, and is only so i know where the pagec controller section is
        page_controller:

        // Fetch the specified Page Controller
        require($page_controller);

        // If an alternative page has been loaded by the page controller, return this content
        if($pagePayload) {        
            return $pagePayload;        
        }

        // If theme is set to Print mode, Skip Header, Footer or raw_html - System will output without headers, footers and debug
        if (isset($themeVar) && ($themeVar === 'print' || $themeVar === 'raw_html')) {        

            // This allows autosuggest to work
            if ($themeVar !== 'raw_html') {
                $pagePayload .= $this->app->smarty->fetch($component.'/'.$page_tpl.'.tpl');
            }

            goto page_build_end;
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

        // Fetch Header Legacy Template Code and Menu Block - Clients, Guests and Public users will not see the menu
        if((!isset($themeVar) || $themeVar != 'off') && isset($user->login_token) && $user->login_usergroup_id != 7 && $user->login_usergroup_id != 8 && $user->login_usergroup_id != 9) {       
            $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_header_legacy_supplement_block.tpl');

            // is the menu disabled
            if(!isset($themeVar) || $themeVar != 'menu_off') {
                require(COMPONENTS_DIR.'core/blocks/theme_menu_block.php');
                $pagePayload .= $this->app->smarty->fetch('core/blocks/theme_menu_block.tpl');
            }

        }  

        // Fetch the specified Page Tempalte
        $pagePayload .= $this->app->smarty->fetch($component.'/'.$page_tpl.'.tpl');

        // Fetch Footer Legacy Template code Block (closes content table)
        if((!isset($themeVar) || $themeVar != 'off') && isset($user->login_token) && $user->login_usergroup_id != 7 && $user->login_usergroup_id != 8 && $user->login_usergroup_id != 9) {
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

        page_build_end:

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

        return array_map('trim', (array) explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']));

    }
    
}