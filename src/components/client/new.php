<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if the record can be created
if(!$this->app->components->client->checkRecordCanBeCreated()) {
    $this->app->system->page->forcePage('client', 'search');
} else {

    // Create the user record and return the client_id
    \CMSApplication::$VAR['client_id'] = $this->app->components->client->insertRecord();

    // Advise on what to do next
    $this->app->system->variables->systemMessagesWrite('success', _gettext("A new client has been created, you now need to fill in the missing details before it can be activated and used."));

    // Edit the newly created record
    $this->app->system->page->forcePage('client', 'edit&client_id='.\CMSApplication::$VAR['client_id']);

}
