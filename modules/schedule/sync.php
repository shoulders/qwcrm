<?php

require(INCLUDES_DIR.'modules/schedule.php');

require_once('conf.php');
$sql = "SELECT SCHEDULE_ID,EMPLOYEE_ID,WORK_ORDER_ID,SCHEDULE_NOTES,SCHEDULE_START,SCHEDULE_END , FROM_UNIXTIME( SCHEDULE_START ,'%Y%m%d') as date, FROM_UNIXTIME( SCHEDULE_END ,'%Y%m%d') as edate, FROM_UNIXTIME( SCHEDULE_START ,'%H%i') as hour, FROM_UNIXTIME( SCHEDULE_END ,'%H%i') as ehour, FROM_UNIXTIME( SCHEDULE_START,'%i') as mintues, FROM_UNIXTIME( SCHEDULE_END,'%i') as emintues FROM myit_table_schedule WHERE WORK_ORDER_ID=".$VAR['workorder_id'];
$result = mysql_query($sql,$link); 
$Filename = "exported.ics"; 
if(isset($_GET["text"])) header("Content-Type: text/plain"); 
else {
header("Content-Type: text/Calendar");
header("Content-Disposition: inline; filename=$Filename");
}
?>BEGIN:VCALENDAR 
VERSION:1.0
<?php
$search = array ('/"/', '/,/', '/\n/', '/\r/', '/:/', '/;/', '/\\//'); // evaluate as php

$replace = array ('\"', '\\,', '\\n', '', '\:', '\\;', '\\\\');

while ( $row= mysql_fetch_assoc($result) ) {

$text = preg_replace($search, $replace, $row["SCHEDULE_NOTES"]); $text = wordwrap($text); $text = str_replace("\n","\n ",$text);

?>BEGIN:VEVENT
DTSTART:<? echo $row["date"]?>T<? echo $row["hour"]?><? echo $row["minutes"]?><? print "00" . "\n";?>
DTEND:<?echo $row["edate"]?>T<? echo $row["ehour"]?><? echo $row["eminutes"]?><? print "00" . "\n";?>
SUMMARY;ENCODING=QUOTED-PRINTABLE:WORK ORDER ID#<?php echo $row['WORK_ORDER_ID'] . "\n"; ?>
LOCATION;ENCODING=QUOTED-PRINTABLE:<? echo 'CLIENTS ADDRESS' . "\n";?>
DESCRIPTION;ENCODING=QUOTED-PRINTABLE:<?=$text . "\n";?>
PRIORITY:<? print "3" . "\n";?>
SEQUENCE:<? print "0" . "\n";?>
UID:<? print time() . "\n"; ?>
<? print "END:VEVENT"  . "\n"; ?>
<?php } ?>
END:VCALENDAR