<!-- Begin password.tpl-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
    <head>
        <title>Password Reset</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="css/default.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        {include file="../js/password.js"}
        <center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                
                <tr>
                    <td>
                        <img src="images/logo.jpg" alt="" height="114">
                    </td>
                </tr>
            </table>
            <table width="100%"  border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td colspan="3" >
                        <img src="images/index03.gif" alt="" width="100%" height="40">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            &nbsp;
                        </p>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <table class="olotable"  border="0" align="center">
                            <tr>
                                <td class="olohead">
                                    &nbsp;Password Resetting
                                </td>
                            </tr>
                            <tr>
                                <td class="olotd">
                                    <table  cellspacing="5" border="0" cellpadding="5" align="center">
                                        <tr>
                                            <td>
                                                <form method="POST" action="password.php" {literal} onsubmit="try  { var myValidator = validate_submit; } catch(e) { return true; } return myValidator(this);"{/literal} >
                                                    <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                                                        <tr>
                                                            <td align="right">
                                                                Email Address
                                                            </td>
                                                            <td>
                                                                <input type="text" name="employee_email" id="employee_email" size="50">
                                                            </td>
                                                        </tr>                                                        
                                                       
                                                        <tr>
                                                            <td colspan="2" align="right">
                                                                <b><font color="RED" >There's</font></b><b><font color="RED" ><input type="text" name="human" id="human" size ="2"> months in a Year. (AntiSpam Test)</font></b>
                                                            </td>
                                                        </tr>
                                                        <tr align="center">
                                                            <td colspan="2">
                                                                <input class="olotd5" type="submit" name="submit"  value="Reset Password">
                                                            </td>
                                                        </tr>
                                                        { if $error_msg != "" }
                                                        <tr>
                                                            <td colspan="2" class="error">
                                                                {$error_msg}
                                                            </td>
                                                        </tr>
							{ /if}

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
        </center>
        
    </body>
</html>
