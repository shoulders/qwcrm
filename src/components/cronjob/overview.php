<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Build the page
$this->app->smarty->assign('cronjob_system_details', $this->app->components->cronjob->getSystem());
$this->app->smarty->assign('display_cronjobs', $this->app->components->cronjob->getRecords('cronjob_id', 'DESC'));