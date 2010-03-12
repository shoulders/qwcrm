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
                                    <b>Please tell us about your issue by filling out the form below and one our our Technicians will be in contact with you shortly.</b>
                                    <br>
                                    <br>
                                   <form action="submit.php" method="post" >
                                       <!-- Let get some info from the customer if this is there first time using this service -->
                                        <table width="100%" class="olotd" cellpadding="4" cellspacing="2">
                                            <tr>
                                                <td colspan="3">
                                                    <b><font color="RED" size="+1"> First time using our service? Please provide us some details about yourself here.</font></b>
                                    
                                                </td>
                                            </tr>
                                        <tr>
                                                <td  colspan="1" width="100" align="right">
                                                    Full Name:
                                                </td>
                                                <td colspan="2">
                                                    <input name="sort_name" type="text" size="50" > <b><font color="BLUE">eg: John Smith</font></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="1" width="100" align="right">
                                                    Contact Number:
                                                </td>
                                                <td colspan="2">
                                                    <input name="phone_number" type="text" size="20" >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="1" width="100" align="right">
                                                    City:
                                                </td>
                                                <td colspan="2">
                                                    <input name="city" type="text" size="20" >
                                                </td>
                                            </tr>
                                    </table>
                                       <!-- Now lets get the details of the issue -->
                                        <table>
                                            <tr>
                                                <td colspan="3">
                                                    <b><font color="BLUE" size="+1"> Now, please tell us some information about you problem.</font></b>
                                    
                                                </td>
                                            </tr>

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
                                                    Brief Description:
                                                </td>
                                                <td colspan="2">
                                                    <input name="subject" type="text" size="50" ><b><font color="BLUE"> eg: Computer Won't Boot</font></b>
                                                    <input  type="hidden" name="ticket_id" type="text" title="Ticket ID" size="80" value >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" valign="top" align="right">
                                                    Issue Details:
                                                </td>
                                                <td colspan="2">
                                                    <textarea cols="80" rows="10" name="description"></textarea>
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
                                            
                                            <tr>
                                                <td colspan="1" align="right">
                                                    Best time to call:
                                                </td>
                                                <td colspan="2">
                                                    <select name="time" id="time" >
                                                        <option value="morning">6am to 12pm</option>
                                                        <option value="afternoon" >12pm to 6pm</option>
                                                        <option value="evening">6pm to 9pm</option>
                                                        <option value="allday" selected>Any Time</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="1" align="right">
                                                    
                                                    <br>
                                                    <b><font color="RED" >There are</font></b>
                                                </td>
                                                <td>
                                                    
                                                    <br><b><font color="RED" ><input type="text" name="human" id="human" size ="6"> months in a Year. Human Test (Prevents Spamming)</font></b>
                                                </td>
                                            </tr>
                                            <tr>                                                                                          
                                                <td colspan="2" align="left">
                                                    <br><b><font color="RED" >Please verify your information is correct before pressing this button>></font></b><input type="submit" name="submit_request" value="Submit">
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
                                                            <td align="right">
                                                                Login
                                                            </td>
                                                            <td>
                                                                <input type="text" name="login" size="25" class="olotd5" alt="login">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right">
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
