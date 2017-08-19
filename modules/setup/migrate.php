<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/setup.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    force_page('index.php', '', 'warning_msg='.gettext("No Direct Access Allowed"));
    exit;
}

// Build the page
$BuildPage .= $smarty->fetch('setup/migrate.tpl');