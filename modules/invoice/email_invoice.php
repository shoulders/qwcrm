<?php
require_once ('include.php');
//require_once ('new.php');
$from = "admin@loclahost.com";
$to = "glen.vanderhel@au.chh.com";
$subject = "Your Invoice for Job ".$wo_id ;
$body = "Hi,\n\nHow are you?";
$headers = "From: ".$from ;
if (mail($to, $subject, $body, $headers)) {
  echo("<p>Message successfully sent!</p>");
 } else {
  echo("<p>Message delivery failed...</p>");
 }
 

?>
