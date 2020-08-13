#!/usr/bin/env php
<?php
/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

if (PHP_SAPI !== 'cli') {
    echo 'This file must be run as a CLI application';
    exit(1);
}

// Let QWcrm know it is a Real Cron running it
const _REAL_CRONJOB = 1;

// Load QWcrm
require('index.php');