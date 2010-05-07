<!-- Begin submit.tpl--><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
    <head>
        <title>{$translate_submit_page_title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="css/default.css" rel="stylesheet" type="text/css">
        
    </head>
    <body>
        {literal}
<script type="text/javascript" src="js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "simple"
});
</script>
{/literal}
 {include file="../js/submit.js"}
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
                        <table class="olotable" width="60%" align="left" border ="1" cellpadding="5" cellspacing="5" >
                            <tr>
                                <td class="olohead" colspan="2">
                                    {$translate_submit_page_title}
                                </td>
                            </tr>
                            <tr>
                                <td class="olotd5">
                                    <img src="images/request.png" alt="Submit Request" align="middle" hspace="3">
                                    <b>{$translate_submit_form_description}</b>
                                    <br>
                                    <br>
                                    {literal}
                                   <form action="submit.php" method="post" onsubmit="try { var myValidator = validate_submit; } catch(e) { return true; } return myValidator(this);" >
                                    {/literal}   <!-- Let get some info from the customer if this is there first time using this service -->
                                     {literal}<script type="text/javascript">
 $(function(){
     $("#newuser").click(function(event) {
     event.preventDefault();
     $("#newuserform").slideToggle();
 });
 $("#newuserform a").click(function(event) {
     event.preventDefault();
     $("#newuserform").slideUp();
 });
 });
 </script>
 {/literal}
  <a href="#" id="newuser">{$translate_submit_new_account}</a>
 <div id="newuserform">


                                    <table width="100%" class="olotd" cellpadding="4" cellspacing="2">
                                            <tr>
                                                <td  colspan="1" width="100" align="right">
                                                    {$translate_submit_full_name}:
                                                </td>
                                                <td colspan="2">
                                                    <input name="sort_name" type="text" size="50" > <b><font color="BLUE">{$translate_submit_name_example}</font></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="1" width="100" align="right">
                                                    {$translate_invoice_phone}:
                                                </td>
                                                <td colspan="2">
                                                    <input name="phone_number" type="text" size="20" >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="1" width="100" align="right">
                                                    {$translate_customer_city}:
                                                </td>
                                                <td colspan="2">
                                                    <input name="city" type="text" size="20" >
                                                </td>
                                            </tr>
                                    </table>
 </div>
                                       <!-- Now lets get the details of the issue -->
                                      
                                        <table>                                            
                                            <tr>
                                                <td colspan="3">
                                                    <b><font color="BLUE" size="+1">{$translate_submit_form_heading}</font></b>
                                    
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="1" width="100" align="right">
                                                    {$translate_employee_email_address}:
                                                </td>
                                                <td colspan="2">
                                                    <input name="from"  type="text" size="50" >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" align="right">
                                                    {$translate_submit_form_description2}:
                                                </td>
                                                <td colspan="2">
                                                    <input name="subject" id="subject" type="text" size="50" ><b><font color="BLUE"> {$translate_submit_subject_example}</font></b>
                                                    <input  type="hidden" name="ticket_id" type="text" title="Ticket ID" size="80" value >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" valign="top" align="right">
                                                    {$translate_submit_issue_details}:
                                                </td>
                                                <td colspan="2">
                                                    <textarea cols="75" rows="20" id="description" name="description"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" align="right">
                                                    {$translate_priority}:
                                                </td>
                                                <td colspan="2">
                                                    <select name="priority" id="priority" >
                                                        <option value="Low">{$translate_low}</option>
                                                        <option value="Normal" selected>{$translate_normal}</option>
                                                        <option value="High">{$translate_high}</option>
                                                        <option value="Critical">{$translate_critical}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="1" align="right">
                                                    {$translate_submit_time_to_call}:
                                                </td>
                                                <td colspan="2">
                                                    <select name="time" id="time" >
                                                        <option value="Morning">{$translate_submit_time_option1}</option>
                                                        <option value="Afternoon" >{$translate_submit_time_option2}</option>
                                                        <option value="Evening">{$translate_submit_time_option3}</option>
                                                        <option value="Anytime" selected>{$translate_submit_time_option4}</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="1" align="right">
                                                    
                                                    <br>
                                                    <b><font color="RED" >{$translate_spam_part1}</font></b>
                                                </td>
                                                <td>
                                                    
                                                    <br><b><font color="RED" ><input type="text" name="human" id="human" size ="2">{$translate_spam_part2}</font></b>
                                                </td>
                                            </tr>
                                            <tr>                                                                                          
                                                <td colspan="2" align="center">
                                                    <br><button type="submit" name="submit" id="submit" style="font-size: 14pt; color: RED">Proceed</button>
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
                                    &nbsp;{$translate_employee_login}
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
                                                                {$translate_employee_login}
                                                            </td>
                                                            <td>
                                                                <input type="text" name="login" size="25" class="olotd5" alt="login">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right">
                                                                {$translate_employee_password}
                                                            </td>
                                                            <td>
                                                                <input type="password" name="password" size="25"  class="olotd5" alt="password">
                                                            </td>
                                                        </tr>
                                                        <tr align="center">
                                                            <td colspan="2">
                                                                <button type="submit" name="submit"><img src="images/tick.png" alt=""> {$translate_employee_login}</button>
                                                            </td>
                                                        </tr>
                                                        { if $error_msg != "" }
                                                        <tr>
                                                            <td colspan="2" class="error">
                                                                {$error_msg}
                                                            </td>
                                                        </tr>
							{ /if}
                                                        <tr align="center">
                                                            <td colspan="2">
                                                                <a href="password.php"> {$translate_reset_password}</a>
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
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" >
                                    <span class="text3"><a href="http://team.myitcrm.com"><b>MyIT CRM Version - {$VERSION}</b></a><br><a href="http://team.myitcrm.com"><b>{$translate_footer1}</b></a><br><a>{$translate_footer_license}</a><br></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>
