<!-- print.tpl - Print Work Order Template -->
{literal}
<script type="text/javascript">
    function data_copy() {
        if(document.form1.copy[0].checked){
            document.form1.tt1.value=document.form1.t1.value;
            document.form1.ddt1.value=document.form1.dt1.value;
            document.form1.tt2.value=document.form1.t2.value;
            document.form1.ddt2.value=document.form1.dt2.value;
            document.form1.tt3.value=document.form1.t3.value;
            document.form1.ddt3.value=document.form1.dt3.value;
            document.form1.tt4.value=document.form1.t4.value;
            document.form1.ddt4.value=document.form1.dt4.value;
            document.form1.tt5.value=document.form1.t5.value;
            document.form1.ddt5.value=document.form1.dt5.value;
            document.form1.tt6.value=document.form1.t6.value;
            document.form1.ddt6.value=document.form1.dt6.value;
            document.form1.tt7.value=document.form1.t7.value;
            document.form1.ddt7.value=document.form1.dt7.value;
            document.form1.bk2.value=document.form1.bk1.value;

        } else {
            document.form1.tt1.value="";
            document.form1.ddt1.value="";
            document.form1.tt2.value="";
            document.form1.ddt2.value="";
            document.form1.tt3.value="";
            document.form1.ddt3.value="";
            document.form1.tt4.value="";
            document.form1.ddt4.value="";
            document.form1.tt5.value="";
            document.form1.ddt5.value="";
            document.form1.tt6.value="";
            document.form1.ddt6.value="";
            document.form1.tt7.value="";
            document.form1.ddt7.value="";
            document.form1.bk2.value="";
            }

    }
