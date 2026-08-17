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
    $this->app->system->page->forcePage('supplier', 'search');
} else {

    // Create the user record and return the supplier_id
    \CMSApplication::$VAR['supplier_id'] = $this->app->components->supplier->insertRecord();

    // Advise on what to do next
    $this->app->system->variables->systemMessagesWrite('success', _gettext("A new supplier has been created, you now need to fill in the missing details before it can be activated and used."));

    // Edit the newly created record
    $this->app->system->page->forcePage('supplier', 'edit&supplier_id='.\CMSApplication::$VAR['supplier_id']);

}
