<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Create the otherincome record and return the new otherincome_id
\CMSApplication::$VAR['otherincome_id'] = $this->app->components->otherincome->insertRecord();

// Load the newly created invoice edit page
$this->app->system->page->forcePage('otherincome', 'edit&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);