<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a cronjob_id
if(!isset(\CMSApplication::$VAR['cronjob_id']) || !\CMSApplication::$VAR['cronjob_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Cronjob ID supplied."));
    $this->app->system->page->forcePage('cronjob', 'search');
} 

// Build the page
$this->app->smarty->assign('cronjob_details', $this->app->components->cronjob->getRecord(\CMSApplication::$VAR['cronjob_id']));