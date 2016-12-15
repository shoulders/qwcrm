<!-- password_reset.tpl-->

<!-- if password is reset whilst someone is logged in, force loggout -->
{include file='user/password_reset.js'}

<table class="olotable"  border="0" align="center">
    <tr>
        <td class="olohead">&nbsp;Password Resetting</td>
    </tr>
    <tr>
        <td class="olotd">
            <table  cellspacing="5" border="0" cellpadding="5" align="center">
                <tr>
                    <td>
                        <form method="POST" action="password.php" {literal} onsubmit="try  { var myValidator = validate_submit; } catch(e) { return true; } return myValidator(this);"{/literal} >
                            <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                                <tr>
                                    <td align="right">Email Address</td>
                                    <td><input type="text" name="employee_email" id="employee_email" size="50"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right"><b><font color="RED" >There's</font></b><b><font color="RED" ><input type="text" name="human" id="human" size ="2"> months in a Year. (AntiSpam Test)</font></b></td>
                                </tr>
                                <tr align="center">
                                    <td colspan="2"><input class="olotd5" type="submit" name="submit"  value="Reset Password"></td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>