<?php

/* 
 * i am going to use thgis to hold all security related function for easy reference
 * some code will auto run aswell as functions being here almost like a seperate librbarr
 */

// Force SSL/HTTPS if enabled - add base path stuff here
if($force_ssl == 1 && !isset($_SERVER['HTTPS'])){
    header('Location: https://' . QWCRM_DOMAIN . QWCRM_PATH );
}