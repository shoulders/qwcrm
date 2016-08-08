<?php

/** Main Include File **/

/* Redirect page with javascript */
function force_page($module, $page, $variables) {
    echo('
        <script type="text/javascript">
            window.location = "index.php?page='.$module.':'.$page.'&'.$variables.'"
        </script>
        ');
}

//function force_page_external($page) - if neeed

/* Write a record to the access log file */
function write_record_to_activity_log($record){
    
    // Build log entry
    $record = $_SERVER['REMOTE_ADDR'] . ',' . date(DATE_W3C) . ',' . $record . "\n";
    
    // Apache log format
    // https://httpd.apache.org/docs/1.3/logs.html
    // Combined Log Format - LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" combined
    // 127.0.0.1 - frank [10/Oct/2000:13:55:36 -0700] "GET /apache_pb.gif HTTP/1.0" 200 2326 "http://www.example.com/start.html" "Mozilla/4.08 [en] (Win98; I ;Nav)"
    
    // Write log entry to access log    
    $fp = fopen(ACTIVITY_LOG,'a') or die($smarty->get_template_vars('translate_include_error_message_cant_open_activity_log').': '.$php_errormsg);
    fwrite($fp, $record);
    fclose($fp);
    
    return;    
}

/* Language Translation Function */

// remove error control from the modules and add it here.

function xml2php($module) {
    global $smarty;

    //$file = FILE_ROOT."language".SEP.$module.SEP.LANG ;
    $file = 'language/'.LANG ;

    $xml_parser = xml_parser_create();
    if (!($fp = fopen($file, 'r'))) {
       die('unable to open XML');
    }
    $contents = fread($fp, filesize($file));
    fclose($fp);
    xml_parse_into_struct($xml_parser, $contents, $arr_vals);   
    xml_parser_free($xml_parser);

    foreach($arr_vals as $things){
        if($things['tag'] != 'TRANSLATE' && $things['value'] != "" ){
            $smarty->assign('translate_'.strtolower($things['tag']),$things['value']);
        }
    }    

    return true;
}