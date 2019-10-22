<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

############################
#  Load the page           #
############################

function load_page($mode = null, $component = null, $page_tpl = null, $themeVar = null) {
    
    // get_page_controller($component = null, $page_tpl = null, $mode = null, $themeVar = null)
    // get_page_content($page_controller, $component = null, $page_tpl = null, $mode = null, $themeVar = null)
        
    // Just return the page as a variable and dont change the system page (not currently using this feature but might for AJAX)    
    if($mode == 'payload') { 
        
        $pageController = get_page_controller($mode, $component, $page_tpl, $themeVar);
        return get_page_content($pageController, $mode, $component, $page_tpl, $themeVar);
    }
    
    // Normal Behaviour, set the routing, get the page, load the page into the system
    if($mode != 'payload') { 
        
        // Get and set the page controller
        \QFactory::$VAR['page_controller'] = get_page_controller();
        
        // Build the page
        \QFactory::$BuildPage = get_page_content(\QFactory::$VAR['page_controller']);
        
        return;
        
    }   
    
}

############################
#  Build the page content  #    // All variables should be passed by \QFactory::$VAR because it is its own scope
############################

function get_page_content($page_controller, $mode = null, $component = null, $page_tpl = null, $themeVar = null) {    
    
    $config = \QFactory::getConfig();
    $smarty = \QFactory::getSmarty();  // This is required for the required files/templates grabbed here 
    $pagePayload = '';                   // Local store for page content
    if(!defined('QWCRM_SETUP')) { $user = \QFactory::getUser(); }
        
    // Set the correct theme spcification, either manually supplied or from the system
    $component = isset($component) ? $component : ( isset(\QFactory::$VAR['component']) ? \QFactory::$VAR['component'] : null);
    $page_tpl = isset($page_tpl) ? $page_tpl : ( isset(\QFactory::$VAR['page_tpl']) ? \QFactory::$VAR['page_tpl'] : null);
    $themeVar = isset($themeVar) ? $themeVar : ( isset(\QFactory::$VAR['theme']) ? \QFactory::$VAR['theme'] : null);
        
    // If theme is set to Print mode, Skip Header and Footer - Print system will output with it's own format without need for headers and footers here
    if (isset($themeVar) && ($themeVar === 'print' || $themeVar === 'raw_html')) {        
        require_once($page_controller);
        
        // This allows autosuggest to work
        if ($themeVar !== 'raw_html') {
            $pagePayload .= $smarty->fetch($component.'/'.$page_tpl.'.tpl');
        }
        
        goto page_build_end;
    }
    
    // Set Page Header and Meta Data
    set_page_header_and_meta_data($component, $page_tpl);
    
    // Fetch Header Block
    if(!isset($themeVar) || $themeVar != 'off') {     
        require(COMPONENTS_DIR.'core/blocks/theme_header_block.php');
        $pagePayload .= $smarty->fetch('core/blocks/theme_header_block.tpl');
    } else {
        //echo '<!DOCTYPE html><head></head><body>';
        require(COMPONENTS_DIR.'core/blocks/theme_header_theme_off_block.php');
        $pagePayload .= $smarty->fetch('core/blocks/theme_header_theme_off_block.tpl');
    }

    // Fetch Header Legacy Template Code and Menu Block - Clients, Guests and Public users will not see the menu
    if((!isset($themeVar) || $themeVar != 'off') && isset($user->login_token) && $user->login_usergroup_id != 7 && $user->login_usergroup_id != 8 && $user->login_usergroup_id != 9) {       
        $pagePayload .= $smarty->fetch('core/blocks/theme_header_legacy_supplement_block.tpl');

        // is the menu disabled
        if(!isset($themeVar) || $themeVar != 'menu_off') {
            require(COMPONENTS_DIR.'core/blocks/theme_menu_block.php');
            $pagePayload .= $smarty->fetch('core/blocks/theme_menu_block.tpl');
        }

    }    
    
    // Fetch the specified Page Controller
    require_once($page_controller);
    $pagePayload .= $smarty->fetch($component.'/'.$page_tpl.'.tpl');
        
    // Fetch Footer Legacy Template code Block (closes content table)
    if((!isset($themeVar) || $themeVar != 'off') && isset($user->login_token) && $user->login_usergroup_id != 7 && $user->login_usergroup_id != 8 && $user->login_usergroup_id != 9) {
        $pagePayload .= $smarty->fetch('core/blocks/theme_footer_legacy_supplement_block.tpl');             
    }

    // Fetch the Footer Block
    if(!isset($themeVar) || $themeVar != 'off'){        
        require(COMPONENTS_DIR.'core/blocks/theme_footer_block.php'); 
        $pagePayload .= $smarty->fetch('core/blocks/theme_footer_block.tpl');
    }    

    // Fetch the Debug Block
    if(!defined('QWCRM_SETUP') && $config->get('qwcrm_debug')){
        require(COMPONENTS_DIR.'core/blocks/theme_debug_block.php');
        $pagePayload .= $smarty->fetch('core/blocks/theme_debug_smarty_debug_block.tpl'); /////////////////// This TPL needs sorting  
        $pagePayload .= "\r\n</body>\r\n</html>";
    } else {
        $pagePayload .= "\r\n</body>\r\n</html>";
    }

    page_build_end:
    
    // Process Page links
    if(!defined('QWCRM_SETUP')) {  
        //$pagePayload .= page_links_acl_replace($pagePayload);
        $pagePayload .= page_links_acl_removal($pagePayload);
        $pagePayload .= page_links_sdmenu_cleanup($pagePayload);        
    }
    
    // Will error out if there are any issues with content replacement
    if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
        echo _gettext("Backtrack limit was exhausted!");
    }

    // Convert to SEF (if enabled and NOT running setup)
    if (!defined('QWCRM_SETUP') && $config->get('sef')) { 
        $pagePayload .= page_links_to_sef($pagePayload);        
    }    
        
    return $pagePayload;
    
}

