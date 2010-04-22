<?php
function xml2php($module) {
	global $smarty;

	//$file = FILE_ROOT."language".SEP.$module.SEP.LANG ;
        $file = FILE_ROOT."language".SEP.LANG ;
	
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

//print_r($arr);
?>
