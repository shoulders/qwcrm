<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');

// Check for updates
checkForQWcrmUpdate();

// Build the page with the update information
$BuildPage .= $smarty->fetch('administrator/update.tpl');

