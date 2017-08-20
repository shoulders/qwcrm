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
if(!check_page_accessed_via_qwcrm()) {
    force_page('index.php', '', 'warning_msg='.gettext("No Direct Access Allowed"));
    exit;
}

// Build the page
$BuildPage .= $smarty->fetch('setup/migrate.tpl');