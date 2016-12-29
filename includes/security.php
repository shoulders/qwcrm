<?php

/* 
 * i am going to use this to hold all security related functions for easy reference
 * some code will auto run aswell as functions being here almost like a seperate library
 */

// Force SSL/HTTPS if enabled - add base path stuff here
if($force_ssl == 1 && !isset($_SERVER['HTTPS'])){
    header('Location: https://' . QWCRM_DOMAIN . QWCRM_PATH );
}