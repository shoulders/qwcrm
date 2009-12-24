<!-- Begin submit.tpl--><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
    <head>
        <title>Submit a Support Request</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="css/default.css" rel="stylesheet" type="text/css">
    </head>
    <body>
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
                    <td align="center">
                        <!-- Start of new dashboard front page -->
                        <table class="olotable" width="75%" align="left" border ="1" cellpadding="5" cellspacing="5" >
                            <tr>
                                <td class="olohead" colspan="2">
                                    Submit your support request form
                                </td>
                            </tr>
                            <tr>
                                <td class="olotd5">
                                    <img src="images/request.png" alt="Submit Request" align="middle" hspace="3">
                                    <br> This is were you can submit your support request through of Helpdesk system
                                    <br>
                                    <br>

                                    <form action="submit.php" method="post" onsubmit="try { var myValidator = validate_submit; } catch(e) { return true; } return myValidator(this);">
                                        {include file="js/submit.js"}
                                        <table>
                                            <tr>
                                                <td colspan="1" width="100" align="right">
                                                    Your Email Address:
                                                </td>
                                                <td colspan="2">
                                                    <input name="email_id" type="text" title="Your Email Address" size="50" >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" align="right">
                                                    Subject:
                                                </td>
                                                <td colspan="2">
                                                    <input name="subject" type="text" title="Your Email Address" size="50" >
                                                    <input name="ticket_id" type="text" title="Ticket ID" size="80" value >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" valign="top" align="right">
                                                    Description:
                                                </td>
                                                <td colspan="2">
                                                    <textarea cols="50" rows="10" name="description"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" align="right">
                                                    Priority:
                                                </td>
                                                <td colspan="2">
                                                    <select name="priority" id="priority_id" >
                                                        <option value="Low">Low</option>
                                                        <option value="Normal" selected>Normal</option>
                                                        <option value="High">High</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>

                                </td>
                            </tr>
                        </table>

                        <table class="olotable"  border="0" align="center">
                            <tr>
                                <td class="olohead">
                                    &nbsp;Login
                                </td>
                            </tr>
                            <tr>
                                <td class="olotd">
                                    <table  cellspacing="5" border="0" cellpadding="5" align="center">
                                        <tr>
                                            <td>
                                                <form action="index.php" method="POST">
                                                    <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                                                        <tr>
                                                            <td>
                                                                Login
                                                            </td>
                                                            <td>
                                                                <input type="text" name="login" size="25" class="olotd5" alt="login">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Password
                                                            </td>
                                                            <td>
                                                                <input type="password" name="password" size="25"  class="olotd5" alt="password">
                                                            </td>
                                                        </tr>
                                                        <tr align="center">
                                                            <td colspan="2">
                                                                <button type="submit" name="submit"><img src="images/tick.png" alt=""> Login</button>
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
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" >
                                    <span class="text3"><a href="http://www.myitcrm.com"><b>MyIT CRM Version - {$VERSION}</b></a><br><a href="http://www.myitcrm.com"><b>The Best Open Source IT CRM program available!</b></a><br><a>This software is distributed under the GNU General Public License V3</a><br></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>
