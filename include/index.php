<?php
//echo "Direct access to this directory is forbidden!";
$errcodes[401] = 'HTTP/1.1 400 Bad Request';
header($errcodes[401]);
?>
