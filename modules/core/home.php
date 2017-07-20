<?php

defined('_QWEXEC') or die;

//$smarty->assign('remember_me', $QConfig->remember_me);
//$smarty->assign('recaptcha', $QConfig->recaptcha);
//$smarty->assign('recaptcha_site_key', $QConfig->recaptcha_site_key);

$BuildPage .= $smarty->fetch('core/home.tpl');