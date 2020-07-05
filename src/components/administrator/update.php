<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Check for updates
    $this->app->components->administrator->checkQwcrmUpdateAvailability();

} else {
    // Prevent undefined variable errors
    $this->app->smarty->assign('update_response', null);   
}

// Build the page

$this->app->smarty->assign('current_version', QWCRM_VERSION);

