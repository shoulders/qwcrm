<?php

defined('_QWEXEC') or die;

$smarty->assign('remember_me', $QConfig->remember_me);
$smarty->assign('captcha', $captcha);
$smarty->assign('recaptcha_site_key', $recaptcha_site_key);

$BuildPage .= $smarty->fetch('core/home.tpl');