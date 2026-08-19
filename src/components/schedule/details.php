<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Build the page
$this->app->smarty->assign('client_details', $this->app->components->client->getRecord($this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id'], 'client_id')));
$this->app->smarty->assign('schedule_details', $this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id']));
$this->app->smarty->assign('employee_display_name', $this->app->components->user->getRecord($this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id'], 'employee_id'), 'display_name')  );
$this->app->smarty->assign('allowed_to_edit', $this->app->components->schedule->checkRecordAllowsEdit(\CMSApplication::$VAR['schedule_id'], true));
$this->app->smarty->assign('allowed_to_delete', $this->app->components->schedule->checkRecordAllowsDelete(\CMSApplication::$VAR['schedule_id'], true));
