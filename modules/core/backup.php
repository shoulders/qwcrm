//<?php
////	require_once('Mail.php');
////	require_once('Mail/mime.php');
//        require_once('../../conf.php');
//
//	// location of your temp directory
//	$tmpDir = "../../backup/";
//
//	/* email settings... */
////	$to = "myitcrm@gmail.com";
////	$from = "admin@localhost";
////	$subject = "db - backup";
//	$sqlFile = $tmpDir.PRFX.date('Y_m_d').".sql";
//	$attachment = $tmpDir.PRFX.date('Y_m_d').".tgz";
//
//
//        // TODO - Comment out when publishing live F:\M\MyITCRM Dev\xampp\mysql\bin\mysqldump.exe
//        //$sqlFile = $tmpDir.PRFX.date('Y_m_d').".sql";
//        $path_to_mysqldump = "F:\\M\\MyITCRM_Dev\\xampp\\mysql\\bin" ;
//        $creatBackup = "F:\\M\\MyITCRM_Dev\\xampp/mysql/bin/mysqldump.exe -u ".DB_USER." --password=".DB_PASS." ".DB_NAME." > ".$sqlFile;
//
//
//        //The below line is used in LIVE sites - Please uncomment
////        $creatBackup = "mysqldump -u ".DB_USER." --password=".DB_PASS." ".DB_NAME." > ".$sqlFile;
//	$createZip = "tar cvzf $attachment $sqlFile";
//	exec($creatBackup);
//	exec($createZip);
//
////	$headers = array('From'    => $from, 'Subject' => $subject);
////	$textMessage = $attachment;
////	$htmlMessage = "";
////
////	$mime = new Mail_Mime("\n");
////	$mime->setTxtBody($textMessage);
////	$mime->setHtmlBody($htmlMessage);
////	$mime->addAttachment($attachment, 'text/plain');
////	$body = $mime->get();
////	$hdrs = $mime->headers($headers);
////	$mail = &Mail::factory('mail');
////	$mail->send($to, $hdrs, $body);
////
////	unlink($sqlFile);
////	unlink($attachment);
//
//?>
