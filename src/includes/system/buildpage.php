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
    
    $config = QFactory::getConfig();
    $smarty = QFactory::getSmarty();  // This is required for the required files/templates grabbed here
    $user = QFactory::getUser();
    
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

    // Fetch Header Legacy Template Code and Menu Block - Customers, Guests and Public users will not see the menu
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
    if($config->get('qwcrm_debug')){
        require(COMPONENTS_DIR.'core/blocks/theme_debug_block.php');        
        $BuildPage .= "\r\n</body>\r\n</html>";
    } else {
        $BuildPage .= "\r\n</body>\r\n</html>";
    }

    page_build_end:
    
    // Process Page links
    //page_links_acl_modify($BuildPage);
    page_links_acl_removal($BuildPage);
    page_links_sdmenu_cleanup($BuildPage);        
        
    // Will error out if there are any issues with content replacement
    if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
        echo __gettext("Backtrack limit was exhausted!");
    }

    // Convert to SEF
    if ($config->get('sef')) { page_links_to_sef($BuildPage); }    
        
    return $BuildPage;
    
}

###################################################################
#  Check all page links for permission for the user to view them  #
###################################################################

// Swap out the link in href="" for a hash
function page_links_acl_modify(&$BuildPage) {
    
    $BuildPage = preg_replace_callback('/(["\'])(index\.php.*)(["\'])/U',
        function($matches) {
        
            // Check to see if user is allowed to use the link
            if(link_permission($matches[2])) { 
                
                // Return un-modified link
                return $matches[0];
                
            } else {
                
                // Return modifed link (or use &nbsp; )
                return $matches[1].'#'.$matches[3];  
            }

        }, $BuildPage);

}

// Remove links altogether
function page_links_acl_removal(&$BuildPage) {
    
    // This allows for <a>...</a> being split over several lines. The opening <a .....> must be on one line - This is also optimized with atomic groups
    $BuildPage = preg_replace_callback('/<(?>a|button|input)[^\r\n]*["\'](index\.php[^\r\n]*)["\'][^\r\n]*>.*<\/(?>a|button|input)>/Us',
        function($matches) {
            
            // Check to see if user is allowed to use the link
            if(link_permission($matches[1])) { 
                
                // Return un-modified link
                return $matches[0];
                
            } else {

                // Return modifed link (i.e. removed)           
                return '';
            }

        }, $BuildPage);
       
}

// Remove unpopulated main menu groups
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

############################################################
#  Does the current user have permission to see this link  #
############################################################

function link_permission($url) {
    
    // Get routing variables from URL
    $url_routing = get_routing_variables_from_url($url);
    
    // Check to see if user is allowed to use the asset
    if(check_page_acl($url_routing['component'], $url_routing['page_tpl'])) {
        
        return true;
        
    } else {
    
        return false;
    }
}

###########################################
#  Change all internal page links to SEF  #
###########################################

function page_links_to_sef(&$BuildPage) {
    
    // Replace nonsef links within "" and ''
    $BuildPage = preg_replace_callback('|(["\'])(index\.php.*)(["\'])|U',
        function($matches) {
            
            return $matches[1].buildSEF($matches[2]).$matches[3];

        }, $BuildPage);
    
}