############################################################
#  This checks the links in templates for permissions      #
############################################################

function check_page_link_permission($url) {
    
    // index.php is a special case
    if($url === 'index.php') { return true; }
    
    // Get routing variables from URL
    $url_routing = get_routing_variables_from_url($url);
    
    // Check to see if user is allowed to use the asset
    if(isset($url_routing['component'], $url_routing['page_tpl']) && check_page_acl($url_routing['component'], $url_routing['page_tpl'])) {
        
        return true;
        
    } else {
    
        return false;
    }
}

#######################################################
#  Replace all unauthorised page links with href="#"  #
#######################################################

function page_links_acl_replace($pagePayload) {
    
    $pagePayload = preg_replace_callback('/(["\'])(index\.php.*)(["\'])/U',
        function($matches) {
        
             // Check to see if user is allowed to use the link
            if(check_page_link_permission($matches[2])) { 
                
                // Return un-modified link
                return $matches[0];
                
            } else {
                
                // Return modifed link (or use &nbsp; )
                return $matches[1].'#'.$matches[3];  
            }

        }, $pagePayload);
        
    return $pagePayload;

}

#######################################
#  Remove all unauthorised page links #
#######################################

function page_links_acl_removal($pagePayload) {
    
    // This allows for <a>...</a> being split over several lines. The opening <a .....> must be on one line - This is also optimized with atomic groups
    $pagePayload = preg_replace_callback('/<(?>a|button|input|form)[^\r\n]*["\'](index\.php[^\r\n]*)["\'][^\r\n]*>.*<\/(?>a|button|input|form)>/Us',
        function($matches) {
            
            // Check to see if user is allowed to use the link
            if(check_page_link_permission($matches[1])) { 
                
                // Return un-modified link
                return $matches[0];
                
            } else {

                // Return modifed link (i.e. removed)           
                return '';
            }

        }, $pagePayload);
        
    return $pagePayload;
       
}

#########################################
#  Remove unpopulated SD Menu groups    #
#########################################

function page_links_sdmenu_cleanup($pagePayload) {
    
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
        
    return $pagePayload;
    
}

###########################################
#  Change all internal page links to SEF  #
###########################################

function page_links_to_sef($pagePayload) {
    
    // Replace nonsef links within "" and ''
    $pagePayload = preg_replace_callback('|(["\'])(index\.php.*)(["\'])|U',
        function($matches) {
            
            return $matches[1].build_sef_url($matches[2]).$matches[3];

        }, $pagePayload);
        
    return $pagePayload;
    
}

############################################
#      Set Page Header and Meta Data       #
############################################

function set_page_header_and_meta_data($component, $page_tpl) {
    
    $smarty = \QFactory::getSmarty();
    
    // Page Title
    $smarty->assign('page_title', _gettext(strtoupper($component).'_'.strtoupper($page_tpl).'_PAGE_TITLE'));    
    
    // Meta Tags
    $smarty->assign('meta_description', _gettext(strtoupper($component).'_'.strtoupper($page_tpl).'_META_DESCRIPTION')  );
    $smarty->assign('meta_keywords',    _gettext(strtoupper($component).'_'.strtoupper($page_tpl).'_META_KEYWORDS')     );
    
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

function compress_page_output($pagePayload)
{
    // Supported compression encodings.
    $supported = array(
        'x-gzip'    => 'gz',
        'gzip'      => 'gz',
        'deflate'   => 'deflate'
    );

    // Get the supported encoding.
    $encodings = array_intersect(browserSupportedCompressionEncodings(), array_keys($supported));

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

function browserSupportedCompressionEncodings() {
        
    return array_map('trim', (array) explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']));

}