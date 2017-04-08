<?php

require(INCLUDES_DIR.'modules/administrator.php');

// Check for updates
checkForQWcrmUpdate();

// Fetch the page with the update information
$BuildPage .= $smarty->fetch('administrator/update.tpl');

