<?php

defined('_QWEXEC') or die;

// Build the page
$smarty->assign('help_details', $VAR);
$BuildPage .= $smarty->fetch('help/about.tpl');
