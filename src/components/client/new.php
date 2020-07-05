<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

if(isset(\CMSApplication::$VAR['submit'])) {

    // Create the new Client
    \CMSApplication::$VAR['client_id'] = $this->app->components->client->insertRecord(\CMSApplication::$VAR['qform']);
    
    // Load the new Client's Details page
    $this->app->system->page->force_page('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);
    
} else {
    
    // Build the page
    $this->app->smarty->assign('client_types', $this->app->components->client->getTypes());

}