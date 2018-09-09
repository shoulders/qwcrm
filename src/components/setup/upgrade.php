<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'setup.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'upgrade', 'index_allowed')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

$setup = new Setup($BuildPage);

// set version number to 0.0.0'

// Build a List of all of the upgrade version steps
$upgrade_steps = $setup->get_upgrade_steps();

// Process each upgrade step
process_upgrade_steps($upgrade_steps);

// successfull message ?