</script>
{/literal}
{section name=i loop=$single_workorder_array}
<table  width="100%" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr align="center">
        <!-- right column -->
        <td valign="top" align="center" ><img src="{$theme_images_dir}logo.jpg" alt="" height="50"></td>
        <!-- middle column -->
        <td valign="top" align="center" width="80%">
            <font size="+3">{$translate_workorder_print_technician_copy}</font><br>
            {$translate_workorder_print_work_order_id} #{$single_workorder_array[i].WORK_ORDER_ID}
        </td>
    </tr>
    <tr>
        <!-- left Column -->
        <td valign="top" align="center" nowrap><b>{$translate_workorder_print_service_location_title}</b></td>
        <!-- Center Column -->
        <td valign="top" align="center" nowrap><b>{$translate_workorder_print_service_details_title}</b></td>
        <!-- right column -->
        <td valign="top" align="center" nowrap><b>{$translate_workorder_print_summary_title}</b></td>
    </tr>
    <tr>
        <!-- left Column -->
        <td valign="top" width="20%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td><b>{$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}</b></td>
                </tr>
            </table>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td>{$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}<br>
                        {$single_workorder_array[i].CUSTOMER_ADDRESS}<br>
                        {$single_workorder_array[i].CUSTOMER_CITY}, {$single_workorder_array[i].CUSTOMER_STATE} {$single_workorder_array[i].CUSTOMER_ZIP}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>{$translate_workorder_print_home}:</b> {$single_workorder_array[i].CUSTOMER_PHONE}<br>
                        <b>{$translate_workorder_print_work}:</b> {$single_workorder_array[i].CUSTOMER_WORK_PHONE}<br>
                        <b>{$translate_workorder_print_mobile}:</b> {$single_workorder_array[i].CUSTOMER_MOBILE_PHONE}
                    </td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_email}:</b> {$single_workorder_array[i].CUSTOMER_EMAIL}<br>
                </tr>
            </table>
            <!--OLD LINE-->
            <hr>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td><b>{$translate_workorder_print_company_contact_title}</b></td>
                </tr>
                {section name=d loop=$company}
                    <tr>
                        <td>{$company[d].COMPANY_NAME}<br>
                            {$company[d].COMPANY_ADDRESS}<br>
                            {$company[d].COMPANY_CITY}, {$company[d].COMPANY_STATE} {$company[d].COMPANY_ZIP}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <b>{$translate_workorder_print_phone_numbers}</b><br>
                            <b>{$translate_workorder_print_primary}:</b>&nbsp {$company[d].COMPANY_PHONE}<br>                  
                            <b>{$translate_workorder_print_fax}:</b>&nbsp {$company[d].COMPANY_FAX}<br>
                            <b>{$translate_workorder_print_mobile}:</b>&nbsp {$company[d].COMPANY_MOBILE}<br>
                        </td>
                    </tr>
                {/section}
            </table>            
            <hr>
            <b>{$translate_workorder_print_thank_you_title}&nbsp</b>{$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}<br><br>
            {$translate_workorder_print_thank_you_message}
        </td>
        <!-- Center Column -->
        <td valign="top" width="60%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td><b>{$translate_workorder_print_description}</b></td>
                </tr>
                <tr>
                    <td>{$single_workorder_array[i].WORK_ORDER_DESCRIPTION }</td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_comments}</b></td>
                </tr>
                <tr>
                    <td>{$single_workorder_array[i].WORK_ORDER_COMMENT}</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        {section name=b loop=$work_order_notes}
                        <p>
                            <b>{$translate_workorder_print_service_notes}</b><br>
                            {$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}<br><br>
                            <b>{$translate_workorder_print_entered_by}: </b>{$work_order_notes[b].EMPLOYEE_DISPLAY_NAME}<br>
                            <b>{$translate_workorder_print_date}: </b> {$work_order_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format"}<br>
                        </p>
                        {/section}
                    </td>
                </tr>
            </table>
            <hr>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td align="center"><b>{$translate_workorder_print_schedule_details_title}</b></td>
                </tr>
                <tr>
                    <td>
                        {section name=e loop=$work_order_sched}
                            <b>{$translate_workorder_print_scheduled_start}</b> {$work_order_sched[e].SCHEDULE_START|date_format:"$date_format %I:%M  %p"}<br>
                            <b>{$translate_workorder_print_scheduled_end}</b> {$work_order_sched[e].SCHEDULE_END|date_format:"$date_format %I:%M  %p "} <br>
                            <b>{$translate_workorder_print_scheduled_notes}</b><br>
                            {$work_order_sched[e].SCHEDULE_NOTES}
                        {sectionelse}
                            {$translate_workorder_print_no_schedule_has_been_set_message}
                        {/section}
                    </td>
                </tr>
            </table>
            <hr>
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        <b>{$translate_workorder_print_resolution_title}:</b><br>
                        {section name=r loop=$work_order_res}
                            {if $work_order_res[r].EMPLOYEE_DISPLAY_NAME != ''}
                                <b>{$translate_workorder_closed_by}:</b> {$work_order_res[r].EMPLOYEE_DISPLAY_NAME} <b>{$translate_workorder_date}:</b>  {$work_order_res[r].WORK_ORDER_CLOSE_DATE|date_format:"$date_format"}
                                {$work_order_res[r].WORK_ORDER_RESOLUTION}
                            {/if}
                        {/section}
                    </td>
                </tr>
                <tr>
                    <td>
                        <br><br><br><br><br><br><br><br>
                    </td>
            </table>
        </td>
        <!-- right column -->
        <td valign="top" width="20%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td valign="top" nowrap><b>{$translate_workorder_scope}:</b></td>
                    <td valign="top">{$single_workorder_array[i].WORK_ORDER_SCOPE}</td>
                </tr>
                <tr>
                    <td valign="top" nowrap><b>{$translate_workorder_print_summary_date_opened}:</b></td>
                    <td valign="top">{$single_workorder_array[i].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
                </tr>
                <tr>
                    <td valign="top"><b>{$translate_workorder_status}:</b></td>
                    <td valign="top">
                        {if $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "1"}
                            {$translate_workorder_created}
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "2"}
                            {$translate_workorder_assigned}
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "3"}
                            {$translate_workorder_waiting_for_parts}
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "6"}
                            {$translate_workorder_closed}
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "7"}    
                            {$translate_workorder_waiting_for_payment}
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "8"}    
                            {$translate_workorder_payment_made}
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "9"}    
                            {$translate_workorder_pending}   
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>{$translate_workorder_print_signature_technician_name}:</b></td>
                    <td valign="top">
                        {if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME ==""}
                            {$translate_workorder_not_assigne}
                        {else}
                            {$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_last_changed}:</b></td>
                    <td>{$single_workorder_array[i].LAST_ACTIVE|date_format:"$date_format"}</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td align="center" colspan="2"><b>{$translate_workorder_print_service_time}</b></td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_arrival}</b></td>
                    <td>___/____/____ __:__</td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_departed}</b></td>
                    <td>___/____/____ __:__</td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_travel}</b></td>
                    <td>_________KM/Miles</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td align="center"><b>{$translate_workorder_print_notes_title}</b></td>
                </tr>
                <tr>
                    <td>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        <br>                        
                    </td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td align="center"><b>{$translate_workorder_print_feedback_title}</b></td>
                </tr>
                <tr>
                    <td align="center">{$translate_workorder_print_feedback_message}</td>
                </tr>
                <tr>
                    <td align="center"><b>{$translate_workorder_print_feedback_your_rating_is}</b></td>
                </tr>
                <tr>
                    <td>
                        {$translate_workorder_print_feedback_comments}:<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
                        <br>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td align="center" colspan="2"><b>{$translate_workorder_print_signature_title}</b></td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_signature_client_name}</b></td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_signature_signature}</b></td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_signature_technician_name}</b></td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td><b>{$translate_workorder_print_signature_signature}</b></td>
                    <td>__________________</td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>
