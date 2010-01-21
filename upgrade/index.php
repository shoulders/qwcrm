<?php
###############################
#		Switch 						#
###############################
$mode = $_POST['mode'];
switch ($mode) {

############################
#		Install 					#
############################
    case "install":
		/* display page header and start graphics */
        echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n
<html>\n
<head>\n
	<title>MyIT Upgrader</title>\n
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n
	<link href=\"../css/default.css\" rel=\"stylesheet\" type=\"text/css\">\n

</head>\n
<body>\n
<center>\n
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
	<tr>\n
		<td><img src=\"../images/logo.jpg\" alt=\"\" width=\"490\" height=\"114\"></td>\n
	</tr>\n
</table>\n
			
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n
	<tr>\n
		<td colspan=\"3\" background=\"../images/index03.gif\"><img src=\"../images/index03.gif\" alt=\"\" width=\"100%\" height=\"40\"></td>\n
	</tr><tr>\n
		<td align=\"center\">\n

			<table width=\"100%\" border=\"0\" cellpadding=\"20\" cellspacing=\"0\">\n
				<tr>\n
					<td class=\"olotd\" align=\"center\">\n
						
						<!-- Begin Page -->\n
						<table width=\"800\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
							<tr>\n
								<td class=\"menuhead2\" width=\"80%\">&nbsp;MYIT CRM Upgrader</td>\n
							</tr><tr>\n
								<td class=\"menutd2\" colspan=\"2\">\n

									<table width=\"100%\"  class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
										<tr>
											<td>
												<table width=\"100%\"  class=\"menutd\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
									");	

        
		/* Load our new configs */

        require("../include/ADODB/adodb.inc.php");

		/* Create ADODB Connection */
        $db = &ADONewConnection('mysql');

        $db->Connect($_POST['db_host'] ,$_POST['db_user'], $_POST['db_password']);
        if( $db->errorMsg() != '' ) {
            echo "There was an error connecting to the database: ".$db->errorMsg();
            die;
        }



        ##################################
        # Create New Connection				#
        ##################################
        $db->close();
        include("../conf.php");

        if( $db->errorMsg() != '' ) {
            echo "There Was an error connecting to the database: ".$db->errorMsg();
            die;
        }
        $curr = $_POST['currency'];
        $path2 = $_POST['default_site_name'];
        $prefix = $_POST['db_prefix'];
        @define('PRFX', $prefix);
        ##################################
        # Build Tables							#
        ##################################
		/*include sql.php */
        include("sql.php");


        if($error_flag == true) {
	/* error can not complete the install */
            echo("<tr>\n
				<td colspan=\"2\">There where errors during the upgrade process. MyIT CRM has not been upgraded. If the errors continue please submit a bug report at http://trac.myitcrm.com</td>\n
			</tr>\n");
        } else {
		/* create lock file */
            if(!touch("../cache/lock")) {
                echo("<tr><td colspan=\"2\"><font color=\"red\">Failed to create lock file. Please create a file name lock and put it in the cache folder !!</font></td></tr>");
            }

		/* done */

            echo("<tr>\n<td colspan=\"2\"><font size=\+2 color=\"red\">Your Upgrade was successful from version 0.2.7.3 to 0.2.8.1.</font>
				<br><br>
				There are still a few steps that need to be completed.<br>
				1. You need to move or rename the upgrade directory. We recommend deleting this folder.<br>
                                2. Add the following to your conf.php file<br>
                                 <b>\$currency_code = '".$curr."';</b>
                                <br>

				3. You can now resume your normal operation mode.
				<br><br>
				Where to find help:<br>
				The user Documentation is at <a href=\"http://trac.myitcrm.com/wiki/\">http://trac.myitcrm.com/wiki/</a><br>
				Bug/Feature Reporting is at <a href=\"http://trac.myitcrm.com/newticket\">TRAC Bug Tracker</a><br>

				</td>\n</tr>\n");
        }

        echo("
									</table>\n
								</td>\n
							</tr>\n
						</table>\n

					</td>\n
				</tr>\n
			</table>\n
		</td>\n
	</tr>\n
</table>\n
			<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
				<tr>
					<td height=\"51\" align=\"center\" background=\"../images/index41.gif\"></td>\n
				</tr><tr>\n
					<td height=\"48\" align=\"center\" background=\"../images/index42.gif\"><span class=\"text3\"></a>
								All rights reserved.</span></td>\n
				</tr><tr>\n
					<td>&nbsp;</td>\n
				</tr>\n
			</table>\n
		</td>\n
	</tr>\n
</table>\n
</center>\n

</body>\n
</html>\n");
        break;

    ################################
    #		Default						#
    ###############################
    default:
        $default_path = resolveDocumentRoot();
        $default_server = get_server_name();

        echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html>
<head>
	<title>MYIT CRM Upgrader</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
	<link href=\"../css/default.css\" rel=\"stylesheet\" type=\"text/css\">");
        include('validate.js');
        echo ("
</head>
<body>
<p>&nbsp;</p>
<center>
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td><img src=\"../images/logo.jpg\" alt=\"\" width=\"490\" height=\"114\"></td>
	</tr>
</table>
			
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
	<tr>
		<td colspan=\"3\" background=\"../images/index03.gif\"><img src=\"../images/index03.gif\" alt=\"\" width=\"100%\" height=\"40\"></td>
	</tr><tr>
		<td align=\"center\">
		<br><br>

<table width=\"100%\" border=\"0\" cellpadding=\"20\" cellspacing=\"0\">
	<tr>
		<td class=\"olotd\" align=\"center\">
			
			<!-- Begin Page -->
			<table width=\"800\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
				<tr>
					<td class=\"menuhead2\" width=\"80%\">&nbsp;MyIT CRM Upgrader from version 0.2.7.3 to 0.2.8.1</td>
					</td>
				</tr><tr>
					<td class=\"menutd2\" colspan=\"2\">

						<table width=\"100%\" class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
							<tr>
								<td width=\"100%\" valign=\"top\" >

									<form action=\"index.php\" method=\"POST\" name=\"install\" id=\"install\" onsubmit=\"try   { var myValidator = validate_install; } catch(e) { return true; } return myValidator(this);\">
									<input type=\"hidden\" name=\"mode\" value=\"install\">

										<table width=\"100%\" class=\"menutd\" cellspacing=\"0\"  border=\"0\" cellpadding=\"5\">
											<tr>
												<td>
													<table >
														<tr>
															<td>
															<b>Initial File Checks</b><br>

															<table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																
																	<td width=\"140\">Cache Folder</td>
																	<td>");
        if(!check_write ('../cache')) {
            echo("<font color=\"red\">../cache is not writable stopping.</font>");
            $errors[] = array('../cache'=>'Not Writable');
        } else {
            echo("<font color=\"green\"><b>OK</b>");
        }
        echo( "</td>
																
																</tr><tr>
																
																	<td width=\"140\">Access Log</td>
																	<td>");
        if(!check_write ('../log/access.log')) {
            echo("<font color=\"red\">../log/access.log is not writable stopping.</font>");
            $errors[] = array('../log/access.log'=>'Not Writable');
        } else {
            echo("<font color=\"green\"><b>OK</b>");
        }
        echo("<td>
																	
																</tr><tr>
															<!-- End of File Checks -->
																	<td colspan=\"2\">&nbsp;</td>
																</tr><tr>
																	<td colspan=\"2\"></td>
																		
																</tr>
															</table>");
        if(is_array($errors)) {
            echo("Set up can not continue until the following errors are fixed:<br>");
            foreach($errors as $key=>$val) {
                echo("<font color=\"red\">Error $key: ");
                foreach($val as $k=>$v) {
                    echo("$k $v");
                }
                echo("</font><br>");
            }
        } else {
            echo ("
															<br>
															<b>Database Information:</b>
															<table  class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
																<tr>
																	<td valign=\"top\" width=\"60%\" align=\"left\">
																		<table >
																			<tr>
																				<td width=\"140\">Database User:</td>
																				<td ><input type=\"text\" size=\"20\" name=\"db_user\" value=\"username\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Database Password:</td>
																				<td><input type=\"password\" size=\"20\" name=\"db_password\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Database Host:</td>
																				<td><input type=\"text\" size=\"20\" name=\"db_host\" value=\"localhost\" class=\"olotd5\"></td>
																			</tr><tr>
																				<td width=\"140\">Database Name:</td>
																				<td>
																					<input type=\"text\" size=\"30\" name=\"db_name\" value=\"myitcrm\" class=\"olotd5\">
																				</td>
																			</tr><tr>
																					<td width=\"140\">Table Prefix</td>
																					<td>
																						<input type=\"text\" size=\"30\" name=\"db_prefix\" value=\"MYIT_\" class=\"olotd5\">
																					</td>
																				</tr>
<tr>
																					<td width=\"140\">Currency</td>
																					<td>
																						<select name=\"currency\" size=\"1\" >
																							<option value=\"AFN\">Afghanistan, Afghanis</option>
                                                                                                                                                                                        <option value=\"ALL\">Albania, Leke</option>
                                                                                                                                                                                        <option value=\"DZD\">Algeria, Dinars</option>
                                                                                                                                                                                        <option value=\"USD\">America (United States of America), Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">American Samoa, United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">American Virgin Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Andorra, Euro</option>
                                                                                                                                                                                        <option value=\"AOA\">Angola, Kwanza</option>
                                                                                                                                                                                        <option value=\"XCD\">Anguilla, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"XCD\">Antigua and Barbuda, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"ARS\">Argentina, Pesos</option>
                                                                                                                                                                                        <option value=\"AMD\">Armenia, Drams</option>
                                                                                                                                                                                        <option value=\"AWG\">Aruba, Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"AUD\" SELECTED>Australia, Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Austria, Euro</option>
                                                                                                                                                                                        <option value=\"AZN\">Azerbaijan, New Manats</option>
                                                                                                                                                                                        <option value=\"EUR\">Azores, Euro</option>
                                                                                                                                                                                        <option value=\"BSD\">Bahamas, Dollars</option>
                                                                                                                                                                                        <option value=\"BHD\">Bahrain, Dinars</option>
                                                                                                                                                                                        <option value=\"EUR\">Baleares (Balearic Islands), Euro</option>
                                                                                                                                                                                        <option value=\"BDT\">Bangladesh, Taka</option>
                                                                                                                                                                                        <option value=\"BBD\">Barbados, Dollars</option>
                                                                                                                                                                                        <option value=\"XCD\">Barbuda and Antigua, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"BYR\">Belarus, Rubles</option>
                                                                                                                                                                                        <option value=\"EUR\">Belgium, Euro</option>
                                                                                                                                                                                        <option value=\"BZD\">Belize, Dollars</option>
                                                                                                                                                                                        <option value=\"XOF\">Benin, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"BMD\">Bermuda, Dollars</option>
                                                                                                                                                                                        <option value=\"BTN\">Bhutan, Ngultrum</option>
                                                                                                                                                                                        <option value=\"INR\">Bhutan, India Rupees</option>
                                                                                                                                                                                        <option value=\"BOB\">Bolivia, Bolivianos</option>
                                                                                                                                                                                        <option value=\"ANG\">Bonaire, Netherlands Antilles Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"BAM\">Bosnia and Herzegovina, Convertible Marka</option>
                                                                                                                                                                                        <option value=\"BWP\">Botswana, Pulas</option>
                                                                                                                                                                                        <option value=\"NOK\">Bouvet Island, Norway Kroner</option>
                                                                                                                                                                                        <option value=\"BRL\">Brazil, Real</option>
                                                                                                                                                                                        <option value=\"GBP\">Britain (United Kingdom), Pounds</option>
                                                                                                                                                                                        <option value=\"USD\">British Indian Ocean Territory, United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">British Virgin Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"BND\">Brunei Darussalam, Dollars</option>
                                                                                                                                                                                        <option value=\"BGN\">Bulgaria, Leva</option>
                                                                                                                                                                                        <option value=\"XOF\">Burkina Faso, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"MMK\">Burma (Myanmar), Kyats</option>
                                                                                                                                                                                        <option value=\"BIF\">Burundi, Francs</option>
                                                                                                                                                                                        <option value=\"XOF\">Côte D'Ivoire, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"USD\">Caicos and Turks Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"KHR\">Cambodia, Riels</option>
                                                                                                                                                                                        <option value=\"XAF\">Cameroon, Communauté Financière Africaine Francs (BEAC)</option>
                                                                                                                                                                                        <option value=\"CAD\">Canada, Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Canary Islands, Euro</option>
                                                                                                                                                                                        <option value=\"CVE\">Cape Verde, Escudos</option>
                                                                                                                                                                                        <option value=\"KYD\">Cayman Islands, Dollars</option>
                                                                                                                                                                                        <option value=\"XAF\">Central African Republic, Communauté Financière Africaine Francs (BEAC)</option>
                                                                                                                                                                                        <option value=\"XAF\">Chad, Communauté Financière Africaine Francs (BEAC)</option>
                                                                                                                                                                                        <option value=\"CLP\">Chile, Pesos</option>
                                                                                                                                                                                        <option value=\"CNY\">China, Yuan Renminbi</option>
                                                                                                                                                                                        <option value=\"AUD\">Christmas Island, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"AUD\">Cocos (Keeling) Islands, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"COP\">Colombia, Pesos</option>
                                                                                                                                                                                        <option value=\"XAF\">Communauté Financière Africaine (CFA), Francs</option>
                                                                                                                                                                                        <option value=\"KMF\">Comoros, Francs</option>
                                                                                                                                                                                        <option value=\"XPF\">Comptoirs Français du Pacifique (CFP), Francs</option>
                                                                                                                                                                                        <option value=\"XAF\">Congo/Brazzaville, Communauté Financière Africaine Francs (BEAC)</option>
                                                                                                                                                                                        <option value=\"CDF\">Congo/Kinshasa, Francs</option>
                                                                                                                                                                                        <option value=\"NZD\">Cook Islands, New Zealand Dollars</option>
                                                                                                                                                                                        <option value=\"CRC\">Costa Rica, Colones</option>
                                                                                                                                                                                        <option value=\"HRK\">Croatia, Kuna</option>
                                                                                                                                                                                        <option value=\"CUP\">Cuba, Pesos</option>
                                                                                                                                                                                        <option value=\"ANG\">Curaço, Netherlands Antilles Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"EUR\">Cyprus, Euro</option>
                                                                                                                                                                                        <option value=\"CYP\">Cyprus, Pounds (expires 2008-Jan-31)</option>
                                                                                                                                                                                        <option value=\"CZK\">Czech Republic, Koruny</option>
                                                                                                                                                                                        <option value=\"DKK\">Denmark, Kroner</option>
                                                                                                                                                                                        <option value=\"DJF\">Djibouti, Francs</option>
                                                                                                                                                                                        <option value=\"XCD\">Dominica, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"DOP\">Dominican Republic, Pesos</option>
                                                                                                                                                                                        <option value=\"EUR\">Dutch (Netherlands) Euro</option>
                                                                                                                                                                                        <option value=\"XCD\">East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"IDR\">East Timor, Indonesia Rupiahs</option>
                                                                                                                                                                                        <option value=\"USD\">Ecuador, United States Dollars</option>
                                                                                                                                                                                        <option value=\"EGP\">Egypt, Pounds</option>
                                                                                                                                                                                        <option value=\"EUR\">Eire (Ireland), Euro</option>
                                                                                                                                                                                        <option value=\"SVC\">El Salvador, Colones</option>
                                                                                                                                                                                        <option value=\"USD\">El Salvador, United States Dollars</option>
                                                                                                                                                                                        <option value=\"GBP\">England (United Kingdom), Pounds</option>
                                                                                                                                                                                        <option value=\"XAF\">Equatorial Guinea, Communauté Financière Africaine Francs (BEAC)</option>
                                                                                                                                                                                        <option value=\"ETB\">Eritrea, Ethiopia Birr</option>
                                                                                                                                                                                        <option value=\"ERN\">Eritrea, Nakfa</option>
                                                                                                                                                                                        <option value=\"EEK\">Estonia, Krooni</option>
                                                                                                                                                                                        <option value=\"ETB\">Ethiopia, Birr</option>
                                                                                                                                                                                        <option value=\"EUR\">Euro Member Countries, Euro</option>
                                                                                                                                                                                        <option value=\"FKP\">Falkland Islands (Malvinas), Pounds</option>
                                                                                                                                                                                        <option value=\"DKK\">Faroe Islands, Denmark Kroner</option>
                                                                                                                                                                                        <option value=\"FJD\">Fiji, Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Finland, Euro</option>
                                                                                                                                                                                        <option value=\"EUR\">France, Euro</option>
                                                                                                                                                                                        <option value=\"EUR\">French Guiana, Euro</option>
                                                                                                                                                                                        <option value=\"XPF\">French Pacific Islands (French Polynesia), Comptoirs Français du Pacifique Francs</option>
                                                                                                                                                                                        <option value=\"XPF\">French Polynesia (French Pacific Islands), Comptoirs Français du Pacifique Francs</option>
                                                                                                                                                                                        <option value=\"EUR\">French Southern Territories, Euro</option>
                                                                                                                                                                                        <option value=\"XPF\">Futuna and Wallis Islands, Comptoirs Français du Pacifique Francs</option>
                                                                                                                                                                                        <option value=\"XAF\">Gabon, Communauté Financière Africaine Francs (BEAC)</option>
                                                                                                                                                                                        <option value=\"GMD\">Gambia, Dalasi</option>
                                                                                                                                                                                        <option value=\"GEL\">Georgia, Lari</option>
                                                                                                                                                                                        <option value=\"EUR\">Germany, Euro</option>
                                                                                                                                                                                        <option value=\"GHS\">Ghana, Cedis</option>
                                                                                                                                                                                        <option value=\"GIP\">Gibraltar, Pounds</option>
                                                                                                                                                                                        <option value=\"XAU\">Gold, Ounces</option>
                                                                                                                                                                                        <option value=\"EUR\">Greece, Euro</option>
                                                                                                                                                                                        <option value=\"DKK\">Greenland, Denmark Kroner</option>
                                                                                                                                                                                        <option value=\"XCD\">Grenada, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"XCD\">Grenadines (The) and Saint Vincent, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Guadeloupe, Euro</option>
                                                                                                                                                                                        <option value=\"USD\">Guam, United States Dollars</option>
                                                                                                                                                                                        <option value=\"GTQ\">Guatemala, Quetzales</option>
                                                                                                                                                                                        <option value=\"GGP\">Guernsey, Pounds</option>
                                                                                                                                                                                        <option value=\"GNF\">Guinea, Francs</option>
                                                                                                                                                                                        <option value=\"XOF\">Guinea-Bissau, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"GYD\">Guyana, Dollars</option>
                                                                                                                                                                                        <option value=\"HTG\">Haiti, Gourdes</option>
                                                                                                                                                                                        <option value=\"USD\">Haiti, United States Dollars</option>
                                                                                                                                                                                        <option value=\"AUD\">Heard Island and McDonald Islands, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"BAM\">Herzegovina and Bosnia, Convertible Marka</option>
                                                                                                                                                                                        <option value=\"EUR\">Holland (Netherlands), Euro</option>
                                                                                                                                                                                        <option value=\"EUR\">Holy See, (Vatican City), Euro</option>
                                                                                                                                                                                        <option value=\"HNL\">Honduras, Lempiras</option>
                                                                                                                                                                                        <option value=\"HKD\">Hong Kong, Dollars</option>
                                                                                                                                                                                        <option value=\"HUF\">Hungary, Forint</option>
                                                                                                                                                                                        <option value=\"ISK\">Iceland, Kronur</option>
                                                                                                                                                                                        <option value=\"INR\">India, Rupees</option>
                                                                                                                                                                                        <option value=\"IDR\">Indonesia, Rupiahs</option>
                                                                                                                                                                                        <option value=\"XDR\">International Monetary Fund (IMF), Special Drawing Rights</option>
                                                                                                                                                                                        <option value=\"IRR\">Iran, Rials</option>
                                                                                                                                                                                        <option value=\"IQD\">Iraq, Dinars</option>
                                                                                                                                                                                        <option value=\"EUR\">Ireland (Eire), Euro</option>
                                                                                                                                                                                        <option value=\"IMP\">Isle of Man, Pounds</option>
                                                                                                                                                                                        <option value=\"ILS\">Israel, New Shekels</option>
                                                                                                                                                                                        <option value=\"EUR\">Italy, Euro</option>
                                                                                                                                                                                        <option value=\"JMD\">Jamaica, Dollars</option>
                                                                                                                                                                                        <option value=\"NOK\">Jan Mayen and Svalbard, Norway Kroner</option>
                                                                                                                                                                                        <option value=\"JPY\">Japan, Yen</option>
                                                                                                                                                                                        <option value=\"JEP\">Jersey, Pounds</option>
                                                                                                                                                                                        <option value=\"JOD\">Jordan, Dinars</option>
                                                                                                                                                                                        <option value=\"KZT\">Kazakhstan, Tenge</option>
                                                                                                                                                                                        <option value=\"AUD\">Keeling (Cocos) Islands, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"KES\">Kenya, Shillings</option>
                                                                                                                                                                                        <option value=\"AUD\">Kiribati, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"KPW\">Korea (North), Won</option>
                                                                                                                                                                                        <option value=\"KRW\">Korea (South), Won</option>
                                                                                                                                                                                        <option value=\"KWD\">Kuwait, Dinars</option>
                                                                                                                                                                                        <option value=\"KGS\">Kyrgyzstan, Soms</option>
                                                                                                                                                                                        <option value=\"LAK\">Laos, Kips</option>
                                                                                                                                                                                        <option value=\"LVL\">Latvia, Lati</option>
                                                                                                                                                                                        <option value=\"LBP\">Lebanon, Pounds</option>
                                                                                                                                                                                        <option value=\"LSL\">Lesotho, Maloti</option>
                                                                                                                                                                                        <option value=\"ZAR\">Lesotho, South Africa Rand</option>
                                                                                                                                                                                        <option value=\"LRD\">Liberia, Dollars</option>
                                                                                                                                                                                        <option value=\"LYD\">Libya, Dinars</option>
                                                                                                                                                                                        <option value=\"CHF\">Liechtenstein, Switzerland Francs</option>
                                                                                                                                                                                        <option value=\"LTL\">Lithuania, Litai</option>
                                                                                                                                                                                        <option value=\"EUR\">Luxembourg, Euro</option>
                                                                                                                                                                                        <option value=\"MOP\">Macau, Patacas</option>
                                                                                                                                                                                        <option value=\"MKD\">Macedonia, Denars</option>
                                                                                                                                                                                        <option value=\"MGA\">Madagascar, Ariary</option>
                                                                                                                                                                                        <option value=\"EUR\">Madeira Islands, Euro</option>
                                                                                                                                                                                        <option value=\"MWK\">Malawi, Kwachas</option>
                                                                                                                                                                                        <option value=\"MYR\">Malaysia, Ringgits</option>
                                                                                                                                                                                        <option value=\"MVR\">Maldives (Maldive Islands), Rufiyaa</option>
                                                                                                                                                                                        <option value=\"XOF\">Mali, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"EUR\">Malta, Euro</option>
                                                                                                                                                                                        <option value=\"MTL\">Malta, Liri (expires 2008-Jan-31)</option>
                                                                                                                                                                                        <option value=\"FKP\">Malvinas (Falkland Islands), Pounds</option>
                                                                                                                                                                                        <option value=\"USD\">Mariana Islands (Northern), United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">Marshall Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Martinique, Euro</option>
                                                                                                                                                                                        <option value=\"MRO\">Mauritania, Ouguiyas</option>
                                                                                                                                                                                        <option value=\"MUR\">Mauritius, Rupees</option>
                                                                                                                                                                                        <option value=\"EUR\">Mayotte, Euro</option>
                                                                                                                                                                                        <option value=\"AUD\">McDonald Islands and Heard Island, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"MXN\">Mexico, Pesos</option>
                                                                                                                                                                                        <option value=\"USD\">Micronesia (Federated States of), United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">Midway Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Miquelon and Saint Pierre, Euro</option>
                                                                                                                                                                                        <option value=\"MDL\">Moldova, Lei</option>
                                                                                                                                                                                        <option value=\"EUR\">Monaco, Euro</option>
                                                                                                                                                                                        <option value=\"MNT\">Mongolia, Tugriks</option>
                                                                                                                                                                                        <option value=\"EUR\">Montenegro, Euro</option>
                                                                                                                                                                                        <option value=\"XCD\">Montserrat, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"MAD\">Morocco, Dirhams</option>
                                                                                                                                                                                        <option value=\"MZN\">Mozambique, Meticais</option>
                                                                                                                                                                                        <option value=\"MMK\">Myanmar (Burma), Kyats</option>
                                                                                                                                                                                        <option value=\"NAD\">Namibia, Dollars</option>
                                                                                                                                                                                        <option value=\"ZAR\">Namibia, South Africa Rand</option>
                                                                                                                                                                                        <option value=\"AUD\">Nauru, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"NPR\">Nepal, Rupees</option>
                                                                                                                                                                                        <option value=\"ANG\">Netherlands Antilles, Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"EUR\">Netherlands, Euro</option>
                                                                                                                                                                                        <option value=\"XCD\">Nevis and Saint Kitts, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"XPF\">New Caledonia, Comptoirs Français du Pacifique Francs</option>
                                                                                                                                                                                        <option value=\"NZD\">New Zealand, Dollars</option>
                                                                                                                                                                                        <option value=\"NIO\">Nicaragua, Cordobas</option>
                                                                                                                                                                                        <option value=\"XOF\">Niger, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"NGN\">Nigeria, Nairas</option>
                                                                                                                                                                                        <option value=\"NZD\">Niue, New Zealand Dollars</option>
                                                                                                                                                                                        <option value=\"AUD\">Norfolk Island, Australia Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">Northern Mariana Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"NOK\">Norway, Kroner</option>
                                                                                                                                                                                        <option value=\"OMR\">Oman, Rials</option>
                                                                                                                                                                                        <option value=\"PKR\">Pakistan, Rupees</option>
                                                                                                                                                                                        <option value=\"USD\">Palau, United States Dollars</option>
                                                                                                                                                                                        <option value=\"XPD\">Palladium, Ounces</option>
                                                                                                                                                                                        <option value=\"PAB\">Panama, Balboa</option>
                                                                                                                                                                                        <option value=\"USD\">Panama, United States Dollars</option>
                                                                                                                                                                                        <option value=\"PGK\">Papua New Guinea, Kina</option>
                                                                                                                                                                                        <option value=\"PYG\">Paraguay, Guarani</option>
                                                                                                                                                                                        <option value=\"PEN\">Peru, Nuevos Soles</option>
                                                                                                                                                                                        <option value=\"PHP\">Philippines, Pesos</option>
                                                                                                                                                                                        <option value=\"NZD\">Pitcairn Islands, New Zealand Dollars</option>
                                                                                                                                                                                        <option value=\"XPT\">Platinum, Ounces</option>
                                                                                                                                                                                        <option value=\"PLN\">Poland, Zlotych</option>
                                                                                                                                                                                        <option value=\"EUR\">Portugal, Euro</option>
                                                                                                                                                                                        <option value=\"STD\">Principe and São Tome, Dobras</option>
                                                                                                                                                                                        <option value=\"USD\">Puerto Rico, United States Dollars</option>
                                                                                                                                                                                        <option value=\"QAR\">Qatar, Rials</option>
                                                                                                                                                                                        <option value=\"EUR\">Réunion, Euro</option>
                                                                                                                                                                                        <option value=\"RON\">Romania, New Lei</option>
                                                                                                                                                                                        <option value=\"RUB\">Russia, Rubles</option>
                                                                                                                                                                                        <option value=\"RWF\">Rwanda, Francs</option>
                                                                                                                                                                                        <option value=\"STD\">São Tome and Principe, Dobras</option>
                                                                                                                                                                                        <option value=\"ANG\">Saba, Netherlands Antilles Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"MAD\">Sahara (Western), Morocco Dirhams</option>
                                                                                                                                                                                        <option value=\"XCD\">Saint Christopher, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"SHP\">Saint Helena, Pounds</option>
                                                                                                                                                                                        <option value=\"XCD\">Saint Kitts and Nevis, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"XCD\">Saint Lucia, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Saint Pierre and Miquelon, Euro</option>
                                                                                                                                                                                        <option value=\"XCD\">Saint Vincent and The Grenadines, East Caribbean Dollars</option>
                                                                                                                                                                                        <option value=\"EUR\">Saint-Martin, Euro</option>
                                                                                                                                                                                        <option value=\"USD\">Samoa (American), United States Dollars</option>
                                                                                                                                                                                        <option value=\"WST\">Samoa, Tala</option>
                                                                                                                                                                                        <option value=\"EUR\">San Marino, Euro</option>
                                                                                                                                                                                        <option value=\"SAR\">Saudi Arabia, Riyals</option>
                                                                                                                                                                                        <option value=\"SPL\">Seborga, Luigini</option>
                                                                                                                                                                                        <option value=\"XOF\">Senegal, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"RSD\">Serbia, Dinars</option>
                                                                                                                                                                                        <option value=\"SCR\">Seychelles, Rupees</option>
                                                                                                                                                                                        <option value=\"SLL\">Sierra Leone, Leones</option>
                                                                                                                                                                                        <option value=\"XAG\">Silver, Ounces</option>
                                                                                                                                                                                        <option value=\"SGD\">Singapore, Dollars</option>
                                                                                                                                                                                        <option value=\"ANG\">Sint Eustatius, Netherlands Antilles Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"ANG\">Sint Maarten, Netherlands Antilles Guilders (also called Florins)</option>
                                                                                                                                                                                        <option value=\"EUR\">Slovakia, Euro</option>
                                                                                                                                                                                        <option value=\"EUR\">Slovenia, Euro</option>
                                                                                                                                                                                        <option value=\"SBD\">Solomon Islands, Dollars</option>
                                                                                                                                                                                        <option value=\"SOS\">Somalia, Shillings</option>
                                                                                                                                                                                        <option value=\"ZAR\">South Africa, Rand</option>
                                                                                                                                                                                        <option value=\"GBP\">South Georgia, United Kingdom Pounds</option>
                                                                                                                                                                                        <option value=\"GBP\">South Sandwich Islands, United Kingdom Pounds</option>
                                                                                                                                                                                        <option value=\"EUR\">Spain, Euro</option>
                                                                                                                                                                                        <option value=\"XDR\">Special Drawing Rights</option>
                                                                                                                                                                                        <option value=\"LKR\">Sri Lanka, Rupees</option>
                                                                                                                                                                                        <option value=\"SDG\">Sudan, Pounds</option>
                                                                                                                                                                                        <option value=\"SRD\">Suriname, Dollars</option>
                                                                                                                                                                                        <option value=\"NOK\">Svalbard and Jan Mayen, Norway Kroner</option>
                                                                                                                                                                                        <option value=\"SZL\">Swaziland, Emalangeni</option>
                                                                                                                                                                                        <option value=\"SEK\">Sweden, Kronor</option>
                                                                                                                                                                                        <option value=\"CHF\">Switzerland, Francs</option>
                                                                                                                                                                                        <option value=\"SYP\">Syria, Pounds</option>
                                                                                                                                                                                        <option value=\"TWD\">Taiwan, New Dollars</option>
                                                                                                                                                                                        <option value=\"RUB\">Tajikistan, Russia Rubles</option>
                                                                                                                                                                                        <option value=\"TJS\">Tajikistan, Somoni</option>
                                                                                                                                                                                        <option value=\"TZS\">Tanzania, Shillings</option>
                                                                                                                                                                                        <option value=\"THB\">Thailand, Baht</option>
                                                                                                                                                                                        <option value=\"IDR\">Timor (East), Indonesia Rupiahs</option>
                                                                                                                                                                                        <option value=\"TTD\">Tobago and Trinidad, Dollars</option>
                                                                                                                                                                                        <option value=\"XOF\">Togo, Communauté Financière Africaine Francs (BCEAO)</option>
                                                                                                                                                                                        <option value=\"NZD\">Tokelau, New Zealand Dollars</option>
                                                                                                                                                                                        <option value=\"TOP\">Tonga, Pa'anga</option>
                                                                                                                                                                                        <option value=\"TTD\">Trinidad and Tobago, Dollars</option>
                                                                                                                                                                                        <option value=\"TND\">Tunisia, Dinars</option>
                                                                                                                                                                                        <option value=\"TRY\">Turkey, Lira</option>
                                                                                                                                                                                        <option value=\"TMM\">Turkmenistan, Manats</option>
                                                                                                                                                                                        <option value=\"USD\">Turks and Caicos Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"TVD\">Tuvalu, Tuvalu Dollars</option>
                                                                                                                                                                                        <option value=\"UGX\">Uganda, Shillings</option>
                                                                                                                                                                                        <option value=\"UAH\">Ukraine, Hryvnia</option>
                                                                                                                                                                                        <option value=\"AED\">United Arab Emirates, Dirhams</option>
                                                                                                                                                                                        <option value=\"GBP\">United Kingdom, Pounds</option>
                                                                                                                                                                                        <option value=\"USD\">United States Minor Outlying Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">United States of America, Dollars</option>
                                                                                                                                                                                        <option value=\"UYU\">Uruguay, Pesos</option>
                                                                                                                                                                                        <option value=\"USD\">US Virgin Islands, United States Dollars</option>
                                                                                                                                                                                        <option value=\"UZS\">Uzbekistan, Sums</option>
                                                                                                                                                                                        <option value=\"VUV\">Vanuatu, Vatu</option>
                                                                                                                                                                                        <option value=\"EUR\">Vatican City (The Holy See), Euro</option>
                                                                                                                                                                                        <option value=\"VEB\">Venezuela, Bolivares (expires 2008-Jun-30)</option>
                                                                                                                                                                                        <option value=\"VEF\">Venezuela, Bolivares Fuertes</option>
                                                                                                                                                                                        <option value=\"VND\">Viet Nam, Dong</option>
                                                                                                                                                                                        <option value=\"USD\">Virgin Islands (American), United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">Virgin Islands (British), United States Dollars</option>
                                                                                                                                                                                        <option value=\"USD\">Wake Island, United States Dollars</option>
                                                                                                                                                                                        <option value=\"XPF\">Wallis and Futuna Islands, Comptoirs Français du Pacifique Francs</option>
                                                                                                                                                                                        <option value=\"MAD\">Western Sahara, Morocco Dirhams</option>
                                                                                                                                                                                        <option value=\"YER\">Yemen, Rials</option>
                                                                                                                                                                                        <option value=\"ZMK\">Zambia, Kwacha</option>
                                                                                                                                                                                        <option value=\"ZWD\">Zimbabwe, Zimbabwe Dollars</option>
																						</select>
																					</td>
																				</tr>
																			</table>
																	</td>
																	<td valign=\"top\">

																		<table width=\"100%\"  cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
																			<tr>
																				<td colspan=\"2\">Please ensure that you have created this database prior to installtion. You can change table prefix to suit your needs.
																				The pre set examples will work fine for most installs.<br><br>
																				Next you need to add a user and password for the database to run as. We do not suggest using the root Mysql User for this.
																				</td>
																			</tr>
																		</table>

																	</td>
																</tr>
															</table>
															<br>																							
															<table>
																<tr>
																	<td>");
            if(is_array($errors)) {
                echo("Set up can not continue until the following errors are fixed:<br>");
                foreach($errors as $key=>$val) {
                    echo("<font color=\"red\">Error $key: ");
                    foreach($val as $k=>$v) {
                        echo("$k $v");
                    }
                    echo("</font><br>");
                }
            } else {
                echo("<input type=\"submit\" name=\"submit\" value=\"Upgrade\">");
            }
            echo("</td>
																</tr>
															</table>
															
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</form>	  	  
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
			<br><br>
			<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td height=\"51\" align=\"center\" background=\"../images/index41.gif\"></td>
				</tr><tr>
					<td height=\"48\" align=\"center\" background=\"../images/index42.gif\"><span class=\"text3\"><a> This software is distrubuted under the GNU General Public License</span></t
				</tr><tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

</body>
</html>");
        }
}

function resolveDocumentRoot() {
    $current_script = dirname($_SERVER['SCRIPT_NAME']);
    $current_path  = dirname($_SERVER['SCRIPT_FILENAME']);

   /* work out how many folders we are away from document_root
       by working out how many folders deep we are from the url.
       this isn't fool proof */
    $adjust = explode("/", $current_script);
    $adjust = count($adjust)-1;

   /* move up the path with ../ */
    $traverse = str_repeat("../", $adjust);
    $adjusted_path = sprintf("%s/%s", $current_path, $traverse);

   /* real path expands the ../'s to the correct folder names */
    return realpath($adjusted_path);
}

function get_server_name() {
    $default_server = $_SERVER['SERVER_NAME'];
    return $default_server;

}
#####################################
#		Check Lock					#
#####################################
function check_lock_file() {
    $lock_file = "../cache/lock";
    if (file_exists($lock_file)) {
        return true;
    } else {
        return false;
    }
}



#####################################
#		Check If File Exists		#
#####################################
function file_exists_incpath ($file) {
    $paths = explode(PATH_SEPARATOR, get_include_path());

    foreach ($paths as $path) {
    // Formulate the absolute path
        $fullpath = $path . DIRECTORY_SEPARATOR . $file;

        // Check it
        if (file_exists($fullpath)) {
            return true;
        }
    }

    return false;
}


#####################################
#		Check If File writes		#
#####################################
function check_write ($file) {
    if(is_writable($file)) {
        return true;
    } else {
        return false;
    }
}


#####################################
#		Generic error checking		#
#####################################
function error_check($error) {
    echo("<font color=\"red\"><b>Error: </b></font>$error</br>");
    exit;
}

#####################################
#		Generic error checking		#
#####################################
function validate($data) {
//print_r($data);

	/* check for Null all values are required */
    foreach($data as $key => $val) {
        if($val == "") {
            error_check("Missing field $key.<br>");
        }
    }

}
?>
