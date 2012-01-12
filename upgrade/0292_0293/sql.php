<?php

// Adding Version Number to Database
if (!insert_version_values($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."VERSION</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."VERSION</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
// ADDING VERSION NUMBER TO DATABASE
function insert_version_values($db)
{
    //Insert New Records for version table
    $q = "INSERT INTO `".PRFX."VERSION` (`VERSION_ID`, `VERSION_NAME`) VALUES ('293', '0.2.9.3')";

    if (!$rs = $db->Execute($q)) {
        return false;
    } else {
        return true;
    }

}

?>

