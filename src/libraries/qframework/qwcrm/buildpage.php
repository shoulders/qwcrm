<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

############################
#  Build the page content  #    // All variables should be passed by $VAR because it is its own scope
############################

function get_page_content($page_controller, $startTime, $VAR = null) {    
    
    $config = \QFactory::getConfig();
    $smarty = \QFactory::getSmarty();  // This is required for the required files/templates grabbed here    
    if(!defined('QWCRM_SETUP')) { $user = \QFactory::getUser(); }
    
    // This varible holds the page as it is built
    $BuildPage = '';

    // If theme is set to Print mode then fetch the Page Content - Print system will output with its own format without need for headers and footers here
    if (isset($VAR['theme']) && $VAR['theme'] === 'print') {        
        require($page_controller);
        goto page_build_end;
    }
    
    // Set Page Header and Meta Data
    set_page_header_and_meta_data($VAR['component'], $VAR['page_tpl']);

    // Fetch Header Block
    if(!isset($VAR['theme']) || $VAR['theme'] != 'off') {     
        require(COMPONENTS_DIR.'core/blocks/theme_header_block.php');
    } else {
        //echo '<!DOCTYPE html><head></head><body>';
        require(COMPONENTS_DIR.'core/blocks/theme_header_theme_off_block.php');
    }

    // Fetch Header Legacy Template Code and Menu Block - Clients, Guests and Public users will not see the menu
    if((!isset($VAR['theme']) || $VAR['theme'] != 'off') && isset($user->login_token) && $user->login_usergroup_id != 7 && $user->login_usergroup_id != 8 && $user->login_usergroup_id != 9) {       
        $BuildPage .= $smarty->fetch('core/blocks/theme_header_legacy_supplement_block.tpl');

        // is the menu disabled
        if(!isset($VAR['theme']) || $VAR['theme'] != 'menu_off') {
            require(COMPONENTS_DIR.'core/blocks/theme_menu_block.php'); 
        }

    }    

    // Fetch the Page Content
    require($page_controller);    

    // Fetch Footer Legacy Template code Block (closes content table)
    if((!isset($VAR['theme']) || $VAR['theme'] != 'off') && isset($user->login_token) && $user->login_usergroup_id != 7 && $user->login_usergroup_id != 8 && $user->login_usergroup_id != 9) {
        $BuildPage .= $smarty->fetch('core/blocks/theme_footer_legacy_supplement_block.tpl');             
    }

    // Fetch the Footer Block
    if(!isset($VAR['theme']) || $VAR['theme'] != 'off'){        
        require(COMPONENTS_DIR.'core/blocks/theme_footer_block.php');        
    }    

    // Fetch the Debug Block
    if(!defined('QWCRM_SETUP') && $config->get('qwcrm_debug')){
        require(COMPONENTS_DIR.'core/blocks/theme_debug_block.php');        
        $BuildPage .= "\r\n</body>\r\n</html>";
    } else {
        $BuildPage .= "\r\n</body>\r\n</html>";
    }

    page_build_end:
    
    // Process Page links
    if(!defined('QWCRM_SETUP')) {  
        //page_links_acl_replace($BuildPage);
        page_links_acl_removal($BuildPage);
        page_links_sdmenu_cleanup($BuildPage);        
    }
    
    // Will error out if there are any issues with content replacement
    if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
        echo _gettext("Backtrack limit was exhausted!");
    }

    // Convert to SEF (if enabled and NOT running setup)
    if (!defined('QWCRM_SETUP') && $config->get('sef')) { 
        page_links_to_sef($BuildPage);        
    }    
        
    return $BuildPage;
    
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

function page_links_acl_replace(&$BuildPage) {
    
    $BuildPage = preg_replace_callback('/(["\'])(index\.php.*)(["\'])/U',
        function($matches) {
        
             // Check to see if user is allowed to use the link
            if(check_page_link_permission($matches[2])) { 
                
                // Return un-modified link
                return $matches[0];
                
            } else {
                
                // Return modifed link (or use &nbsp; )
                return $matches[1].'#'.$matches[3];  
            }

        }, $BuildPage);

}

#######################################
#  Remove all unauthorised page links #
#######################################

function page_links_acl_removal(&$BuildPage) {
    
    // This allows for <a>...</a> being split over several lines. The opening <a .....> must be on one line - This is also optimized with atomic groups
    $BuildPage = preg_replace_callback('/<(?>a|button|input|form)[^\r\n]*["\'](index\.php[^\r\n]*)["\'][^\r\n]*>.*<\/(?>a|button|input|form)>/Us',
        function($matches) {
            
            // Check to see if user is allowed to use the link
            if(check_page_link_permission($matches[1])) { 
                
                // Return un-modified link
                return $matches[0];
                
            } else {

                // Return modifed link (i.e. removed)           
                return '';
            }

        }, $BuildPage);
       
}

#########################################
#  Remove unpopulated SD Menu groups    #
#########################################

function page_links_sdmenu_cleanup(&$BuildPage) {
    
    $BuildPage = preg_replace_callback('/<div class="menugroup">.*<\/div>/Us',
        function($matches) {
        
            if(preg_match('/<a.*<\/a>/Us', $matches[0])) {
                
                // leave the group as is because there are menu item(s)
                return $matches[0];
                
            } else {

                // remove the menu group because there are no menu item(s)
                return '';
                
            }

        }, $BuildPage);
    
}

###########################################
#  Change all internal page links to SEF  #
###########################################

function page_links_to_sef(&$BuildPage) {
    
    // Replace nonsef links within "" and ''
    $BuildPage = preg_replace_callback('|(["\'])(index\.php.*)(["\'])|U',
        function($matches) {
            
            return $matches[1].build_sef_url($matches[2]).$matches[3];

        }, $BuildPage);
    
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

function compress_page_output(&$BuildPage)
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
        return;
    }

    // Verify that headers have not yet been sent, and that our connection is still alive.
    if (headers_sent() || (connection_status() !== CONNECTION_NORMAL))
    {
        return;
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
            $gzBuildPage = gzencode($BuildPage, 4, ($supported[$encoding] == 'gz') ? FORCE_GZIP : FORCE_DEFLATE);

            // If there was a problem encoding the data just try the next encoding scheme.            
            if ($gzBuildPage === false)
            {
                continue;
            }            

            // Set the encoding headers.
            header("Content-Encoding: $encoding");

            // Replace the output with the encoded data.            
            $BuildPage = $gzBuildPage;
            return;
            
        }
    }
}

####################################################################
#  Get the supported compression algorithms in the client browser  #
####################################################################

function browserSupportedCompressionEncodings() {
        
    return array_map('trim', (array) explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']));

}