<?php

/** Main Include File **/

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Redirect page with javascript */
function force_page($module, $cur_page) {    
    echo('
        <script type="text/javascript">
            window.location = "index.php?page='.$module.':'.$cur_page.'"
        </script>
        ');
}

/* Write a record to the access log file */
function write_record_to_access_log($record){
    
    // Build log entry
    $record = $_SERVER['REMOTE_ADDR'] . ',' . date(DATE_W3C) . ',' . $record . "\n";
    
    // Apache log format
    // https://httpd.apache.org/docs/1.3/logs.html
    // Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined
    // 127.0.0.1 - frank [10/Oct/2000:13:55:36 -0700] "GET /apache_pb.gif HTTP/1.0" 200 2326 "http://www.example.com/start.html" "Mozilla/4.08 [en] (Win98; I ;Nav)"
    
    // Write log entry to access log    
    $fp = fopen(ACCESS_LOG,'a') or die($smarty->get_template_vars('translate_include_error_message_cant_open_access_log').': '.$php_errormsg);
    fwrite($fp, $record);
    fclose($fp);
    
    return;    
}