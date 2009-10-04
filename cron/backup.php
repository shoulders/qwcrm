<?php
/* Load MySQL Database Settings */
require('../conf.php');
$root = getenv("DOCUMENT_ROOT");

backup_tables($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

/* backup the db OR just a table */
function backup_tables($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $tables = '*')
{
	
	$link = mysql_connect( $DB_HOST, $DB_USER, $DB_PASS );
	mysql_select_db($DB_NAME, $link );
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$handle = fopen($root.'/DB-'.$DB_NAME.'-'.time().'.sql','a');
	fwrite($handle,$return);
	fclose($handle);
}
Echo "The database has now been backed up successfully. File is named DB-".$DB_NAME."-".time().".sql.Please use your ftp client to download this file from the /backup folder in your home folder for this program."
?>