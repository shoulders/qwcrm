<?php
// Connect database
//include_once('config.php');
//require_once ('include.php');
require('../../include/ADODB/toexport.inc.php');
Function exportdb (){
    $q = 'SELECT * INTO OUTFILE result.txt FROM MYIT_TABLE_LABOR_RATES';
    $rs = $db->Execute($q);
}