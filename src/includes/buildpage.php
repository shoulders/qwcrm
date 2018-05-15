<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

###############################################
#    Build and Display the page (as required) #
#    if the user has the correct permissions  #
###############################################

// This varible holds the page as it is built
$BuildPage = '';

// If theme is set to Print mode then fetch the Page Content - Print system will output with its own format without need for headers and footers here
if (isset($VAR['theme']) && $VAR['theme'] === 'print') {        
    require($page_display_controller);
    goto page_build_end;
}

// Set Page Header and Meta Data
set_page_header_and_meta_data($component, $page_tpl);

// Fetch Header Block
if(!isset($VAR['theme']) || $VAR['theme'] != 'off') {     
    require(COMPONENTS_DIR.'core/blocks/theme_header_block.php');
} else {
    //echo '<!DOCTYPE html><head></head><body>';
    require(COMPONENTS_DIR.'core/blocks/theme_header_theme_off_block.php');
}

// Fetch Header Legacy Template Code and Menu Block - Customers, Guests and Public users will not see the menu
if((!isset($VAR['theme']) || $VAR['theme'] != 'off') && isset($login_token) && $login_usergroup_id != 7 && $login_usergroup_id != 8 && $login_usergroup_id != 9) {       
    $BuildPage .= $smarty->fetch('core/blocks/theme_header_legacy_supplement_block.tpl');

    // is the menu disabled
    if(!isset($VAR['theme']) || $VAR['theme'] != 'menu_off') {
        require(COMPONENTS_DIR.'core/blocks/theme_menu_block.php'); 
    }

}    

// Fetch the Page Content
require($page_display_controller);    

// Fetch Footer Legacy Template code Block (closes content table)
if((!isset($VAR['theme']) || $VAR['theme'] != 'off') && isset($login_token) && $login_usergroup_id != 7 && $login_usergroup_id != 8 && $login_usergroup_id != 9) {
    $BuildPage .= $smarty->fetch('core/blocks/theme_footer_legacy_supplement_block.tpl');             
}

// Fetch the Footer Block
if(!isset($VAR['theme']) || $VAR['theme'] != 'off'){        
    require(COMPONENTS_DIR.'core/blocks/theme_footer_block.php');        
}    

// Fetch the Debug Block
if($QConfig->qwcrm_debug == true){
    require(COMPONENTS_DIR.'core/blocks/theme_debug_block.php');        
    $BuildPage .= "\r\n</body>\r\n</html>";
} else {
    $BuildPage .= "\r\n</body>\r\n</html>";
}

page_build_end: