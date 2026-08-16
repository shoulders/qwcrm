<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;


// Check if the record can be created
if(!$this->app->components->user->checkRecordCanBeCreated(\CMSApplication::$VAR['client_id'] ?? null)) {
    $this->app->system->page->forcePage('user', 'search');
} else {

    // Create the user record and return the new user_id
    \CMSApplication::$VAR['user_id'] = $this->app->components->user->insertRecord(\CMSApplication::$VAR['client_id'] ?? null);

    // Advise on what to do next
    $this->app->system->variables->systemMessagesWrite('success', _gettext("A new user has been created, you now need to fill in the missing details before it can be activated and used."));
    $this->app->system->variables->systemMessagesWrite('success', _gettext("The user has been created with a temporary username, password and email address which need to be changed."));

    // Load the newly created user edit page
    $this->app->system->page->forcePage('user', 'edit&user_id='.\CMSApplication::$VAR['user_id']);

}
