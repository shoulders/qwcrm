<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/setup.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup:migrate', 'setup') || QWCRM_SETUP != 'migrate') {
    die(gettext("No Direct Access Allowed."));
}

// Log message to setup log - only when starting the process
if(!check_page_accessed_via_qwcrm('setup:migrate') ) {
    write_record_to_setup_log(gettext("QWcrm migration from MyITCRM has begun."), 'migrate');
}

// Build the page
$BuildPage .= $smarty->fetch('setup/migrate.tpl');