<br>
{/section}
<h2 align="center" style="page-break-after: always;" >{$translate_workorder_print_hardware_peripherials_received_title}</h2>
<form name=form1 method=post action=''>
    <table id="test" width="100%" align="center" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td colspan="1" align="right" valign="top" >
                <input type="text" value="PC Tower/Laptop:" align="right" readonly><br><br>
                <input type="text" value="Power Cords/Supply:" readonly><br><br>
                <input type="text" value="Software/Discs:" readonly><br><br>
                <input type="text" value="Mice:" readonly><br><br>
                <input type="text" value="Modem/Router:" readonly><br><br>
                <input type="text" value="Printer(s):" readonly><br><br>
                <input type="text" value="Other(s):" readonly><br><br>
            </td>
            <td colspan="2" align="left" valign="top" >
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t1">&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="dt1"><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t2">&nbsp;&nbsp;{$translate_workorder_print_descriptions}:<input type="text" size="40" name="dt2"><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t3">&nbsp;&nbsp;{$translate_workorder_print_descriptions}:<input type="text" size="40" name="dt3"><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t4">&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="dt4"> <br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t5">&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="dt5"><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t6">&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="dt6"><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="t7">&nbsp;&nbsp;{$translate_workorder_print_descriptions}:<input type="text" size="40" name="dt7"><br>
                <br><input type=radio name=copy value='yes' onclick="data_copy()";>{$translate_workorder_print_copy_data_to_customers_copy}
                <br><input type=hidden name=copy value='no' onclick="data_copy()";>
            </td>
        </tr>
    </table>
    <table align="center" width="100%">
        <tr>
            <td><font><b>{$translate_workorder_print_if_backup_required}__________________________________ </b></font></td>
        </tr>
        <tr>
            <td>
                {$translate_workorder_print_data_backup_required}
                <select name="bk1">
                    <option value="Yes">{$translate_workorder_print_yes}</option>
                    <option value="No" selected>{$translate_workorder_print_no}</option>
                </select>
                <br>
                <br>
            </td>
        </tr>
    </table>
    <hr align="center" noshade style="page-break-after: always;">
    <!-- Work Order Customers Copy -->
    {section name=i loop=$single_workorder_array}
    <table width="100%" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <!-- right column -->
            <td valign="top" align="center" ><img src="{$theme_images_dir}logo.jpg" alt="" height="50"></td>
            <!-- middle column -->
            <td valign="top" align="center">
                <font size="+3">{$translate_workorder_print_customer_workorder_slip}</font><br>
                {$translate_workorder_print_work_order_id} #{$single_workorder_array[i].WORK_ORDER_ID}
            </td>
        </tr>
        <tr>
            <!-- left Column -->
            <td valign="top" align="center" nowrap><b>{$translate_workorder_customer_details}</b></td>
            <!-- Center Column -->
            <td valign="top" align="center" nowrap><b>{$translate_workorder_print_service_details_title}</b></td>
            <!-- right column -->
        </tr>
        <tr>
            <!-- left Column -->
            <td valign="top" width="20%">
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td>
                            {$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}<br>
                            {$single_workorder_array[i].CUSTOMER_ADDRESS}<br>
                            {$single_workorder_array[i].CUSTOMER_CITY}, {$single_workorder_array[i].CUSTOMER_STATE} {$single_workorder_array[i].CUSTOMER_ZIP}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>{$translate_workorder_print_home}:</b> {$single_workorder_array[i].CUSTOMER_PHONE}<br>
                            <b>{$translate_workorder_print_work}:</b> {$single_workorder_array[i].CUSTOMER_WORK_PHONE}<br>
                            <b>{$translate_workorder_print_mobile}:</b> {$single_workorder_array[i].CUSTOMER_MOBILE_PHONE}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_email}:</b> {$single_workorder_array[i].CUSTOMER_EMAIL}<br>
                    </tr>
                </table>
            </td>
            <!-- Center Column -->
            <td valign="top" width="100%">
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td valign="top" nowrap><b>{$translate_workorder_scope}:&nbsp;</b>{$single_workorder_array[i].WORK_ORDER_SCOPE}</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_description}:</b></td>
                    </tr>
                    <tr>
                        <td>{$single_workorder_array[i].WORK_ORDER_DESCRIPTION }</td>
                    </tr>
                    {if $single_workorder_array[i].WORK_ORDER_COMMENT != ""}
                    <tr>
                        <td><b>{$translate_workorder_print_comments}:</b></td>
                    </tr>
                    <tr>
                        <td>{$single_workorder_array[i].WORK_ORDER_COMMENT}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td valign="top" nowrap>
                            {$translate_workorder_print_this_work_order_was_created_on_the}&nbsp;{$single_workorder_array[i].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}
                            &nbsp;and has the status of{if $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "1"}
                                {$translate_workorder_created}
                            {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "2"}
                                {$translate_workorder_assigned}
                            {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "3"}
                                {$translate_workorder_waiting_for_parts}
                            {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "6"}
                                {$translate_workorder_closed}
                            {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "7"}
                                {$translate_workorder_waiting_for_payment}
                            {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "8"}
                                {$translate_workorder_payment_made}
                            {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "9"}
                                {$translate_workorder_pending}
                            {/if}
                            .&nbsp;<br>{$translate_workorder_print_this_work_order_has_been_assigned_to}&nbsp;{if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME ==""}
                            &nbsp;{$translate_workorder_print_our_next_available_technician}
                            {else}
                                {$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}
                            {/if}
                        </td>
                    </tr>
                    {if $single_workorder_array[i].WORK_ORDER_NOTES_DESCRIPTION != ""}
                    <tr>
                        <td>
                            {section name=b loop=$work_order_notes}
                            <p><b>{$translate_workorder_print_service_notes}</b><br>
                            {$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}<br><br>
                    </tr>
                    {/section}
                    {/if}
                </table>
            </td>
        </tr>
    </table>
    {/section}
    <h2 align="center" >{$translatee_workorder_print_hardware_peripherials_received_title}</h2>
    <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td colspan="1" align="right" valign="top" >
                <input type="text" value="PC Tower/Laptop:" align="right" readonly><br><br>
                <input type="text" value="Power Cords/Supply:" readonly><br><br>
                <input type="text" value="Software/Discs:" readonly><br><br>
                <input type="text" value="Mice:" readonly><br><br>
                <input type="text" value="Modem/Router:" readonly><br><br>
                <input type="text" value="Printer(s):" readonly><br><br>
                <input type="text" value="Other(s):" readonly><br><br>
            </td>
            <td colspan="2" valign="top" >
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt1" readonly>&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="ddt1" readonly><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt2" readonly>&nbsp;&nbsp;{$translate_workorder_print_descriptions}:<input type="text" size="40" name="ddt2" readonly><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt3" readonly>&nbsp;&nbsp;{$translate_workorder_print_descriptions}:<input type="text" size="40" name="ddt3" readonly><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt4" readonly>&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="ddt4" readonly> <br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt5" readonly>&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="ddt5" readonly><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt6" readonly>&nbsp;&nbsp;{$translate_workorder_print_make_model}:<input type="text" size="40" name="ddt6" readonly><br><br>
                {$translate_workorder_print_qty}:<input type="text" size="3" name="tt7" readonly>&nbsp;&nbsp;{$translate_workorder_print_descriptions}:<input type="text" size="40" name="ddt7" readonly><br>
            </td>
        </tr>
    </table>
    <br>
    <table align="center" width="100%">
        <tr>
            <td><font><b>{$translate_workorder_print_backup_disclaimer}</b></font></td>
        </tr>
        <tr>
            <td>{$translate_workorder_print_data_backup_required}<input type="text" size="3" name="bk2" readonly><br><br></td>
        </tr>
        <tr>
            <td>
                <h3>{$translate_workorder_important_notes_title}</h3>
                {$translate_workorder_important_notes_content}
            </td>
        </tr>
    </table>
</form>
<table width="600" align="center">
     <tr>
         {section name=d loop=$company}
         <td>
             <font size="-2">
                <b>{$company[d].COMPANY_NAME} -</b>{$company[d].COMPANY_ADDRESS} ,{$company[d].COMPANY_CITY}, {$company[d].COMPANY_STATE} {$company[d].COMPANY_ZIP}
                <b>{$translate_workorder_print_company_phone}:</b>&nbsp;{$company[d].COMPANY_PHONE}
                {if $company[d].COMPANY_TOLL_FREE !=""}
                <b>{$translate_workorder_print_toll_free}:</b>&nbsp;{$company[d].COMPANY_TOLL_FREE}
                {/if}
                {if $company[d].COMPANY_MOBILE !=""}
                <b>{$translate_workorder_print_mobile}:</b>&nbsp;{$company[d].COMPANY_MOBILE}
                {/if}
             </font>
         </td>
    </tr>
        {/section}
</table>