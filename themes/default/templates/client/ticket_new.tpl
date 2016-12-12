<!-- ticket_new.tpl - Submit a New Support Request - This is were you can submit your support request through of Helpdesk system  -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
{include file='client/ticket_new.js'}     
                        
<table class="olotable" width="60%" align="left" border ="1" cellpadding="5" cellspacing="5" >
    <tr>
        <td class="olohead" colspan="2">{$translate_submit_page_title}Page title</td>
    </tr>
    <tr>
        <td class="olotd5">
            <img src="{$theme_images_dir}request.png" alt="Submit Request" align="middle" hspace="3">
            <b>{$translate_submit_form_description}</b>
            <br>
            <br>            
            {literal}
            <form action="?page=client:ticket_new" method="post" onsubmit="try { var myValidator = validate_submit; } catch(e) { return true; } return myValidator(this);">
            {/literal}
                <!-- Let get some info from the customer if this is there first time using this service -->                
                <script>
                {literal}    
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
                {/literal}    
                </script>                
                <a href="#" id="newuser">{$translate_submit_new_account}</a>
                <div id="newuserform">
                    <table width="100%" class="olotd" cellpadding="4" cellspacing="2">
                        <tr>
                            <td colspan="1" width="100" align="right">{$translate_submit_full_name}:</td>
                            <td colspan="2"><input name="sort_name" type="text" size="50" ><b><font color="BLUE">{$translate_submit_name_example}</font></b></td>
                        </tr>
                        <tr>
                            <td colspan="1" width="100" align="right">{$translate_invoice_phone}:</td>
                            <td colspan="2"><input name="phone_number" type="text" size="20" ></td>
                        </tr>
                        <tr>
                            <td colspan="1" width="100" align="right">{$translate_customer_city}:</td>
                            <td colspan="2"><input name="city" type="text" size="20"></td>
                        </tr>
                    </table>
                </div>
                <!-- Now lets get the details of the issue -->
                <table>                                            
                    <tr>
                        <td colspan="3"><b><font color="BLUE" size="+1">{$translate_submit_form_heading}</font></b></td>
                    </tr>
                    <tr>
                        <td colspan="1" width="100" align="right">{$translate_employee_email_address}:</td>
                        <td colspan="2"><input name="from" type="text" size="50"></td>
                    </tr>
                    <tr>
                        <td colspan="1" align="right">{$translate_submit_form_description2}: subject</td>
                        <td colspan="2">
                            <input name="subject" id="subject" type="text" size="50" ><b><font color="BLUE">{$translate_submit_subject_example}</font></b>
                            <input type="hidden" name="ticket_id" type="text" title="Ticket ID" size="80" value >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" valign="top" align="right">{$translate_submit_issue_details}: description</td>
                        <td colspan="2"><textarea cols="75" rows="20" id="description" name="description"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="1" align="right">{$translate_priority}: priority</td>
                        <td colspan="2">
                            <select name="priority" id="priority" >
                                <option value="Low">{$translate_low}low</option>
                                <option value="Normal" selected>{$translate_normal}normal</option>
                                <option value="High">{$translate_high}high</option>
                                <option value="Critical">{$translate_critical}critical</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" align="right">{$translate_submit_time_to_call}: time to call</td>
                        <td colspan="2">
                            <select name="time" id="time" >
                                <option value="Morning">{$translate_submit_time_option1}morning</option>
                                <option value="Afternoon" >{$translate_submit_time_option2}afternoon</option>
                                <option value="Evening">{$translate_submit_time_option3}evening</option>
                                <option value="Anytime" selected>{$translate_submit_time_option4}anytime</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" align="right"><b><font color="RED" >{$translate_spam_part1}spam part1</font></b></td>
                        <td><b><font color="RED" ><input type="text" name="human" id="human" size ="2">{$translate_spam_part2}spam part 2</font></b></td>
                    </tr>
                    <tr>                                                                                          
                        <td colspan="2" align="center"><br><button type="submit" name="submit" id="submit" style="font-size: 14pt; color: RED">Proceed</button></td>
                    </tr>
                </table>     
            </form>                    
        </td>
    </tr>
</table>
                    
<!-- why do i need this - is it just this tpl -->
<div style="clear: both"></div>                        