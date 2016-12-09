<?php
// Only show php errors
error_reporting(E_ERROR);
###############################
#        Lock Check           #
###############################
if(check_lock_file() ) {
    echo("<font color=\"red\">Set up has already completed! Some clean up needs to happen before you can run it again!</font>");
    exit;
    /* add code to clean up include file and remove any database settings so we can do a clean install */
}
    
###############################
#        Switch               #
###############################
$mode = $_POST['mode'];
switch ($mode){

############################
#        Install           #
############################
case "install":
        /* display page header and start graphics */
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n
<html>\n
<head>\n
    <title>MyIT Installer</title>\n
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n
    <link href=\"../css/default.css\" rel=\"stylesheet\" type=\"text/css\">\n

</head>\n
<body>\n
<center>\n
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n
    <tr>\n
        <td><img src=\"../images/logo.png\" alt=\"\" width=\"490\" height=\"114\"></td>\n
    </tr>\n
</table>\n
            
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n
    <tr>\n
        <td  background=\"../images/index03.gif\"><img src=\"../images/index03.gif\" alt=\"\" width=\"100%\" height=\"40\"></td>\n
    </tr><tr>\n
        <td align=\"center\">\n

            <table width=\"100%\" border=\"0\" cellpadding=\"20\" cellspacing=\"0\">\n
                <tr>\n
                    <td class=\"olotd\" align=\"center\">\n
                        
                        \n
                        <table width=\"800\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
                            <tr>\n
                                <td class=\"menuhead2\" width=\"100%\">&nbsp;MYIT CRM Installer</td>\n
                            </tr><tr>\n
                                <td class=\"menutd2\" colspan=\"2\">\n

                                    <table width=\"100%\"  class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
                                        <tr>
                                            <td>
                                                <table width=\"100%\"  class=\"menutd\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >\n
                                    ");    
        
                            $login_usr    = $_POST['display_name'];
                            $path2    = $_POST['default_site_name'];

                            /* write the need configs */
                            set_path($_POST);

        
        /* Load our new configs */
        
        require("../include/ADODB/adodb.inc.php");
        
        /* Create ADODB Connection */
        $db = &ADONewConnection('mysqli');

        $db->Connect($_POST['db_host'] ,$_POST['db_user'], $_POST['db_password']);
        if( $db->errorMsg() != '' ) {
            echo "There was an error connecting to the database: ".$db->errorMsg();
            die;
        }
//
//OBSOLETE CODE - REMOVED BY GEEVPC ON THE 21/7/11
//##################################
//# Create Database                #
//##################################
//        $q = "CREATE DATABASE IF NOT EXISTS ".$_POST['db_name'];
//        if(!$rs = $db->Execute($q)) {
//            echo("<tr>\n
//                    <td>Create Database ". $_POST['db_name'] ." </td>\n
//                    <td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg(). " </td>\n
//                </tr>\n");
//            die;
//        } else {
//            echo("<tr>\n
//                        <td>Create Database ".$_POST['db_name']."</td>\n
//                        <td><font color=\"green\"><b>OK</b></font></td>\n
//                    </tr>\n");
//        }
//
##################################
# Create New Connection          #
##################################
        $db->close();
        include("../conf.php");

        if( $db->errorMsg() != '' ) {
            echo "There Was an error connecting to the database: ".$db->errorMsg();
            die;
        }

    $prefix = $_POST['db_prefix'];
@define('PRFX', $prefix);
##################################
# Build Tables                   #
##################################
        /*include sql.php */
        include("sql.php");
        
##################################
# Add Admin                      #
##################################
        $q = "REPLACE INTO ".PRFX."TABLE_EMPLOYEE SET
            EMPLOYEE_LOGIN                =". $db->qstr( $login_usr          ).", 
            EMPLOYEE_FIRST_NAME            =". $db->qstr( $_POST['first_name']            ).",
            EMPLOYEE_LAST_NAME         =". $db->qstr( $_POST['last_name']             ).",
            EMPLOYEE_DISPLAY_NAME         =". $db->qstr( $_POST['display_name']          ).",
            EMPLOYEE_ADDRESS             =". $db->qstr( $_POST['address']               ).",
            EMPLOYEE_CITY                 =". $db->qstr( $_POST['city']                  ).",
            EMPLOYEE_STATE                 =". $db->qstr( $_POST['state']                 ).",
            EMPLOYEE_ZIP                 =". $db->qstr( $_POST['zip']                   ).",
            EMPLOYEE_TYPE                 =". $db->qstr( 4                              ).",
            EMPLOYEE_WORK_PHONE        =". $db->qstr( $_POST['work_phone']            ).",
            EMPLOYEE_HOME_PHONE        =". $db->qstr( $_POST['home_phone']            ).",
            EMPLOYEE_MOBILE_PHONE        =". $db->qstr( $_POST['mobile_phone']          ).",
            EMPLOYEE_STATUS              =". $db->qstr( 1                               ).",
            EMPLOYEE_PASSWD                =". $db->qstr( md5($_POST['default_password']) ).",
            EMPLOYEE_EMAIL                =". $db->qstr( $_POST['default_email']         );
            
        if(!$rs = $db->Execute($q) ) {
            echo("<tr>\n
                        <td>Create Default Admin</td>\n
                        <td><font color=\"red\"><b>Failed: </b>".$db->ErrorMsg()."</td>\n
                    </tr>\n");
        } else {
            echo("<tr>\n
                        <td>Create Default Admin</td>\n
                        <td><font color=\"green\"><b>OK</b></font></td>\n
                </tr>\n");
        }

##################################
# Add Company Information        #
##################################
                $cname = stripslashes($_POST['COMPANY_NAME']);
        $q = "REPLACE INTO ".PRFX."TABLE_COMPANY SET
                COMPANY_NAME            = ". $db->qstr( $cname      ).",
                COMPANY_ADDRESS         = ". $db->qstr( $_POST['COMPANY_ADDRESS']).", 
                COMPANY_CITY            = ". $db->qstr( $_POST['COMPANY_CITY']).", 
                COMPANY_STATE           = ". $db->qstr( $_POST['COMPANY_STATE']).",
                COMPANY_ZIP             = ". $db->qstr( $_POST['COMPANY_ZIP']).",
                COMPANY_COUNTRY         = ". $db->qstr( $_POST['COMPANY_COUNTRY']).",
                COMPANY_PHONE           = ". $db->qstr( $_POST['COMPANY_PHONE']).",
                COMPANY_MOBILE          = ". $db->qstr( $_POST['COMPANY_MOBILE']).",
                COMPANY_EMAIL           = ". $db->qstr( $_POST['COMPANY_EMAIL']).",
                COMPANY_CURRENCY_CODE   = ". $db->qstr( $_POST['COMPANY_CURRENCY_CODE']).",
                COMPANY_CURRENCY_SYMBOL = ". $db->qstr( $_POST['COMPANY_CURRENCY_SYMBOL']).",
                COMPANY_DATE_FORMAT     = ". $db->qstr( $_POST['DATE_FORMAT']).",
                COMPANY_FAX             = ". $db->qstr( $_POST['COMPANY_FAX']) ;

        if(!$rs = $db->Execute($q)) {
            echo("<tr>\n
                    <td>Adding Company Information</td>\n
                    <td><font color=\"red\"><b>Failed</b></font> ".$db->ErrorMsg()."</td>\n
                </tr>\n");
        } else {
            echo("<tr>\n
                        <td>Adding Company Information</td>\n
                        <td><font color=\"green\"><b>OK</b></font></td>\n
                    <tr>\n");
        }

##################################
# Completed                      #
##################################
if($error_flag == true) {
    /* error can not complete the install */
    echo("<tr>\n
                <td colspan=\"2\">There where errors during the install. Your CRM is not enabled and needs to be reinstalled. Please remove the Database
                and reinstall. If the errors continue please submit a bug report at.</td>\n
            </tr>\n");
} else {
        /* create lock file */
        if(!touch("../cache/lock")){
            echo("<tr><td colspan=\"2\"><font color=\"red\">Failed to create lock file. Please create a file name lock and put it in the cache folder !!</font></td></tr>");
        }

        /* done */
        
        echo("<tr>\n<td colspan=\"2\"><font size=\+2 color=\"red\">Installation was successful.</font>
                <br><br>
                There are still a few steps that need to be completed.<br>
                1. You need to move or rename the install directory. We recommend moving it to a location that is not accessible by your web server
                    this way if you need to reinstall the CRM you can move the directory back. You will not be able to login until this directory is removed.<br>
                2. You need to <a href=\"$path2\">login as the admin</a> and finish setting up the CRM by editing the settings in the Control Center.
                <br><br>
                The Admin login is: ".$login_usr ." and the password you supplied in the previous page.<br><br>
                Where to find help:<br>
                The user Documentation is at <a href=\"http://wiki.myitcrm.com\">http://wiki.myitcrm.com</a><br>
                Bug/Feature Reporting is at <a href=\"http://forum.myitcrm.com\">Forum Bug/Feature Requests</a><br>

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
#        Default               #
################################
default: 
$default_path = resolveDocumentRoot();
$default_server = get_server_name();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html>
<head>
    <title>MYIT CRM Installer</title>
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
        <td><img src=\"../images/logo.png\" alt=\"\" width=\"490\" height=\"114\"></td>
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
            
            
            <table width=\"800\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
                <tr>
                    <td class=\"menuhead2\" width=\"100%\">&nbsp;MyIT CRM Installer</td>
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
                                                                    <td align=\"left\">
                                                                        You need to set the config file conf.php to be writable by your webserver for the install after that you must make them read only by your webserver.
                                                                        The file log/access.log needs to be writable by the web server. The cache directory needs to be writable by the server.
                                                                    </td>
                                                                </tr><tr>
                                                                
                                                                    <td align=\"left\">Main Config Writable ");
                                                                        if(!check_write ('../conf.php')) {
                                                                            echo("<font color=\"red\">../conf.php is not writable stopping</font>");
                                                                            $errors[] = array('../conf.php'=>'Not Writable');
                                                                        } else {
                                                                            echo("<font color=\"green\"><b>OK</b>");
                                                                        }
                                                                    echo("</td>
                                                                </tr><tr>
                                                                
                                                                    <td align=\"left\">Cache Folder ");
                                                                        if(!check_write ('../cache')) {
                                                                            echo("<font color=\"red\">../cache is not writable stopping.</font>");
                                                                            $errors[] = array('../cache'=>'Not Writable');
                                                                        } else {
                                                                            echo("<font color=\"green\"><b>OK</b>");
                                                                        }
                                                                    echo( "</td>
                                                                
                                                                </tr><tr>
                                                                
                                                                    <td align=\"left\">Access Log ");
                                                                        if(!check_write ('../log/access.log')) {
                                                                            echo("<font color=\"red\">../log/access.log is not writable stopping.</font>");
                                                                            $errors[] = array('../log/access.log'=>'Not Writable');
                                                                        } else {
                                                                            echo("<font color=\"green\"><b>OK</b>");
                                                                        }
                                                                    echo("</td>
                                                                    
                                                                </tr>
                                                            <!-- End of File Checks -->
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
                                                            <table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
                                                                <tr>
                                                                    <td valign=\"top\" width=\"60%\">

                                                                        <table>
                                                                            <tr>

                                                                            <td align=\"right\" width=\"140\">Database User:</td>
                                                                            <td align=\"left\"><input type=\"text\" size=\"20\" name=\"db_user\" value=\"username\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Database Password:</td>
                                                                                <td align=\"left\"><input type=\"password\" size=\"20\" name=\"db_password\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Database Host:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"db_host\" value=\"localhost\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Database Name:</td>
                                                                                <td align=\"left\">
                                                                                    <input type=\"text\" size=\"30\" name=\"db_name\" value=\"\" class=\"olotd5\">
                                                                                </td>
                                                                            </tr><tr>
                                                                                    <td align=\"right\" width=\"140\">Table Prefix</td>
                                                                                    <td align=\"left\">
                                                                                        <input type=\"text\" size=\"30\" name=\"db_prefix\" value=\"MYIT_\" class=\"olotd5\">
                                                                                    </td>
                                                                                </tr><tr>
                                                                                    <td align=\"right\" width=\"140\">Preferred Language</td>
                                                                                    <td align=\"left\">
                                                                                        <select name=\"language\" size=\"1\" >
                                                                                            <option value=\"english.xml\" SELECTED>English-UK
                                                                                            <option value=\"english_US.xml\">English-US
                                                                                                                                                                                        <option value=\"portuguese.xml\">Portuguese
                                                                                                                                                                                    
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                                                                                                <tr>
                                                                                    <td align=\"right\" width=\"140\">Date Format</td>
                                                                                    <td align=\"left\">
                                                                                        <select name=\"DATE_FORMAT\" size=\"1\" >
                                                                                            <option value=\"%d/%m/%Y\" SELECTED>d/m/Y
                                                                                            <option value=\"%m/%d/%Y\">m/d/Y
                                                                                                                                                                                    
                                                                                        </select>
                                                                                    </td>
                                                                                </tr><tr>
                                                                                    <td align=\"right\" width=\"140\">Currency Symbol</td>
                                                                                    <td align=\"left\">
                                                                                        <select name=\"COMPANY_CURRENCY_SYMBOL\" >
                                                                                            <option value=\"$\" SELECTED>$ - Dollars
                                                                                            <option value=\"£\">£ - Pounds
                                                                                                                                                                                        <option value=\"€\">€ - Euros

                                                                                        </select>
                                                                                    </td>
                                                                                </tr><tr>
                                                                                    <td align=\"right\" width=\"140\">Currency</td>
                                                                                    <td align=\"left\" size=\"30\">
                                                                                        <select name=\"COMPANY_CURRENCY_CODE\" size=\"1\" >
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
                                                                    
                                                                </tr>
                                                            </table>
                                                            <br>

                                                            <!-- Default User -->
                                                            <b>Administrator</b>
                                                            <table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
                                                                <tr>
                                                                    <td valign=\"top\" width=\"60%\">

                                                                        <table>
                                                                            <tr>
                                                                                <td align=\"right\" width=\"140\">First Name:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"first_name\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Last Name:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"last_name\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Login Name:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"display_name\" class=\"olotd5\"></td>
                                                                            </tr>
                                                                                                                                                        <tr>
                                                                                <td align=\"right\" width=\"140\">Password:</td>
                                                                                <td align=\"left\"><input type=\"password\" size=\"20\" name=\"default_password\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Re-enter Password:</td>
                                                                                <td align=\"left\"><input type=\"password\" size=\"20\" name=\"default_password2\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Address:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"address\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">City:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"city\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">State:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"state\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Zip:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"zip\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Home Phone:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"home_phone\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Work Phone:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"work_phone\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Mobile Phone:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"mobile_phone\" class=\"olotd5\"></td>
                                                                            </tr>
                                                                                <td align=\"right\" width=\"140\">Email:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"40\" name=\"default_email\" class=\"olotd5\"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td valign=\"top\">
                                                                        <table width=\"100%\"  cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
                                                                            <tr>
                                                                                <td>
                                                                                        Add the default Administrator. This user will have full permissions to the program and database. This user will also be the Manager of all work orders and employee. IE Full Permissions.
                                                                                        The login will be created bu using the first initial of the first name and the full last name. 
                                                                                </td>    
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <br>


                                                            <b>Company Information</b>
                                                            <table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
                                                                <tr>
                                                                    <td valign=\"top\" width=\"60%\">
                                                                        <table>
                                                                            <tr>
                                                                                <td align=\"right\" width=\"140\">Company Name:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_NAME\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                                                                                        <td></td>
                                                                                                                                                            <td align=\"left\">
                                                                                                                                                                <br><input type=radio name=copy value=\"yes\" onclick=\"data_copy()\";>Copy Administrator data to Business data?
                                                                                                                                                                <br><input type=hidden name=copy value=\"no\" onclick=\"data_copy()\";>
                                                                                                                                                            </td>
                                                                                                                                                        </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company Address:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_ADDRESS\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company City:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_CITY\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company State:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_STATE\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company Zip:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_ZIP\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company Country:</td>
                                                                                <td align=\"left\"><select name=\"COMPANY_COUNTRY\" class=\"olotd5\">
                                                                                                <option value=\"00\"  selected  >--Select--</option> 
                                                                                                              <option value=\"AF\"  >Afghanistan</option>
                                                                                                              <option value=\"AL\"  >Albania</option>
                                                                                                              <option value=\"DZ\"  >Algeria</option>
                                                                                                              <option value=\"AS\"  >American Samoa</option>
                                                                                                              <option value=\"AD\"  >Andorra</option>
                                                                                                              <option value=\"AO\"  >Angola</option>
                                                                                                              <option value=\"AI\"  >Anguilla</option>
                                                                                                              <option value=\"AQ\"  >Antarctica</option>
                                                                                                              <option value=\"AG\"  >Antigua and Barbuda</option>
                                                                                                              <option value=\"AR\"  >Argentina</option>
                                                                                                              <option value=\"AM\"  >Armenia</option>
                                                                                                              <option value=\"AW\"  >Aruba</option>
                                                                                                              <option value=\"AU\"  >Australia</option>
                                                                                                              <option value=\"AT\"  >Austria</option>
                                                                                                              <option value=\"AZ\"  >Azerbaijan</option>
                                                                                                              <option value=\"AP\"  >Azores</option>
                                                                                                              <option value=\"BS\"  >Bahamas</option>
                                                                                                              <option value=\"BH\"  >Bahrain</option>
                                                                                                              <option value=\"BD\"  >Bangladesh</option>
                                                                                                              <option value=\"BB\"  >Barbados</option>
                                                                                                              <option value=\"BY\"  >Belarus</option>
                                                                                                              <option value=\"BE\"  >Belgium</option>
                                                                                                              <option value=\"BZ\"  >Belize</option>
                                                                                                              <option value=\"BJ\"  >Benin</option>
                                                                                                              <option value=\"BM\"  >Bermuda</option>
                                                                                                              <option value=\"BT\"  >Bhutan</option>
                                                                                                              <option value=\"BO\"  >Bolivia</option>
                                                                                                              <option value=\"BA\"  >Bosnia And Herzegowina</option>
                                                                                                              <option value=\"XB\"  >Bosnia-Herzegovina</option>
                                                                                                              <option value=\"BW\"  >Botswana</option>
                                                                                                              <option value=\"BV\"  >Bouvet Island</option>
                                                                                                              <option value=\"BR\"  >Brazil</option>
                                                                                                              <option value=\"IO\"  >British Indian Ocean Territory</option>
                                                                                                              <option value=\"VG\"  >British Virgin Islands</option>
                                                                                                              <option value=\"BN\"  >Brunei Darussalam</option>
                                                                                                              <option value=\"BG\"  >Bulgaria</option>
                                                                                                              <option value=\"BF\"  >Burkina Faso</option>
                                                                                                              <option value=\"BI\"  >Burundi</option>
                                                                                                              <option value=\"KH\"  >Cambodia</option>
                                                                                                              <option value=\"CM\"  >Cameroon</option>
                                                                                                              <option value=\"CA\"  >Canada</option>
                                                                                                              <option value=\"CV\"  >Cape Verde</option>
                                                                                                              <option value=\"KY\"  >Cayman Islands</option>
                                                                                                              <option value=\"CF\"  >Central African Republic</option>
                                                                                                              <option value=\"TD\"  >Chad</option>
                                                                                                              <option value=\"CL\"  >Chile</option>
                                                                                                              <option value=\"CN\"  >China</option>
                                                                                                              <option value=\"CX\"  >Christmas Island</option>
                                                                                                              <option value=\"CC\"  >Cocos (Keeling) Islands</option>
                                                                                                              <option value=\"CO\"  >Colombia</option>
                                                                                                              <option value=\"KM\"  >Comoros</option>
                                                                                                              <option value=\"CG\"  >Congo</option>
                                                                                                              <option value=\"CD\"  >Congo, The Democratic Republic O</option>
                                                                                                              <option value=\"CK\"  >Cook Islands</option>
                                                                                                              <option value=\"XE\"  >Corsica</option>
                                                                                                              <option value=\"CR\"  >Costa Rica</option>
                                                                                                              <option value=\"CI\"  >Cote d` Ivoire (Ivory Coast)</option>
                                                                                                              <option value=\"HR\"  >Croatia</option>
                                                                                                              <option value=\"CU\"  >Cuba</option>
                                                                                                              <option value=\"CY\"  >Cyprus</option>
                                                                                                              <option value=\"CZ\"  >Czech Republic</option>
                                                                                                              <option value=\"DK\"  >Denmark</option>
                                                                                                              <option value=\"DJ\"  >Djibouti</option>
                                                                                                              <option value=\"DM\"  >Dominica</option>
                                                                                                              <option value=\"DO\"  >Dominican Republic</option>
                                                                                                              <option value=\"TP\"  >East Timor</option>
                                                                                                              <option value=\"EC\"  >Ecuador</option>
                                                                                                              <option value=\"EG\"  >Egypt</option>
                                                                                                              <option value=\"SV\"  >El Salvador</option>
                                                                                                              <option value=\"GQ\"  >Equatorial Guinea</option>
                                                                                                              <option value=\"ER\"  >Eritrea</option>
                                                                                                              <option value=\"EE\"  >Estonia</option>
                                                                                                              <option value=\"ET\"  >Ethiopia</option>
                                                                                                              <option value=\"FK\"  >Falkland Islands (Malvinas)</option>
                                                                                                              <option value=\"FO\"  >Faroe Islands</option>
                                                                                                              <option value=\"FJ\"  >Fiji</option>
                                                                                                              <option value=\"FI\"  >Finland</option>
                                                                                                              <option value=\"FR\"  >France (Includes Monaco)</option>
                                                                                                              <option value=\"FX\"  >France, Metropolitan</option>
                                                                                                              <option value=\"GF\"  >French Guiana</option>
                                                                                                              <option value=\"PF\"  >French Polynesia</option>
                                                                                                              <option value=\"TA\"  >French Polynesia (Tahiti)</option>
                                                                                                              <option value=\"TF\"  >French Southern Territories</option>
                                                                                                              <option value=\"GA\"  >Gabon</option>
                                                                                                              <option value=\"GM\"  >Gambia</option>
                                                                                                              <option value=\"GE\"  >Georgia</option>
                                                                                                              <option value=\"DE\"  >Germany</option>
                                                                                                              <option value=\"GH\"  >Ghana</option>
                                                                                                              <option value=\"GI\"  >Gibraltar</option>
                                                                                                              <option value=\"GR\"  >Greece</option>
                                                                                                              <option value=\"GL\"  >Greenland</option>
                                                                                                              <option value=\"GD\"  >Grenada</option>
                                                                                                              <option value=\"GP\"  >Guadeloupe</option>
                                                                                                              <option value=\"GU\"  >Guam</option>
                                                                                                              <option value=\"GT\"  >Guatemala</option>
                                                                                                              <option value=\"GN\"  >Guinea</option>
                                                                                                              <option value=\"GW\"  >Guinea-Bissau</option>
                                                                                                              <option value=\"GY\"  >Guyana</option>
                                                                                                              <option value=\"HT\"  >Haiti</option>
                                                                                                              <option value=\"HM\"  >Heard And Mc Donald Islands</option>
                                                                                                              <option value=\"VA\"  >Holy See (Vatican City State)</option>
                                                                                                              <option value=\"HN\"  >Honduras</option>
                                                                                                              <option value=\"HK\"  >Hong Kong</option>
                                                                                                              <option value=\"HU\"  >Hungary</option>
                                                                                                              <option value=\"IS\"  >Iceland</option>
                                                                                                              <option value=\"IN\"  >India</option>
                                                                                                              <option value=\"ID\"  >Indonesia</option>
                                                                                                              <option value=\"IR\"  >Iran</option>
                                                                                                              <option value=\"IQ\"  >Iraq</option>
                                                                                                              <option value=\"IE\"  >Ireland</option>
                                                                                                              <option value=\"EI\"  >Ireland (Eire)</option>
                                                                                                              <option value=\"IL\"  >Israel</option>
                                                                                                              <option value=\"IT\"  >Italy</option>
                                                                                                              <option value=\"JM\"  >Jamaica</option>
                                                                                                              <option value=\"JP\"  >Japan</option>
                                                                                                              <option value=\"JO\"  >Jordan</option>
                                                                                                              <option value=\"KZ\"  >Kazakhstan</option>
                                                                                                              <option value=\"KE\"  >Kenya</option>
                                                                                                              <option value=\"KI\"  >Kiribati</option>
                                                                                                              <option value=\"KP\"  >Korea, Democratic People'S Repub</option>
                                                                                                              <option value=\"KW\"  >Kuwait</option>
                                                                                                              <option value=\"KG\"  >Kyrgyzstan</option>
                                                                                                              <option value=\"LA\"  >Laos</option>
                                                                                                              <option value=\"LV\"  >Latvia</option>
                                                                                                              <option value=\"LB\"  >Lebanon</option>
                                                                                                              <option value=\"LS\"  >Lesotho</option>
                                                                                                              <option value=\"LR\"  >Liberia</option>
                                                                                                              <option value=\"LY\"  >Libya</option>
                                                                                                              <option value=\"LI\"  >Liechtenstein</option>
                                                                                                              <option value=\"LT\"  >Lithuania</option>
                                                                                                              <option value=\"LU\"  >Luxembourg</option>
                                                                                                              <option value=\"MO\"  >Macao</option>
                                                                                                              <option value=\"MK\"  >Macedonia</option>
                                                                                                              <option value=\"MG\"  >Madagascar</option>
                                                                                                              <option value=\"ME\"  >Madeira Islands</option>
                                                                                                              <option value=\"MW\"  >Malawi</option>
                                                                                                              <option value=\"MY\"  >Malaysia</option>
                                                                                                              <option value=\"MV\"  >Maldives</option>
                                                                                                              <option value=\"ML\"  >Mali</option>
                                                                                                              <option value=\"MT\"  >Malta</option>
                                                                                                              <option value=\"MH\"  >Marshall Islands</option>
                                                                                                              <option value=\"MQ\"  >Martinique</option>
                                                                                                              <option value=\"MR\"  >Mauritania</option>
                                                                                                              <option value=\"MU\"  >Mauritius</option>
                                                                                                              <option value=\"YT\"  >Mayotte</option>
                                                                                                              <option value=\"MX\"  >Mexico</option>
                                                                                                              <option value=\"FM\"  >Micronesia, Federated States Of</option>
                                                                                                              <option value=\"MD\"  >Moldova, Republic Of</option>
                                                                                                              <option value=\"MC\"  >Monaco</option>
                                                                                                              <option value=\"MN\"  >Mongolia</option>
                                                                                                              <option value=\"MS\"  >Montserrat</option>
                                                                                                              <option value=\"MZ\"  >Mozambique</option>
                                                                                                              <option value=\"MM\"  >Myanmar (Burma)</option>
                                                                                                              <option value=\"NA\"  >Namibia</option>
                                                                                                              <option value=\"NR\"  >Nauru</option>
                                                                                                              <option value=\"NP\"  >Nepal</option>
                                                                                                              <option value=\"NL\"  >Netherlands</option>
                                                                                                              <option value=\"AN\"  >Netherlands Antilles</option>
                                                                                                              <option value=\"NC\"  >New Caledonia</option>
                                                                                                              <option value=\"NZ\"  >New Zealand</option>
                                                                                                              <option value=\"NI\"  >Nicaragua</option>
                                                                                                              <option value=\"NE\"  >Niger</option>
                                                                                                              <option value=\"NG\"  >Nigeria</option>
                                                                                                              <option value=\"NU\"  >Niue</option>
                                                                                                              <option value=\"NF\"  >Norfolk Island</option>
                                                                                                              <option value=\"MP\"  >Northern Mariana Islands</option>
                                                                                                              <option value=\"NO\"  >Norway</option>
                                                                                                              <option value=\"OM\"  >Oman</option>
                                                                                                              <option value=\"PK\"  >Pakistan</option>
                                                                                                              <option value=\"PW\"  >Palau</option>
                                                                                                              <option value=\"PS\"  >Palestinian Territory, Occupied</option>
                                                                                                              <option value=\"PA\"  >Panama</option>
                                                                                                              <option value=\"PG\"  >Papua New Guinea</option>
                                                                                                              <option value=\"PY\"  >Paraguay</option>
                                                                                                              <option value=\"PE\"  >Peru</option>
                                                                                                              <option value=\"PH\"  >Philippines</option>
                                                                                                              <option value=\"PN\"  >Pitcairn</option>
                                                                                                              <option value=\"PL\"  >Poland</option>
                                                                                                              <option value=\"PT\"  >Portugal</option>
                                                                                                              <option value=\"PR\"  >Puerto Rico</option>
                                                                                                              <option value=\"QA\"  >Qatar</option>
                                                                                                              <option value=\"RE\"  >Reunion</option>
                                                                                                              <option value=\"RO\"  >Romania</option>
                                                                                                              <option value=\"RU\"  >Russian Federation</option>
                                                                                                              <option value=\"RW\"  >Rwanda</option>
                                                                                                              <option value=\"KN\"  >Saint Kitts And Nevis</option>
                                                                                                              <option value=\"SM\"  >San Marino</option>
                                                                                                              <option value=\"ST\"  >Sao Tome and Principe</option>
                                                                                                              <option value=\"SA\"  >Saudi Arabia</option>
                                                                                                              <option value=\"SN\"  >Senegal</option>
                                                                                                              <option value=\"XS\"  >Serbia-Montenegro</option>
                                                                                                              <option value=\"SC\"  >Seychelles</option>
                                                                                                              <option value=\"SL\"  >Sierra Leone</option>
                                                                                                              <option value=\"SG\"  >Singapore</option>
                                                                                                              <option value=\"SK\"  >Slovak Republic</option>
                                                                                                              <option value=\"SI\"  >Slovenia</option>
                                                                                                              <option value=\"SB\"  >Solomon Islands</option>
                                                                                                              <option value=\"SO\"  >Somalia</option>
                                                                                                              <option value=\"ZA\"  >South Africa</option>
                                                                                                              <option value=\"GS\"  >South Georgia And The South Sand</option>
                                                                                                              <option value=\"KR\"  >South Korea</option>
                                                                                                              <option value=\"ES\"  >Spain</option>
                                                                                                              <option value=\"LK\"  >Sri Lanka</option>
                                                                                                              <option value=\"NV\"  >St. Christopher and Nevis</option>
                                                                                                              <option value=\"SH\"  >St. Helena</option>
                                                                                                              <option value=\"LC\"  >St. Lucia</option>
                                                                                                              <option value=\"PM\"  >St. Pierre and Miquelon</option>
                                                                                                              <option value=\"VC\"  >St. Vincent and the Grenadines</option>
                                                                                                              <option value=\"SD\"  >Sudan</option>
                                                                                                              <option value=\"SR\"  >Suriname</option>
                                                                                                              <option value=\"SJ\"  >Svalbard And Jan Mayen Islands</option>
                                                                                                              <option value=\"SZ\"  >Swaziland</option>
                                                                                                              <option value=\"SE\"  >Sweden</option>
                                                                                                              <option value=\"CH\"  >Switzerland</option>
                                                                                                              <option value=\"SY\"  >Syrian Arab Republic</option>
                                                                                                              <option value=\"TW\"  >Taiwan</option>
                                                                                                              <option value=\"TJ\"  >Tajikistan</option>
                                                                                                              <option value=\"TZ\"  >Tanzania</option>
                                                                                                              <option value=\"TH\"  >Thailand</option>
                                                                                                              <option value=\"TG\"  >Togo</option>
                                                                                                              <option value=\"TK\"  >Tokelau</option>
                                                                                                              <option value=\"TO\"  >Tonga</option>
                                                                                                              <option value=\"TT\"  >Trinidad and Tobago</option>
                                                                                                              <option value=\"XU\"  >Tristan da Cunha</option>
                                                                                                              <option value=\"TN\"  >Tunisia</option>
                                                                                                              <option value=\"TR\"  >Turkey</option>
                                                                                                              <option value=\"TM\"  >Turkmenistan</option>
                                                                                                              <option value=\"TC\"  >Turks and Caicos Islands</option>
                                                                                                              <option value=\"TV\"  >Tuvalu</option>
                                                                                                              <option value=\"UG\"  >Uganda</option>
                                                                                                              <option value=\"UA\"  >Ukraine</option>
                                                                                                              <option value=\"AE\"  >United Arab Emirates</option>
                                                                                                              <option value=\"UK\"  >United Kingdom</option>
                                                                                                              <option value=\"US\"  >United States</option>
                                                                                                              <option value=\"UM\"  >United States Minor Outlying Isl</option>
                                                                                                              <option value=\"UY\"  >Uruguay</option>
                                                                                                              <option value=\"UZ\"  >Uzbekistan</option>
                                                                                                              <option value=\"VU\"  >Vanuatu</option>
                                                                                                              <option value=\"XV\"  >Vatican City</option>
                                                                                                              <option value=\"VE\"  >Venezuela</option>
                                                                                                              <option value=\"VN\"  >Vietnam</option>
                                                                                                              <option value=\"VI\"  >Virgin Islands (U.S.)</option>
                                                                                                              <option value=\"WF\"  >Wallis and Furuna Islands</option>
                                                                                                              <option value=\"EH\"  >Western Sahara</option>
                                                                                                              <option value=\"WS\"  >Western Samoa</option>
                                                                                                              <option value=\"YE\"  >Yemen</option>
                                                                                                              <option value=\"YU\"  >Yugoslavia</option>
                                                                                                              <option value=\"ZR\"  >Zaire</option>
                                                                                                              <option value=\"ZM\"  >Zambia</option>
                                                                                                              <option value=\"ZW\"  >Zimbabwe</option>
                                                                                                        </select>
                                                                            </td>
                                                                            </tr>
                                                                                <td align=\"right\" width=\"140\">Company Phone:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_PHONE\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company Mobile:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_MOBILE\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company Fax:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_FAX\" class=\"olotd5\"></td>
                                                                            </tr><tr>
                                                                                <td align=\"right\" width=\"140\">Company Email:</td>
                                                                                <td align=\"left\"><input type=\"text\" size=\"20\" name=\"COMPANY_EMAIL\" class=\"olotd5\"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td valign=\"top\">
                                                                        <table width=\"100%\"  cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
                                                                            <tr>
                                                                                <td>
                                                                                This is your Company's contact information as it will show up on invoices and billing.</td>
                                                                                </td>
                                                                            </tr>
                                                                        </table>    
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                            <!-- Site Information -->
                                                            <br>
                                                            <b>Web Site Information</b>
                                                            <table class=\"olotable\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" >
                                                                <tr>
                                                                    <td valign=\"top\" width=\"60%\">
                                                                        <table>
                                                                            <tr>
                                                                                <td width=\"140\">Full Path:</td>
                                                                                <td><input type=\"text\" size=\"40\" name=\"default_path\" value=\"".$default_path."/\"class=\"olotd5\"></td>
                                                                            </tr>
                                                                                <td width=\"140\">Site Name</td>
                                                                                <td><input type=\"text\" size=\"40\" name=\"default_site_name\" value=\"http://".$default_server."/\" class=\"olotd5\"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td valign=\"top\">
                                                                        <table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">
                                                                            <tr>
                                                                                <td>
                                                                                    You need to give the full path to where the site lives. Do not include trailing / but include the directory name myitcrm. IE.. If your site 
                                                                                    lives at /var/www/htdocs/myitcrm use: /var/www/htdocs/myitcrm
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                            <br>
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
                                                                        echo("<input type=\"submit\" name=\"submit\" value=\"Install\">");
                                                                    }
                                                                echo("</td>
                                                                </tr>
                                                            </table>
                                                            </form>
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
                    <td height=\"48\" align=\"center\" background=\"../images/index42.gif\"><span class=\"text3\"><a> This software is distributed under the GNU General Public License</span></td>
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
#        Check Lock                 #
#####################################
function check_lock_file(){
    $lock_file = "../cache/lock";
    if (file_exists($lock_file)) {
        return true;
    } else {
        return false;
    }
}



#####################################
#        Check If File Exists       #
#####################################
function file_exists_incpath ($file){
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
#        Check If File writes       #
#####################################
function check_write ($file) {
    if(is_writable($file)) {
        return true;
    } else {
        return false;
    }    
}

#####################################
#        Set Path                   #
#####################################
function set_path()
{

    //$install_date = date("M d Y h:i:s A" ,time()); //not used
    $filename = '../conf.php';
    $path2 = 'default_site_name';
    //rename(".$filename.'../install, '.$install_date.");
$content = "<?php
#############################################################
# MyIT CRM
# index.php
# PLEASE DON'T CHANGE ANY OF THESE VALUES UNLESS YOU KNOW
# WHAT YOU ARE DOING....
#############################################################
\n
@define('SEP','/');
define('QWCRM_PHYSICAL_PATH', __DIR__);
@define('WWW_ROOT','".$_POST['default_site_name']."');
@define('IMG_URL',WWW_ROOT.'images');
define('INCLUDES_DIR',          'includes/'                 ); 
@define('SMARTY_DIR',INCLUDES_DIR.'SMARTY'.SEP);
define('ACTIVITY_LOG',          LOGS.'activity.log'         );
@define('THEME_LANGUAGE','".$_POST['language']."');


/* Database Settings */
@define('PRFX',    '".$_POST['db_prefix']."');
@define('DB_HOST','".$_POST['db_host']."');
@define('DB_USER','".$_POST['db_user']."');
@define('DB_PASS','".$_POST['db_password']."');
@define('DB_NAME','".$_POST['db_name']."');

// MySQL Database Settings
\$DB_HOST = \"".$_POST['db_host']."\" ;
\$DB_USER = \"".$_POST['db_user']."\" ;
\$DB_PASS = \"".$_POST['db_password']."\" ;
\$DB_NAME = \"".$_POST['db_name']."\" ;

\$link = mysqli_connect( \$DB_HOST, \$DB_USER, \$DB_PASS );

/* Load required Includes */
require(INCLUDES_DIR.'session.php');
require(INCLUDES_DIR.'auth.php');

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDES_DIR.'SMARTY'.SEP);
require('Smarty.class.php');

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDES_DIR.'ADODB'.SEP);
require('adodb.inc.php');

/* Load smarty template engine */
global \$smarty;
\$smarty = new Smarty;
\$smarty->template_dir           = THEME_TEMPLATE_DIR;
\$smarty->compile_dir            = SMARTY_COMPILE_DIR;
\$smarty->config_dir    = SMARTY_DIR.'configs';
\$smarty->cache_dir    = SMARTY_DIR.'cache';
\$smarty->load_filter('output','trimwhitespace');

\$strKey = 'kcmp7n2permbtr0dqebme6mpejhn3ki';

/* create adodb database connection */
\$db = &ADONewConnection('mysqli');
\$db->Connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);


\n";

    if (is_writable($filename)){
        if (!$handle = fopen($filename, 'w')){
            error_check("Cannot open file: $filename");
        }

        if (fwrite($handle, $content) === FALSE) {
            error_check("Cannot write to file: $filename");
        }
        fclose($handle);
    } else {
        error_check("The file $filename is not writable");
    }

}

#####################################
#        Generic error checking     #
#####################################
function error_check($error)
{
    echo("<font color=\"red\"><b>Error: </b></font>$error</br>");
    exit;
}

#####################################
#        Generic error checking     #
#####################################
function validate($data)
{
    //print_r($data);

    /* check for Null all values are required */
    foreach($data as $key => $val) {
        if($val == "") {
            error_check("Missing field $key.<br>");
        }
    }

    /* Check that passwords match for administrator */
    if($data['default_password'] != $data['default_password2']) {
        error_check("Administrators Passwords do not match.</br>");
    }
}
