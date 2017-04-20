<!-- print_technician_workorder_slip.tpl - Technician Work Order Slip Print Template -->
{section name=i loop=$single_work_order}    
    <!-- Header -->
    <table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr bgcolor="#999999">
            <td width="20%" valign="middle" align="center"><img src="{$company_logo}" alt="" height="50"></td>
            <td valign="top" align="center">            
                <font size="+3">{$translate_workorder_print_technician_workorder_slip_title}</font><br />
                {$translate_workorder_work_order_id} {$single_work_order[i].WORK_ORDER_ID}
            </td>
            <td width="20%" valign="middle" align="center"></td>
        </tr>
    </table>

    <!-- Contact Information -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="50%" align="center" valign="middle"><b>{$translate_workorder_print_customer_details_title}</b></td>
            <td width="50%" align="center" valign="middle"><b>{$translate_workorder_print_company_details_title}</b></td>        
        </tr>    
        <tr>

            <!-- Customer Details -->
            <td valign="top">
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td>
                            <p><b><font size="+1">{$single_work_order[i].CUSTOMER_DISPLAY_NAME}</font></b></p>
                            <p>                            
                                <b>{$translate_workorder_address}:</b><br>
                                {$single_work_order[i].CUSTOMER_ADDRESS}<br>
                                {$single_work_order[i].CUSTOMER_CITY}, {$single_work_order[i].CUSTOMER_STATE} {$single_work_order[i].CUSTOMER_ZIP}
                            </p>
                            <p>
                                <b>{$translate_workorder_contact}: </b>{$single_work_order[i].CUSTOMER_FIRST_NAME} {$single_work_order[i].CUSTOMER_LAST_NAME}<br />
                                <b>{$translate_workorder_primary_phone}: </b>{$single_work_order[i].CUSTOMER_PHONE}<br>
                                <b>{$translate_workorder_work}: </b>{$single_work_order[i].CUSTOMER_WORK_PHONE}<br>
                                <b>{$translate_workorder_mobile}: </b>{$single_work_order[i].CUSTOMER_MOBILE_PHONE}<br>
                                <b>{$translate_workorder_email}: </b>{$single_work_order[i].CUSTOMER_EMAIL}<br>                            
                            </p>
                            <p>
                                <b>{$translate_workorder_type}: </b> 
                                {if $single_work_order[i].CUSTOMER_TYPE == '1'}{$translate_workorder_customer_type_1}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '2'}{$translate_workorder_customer_type_2}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '3'}{$translate_workorder_customer_type_3}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '4'}{$translate_workorder_customer_type_4}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '5'}{$translate_workorder_customer_type_4}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '6'}{$translate_workorder_customer_type_4}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '7'}{$translate_workorder_customer_type_4}{/if}
                                {if $single_work_order[i].CUSTOMER_TYPE == '8'}{$translate_workorder_customer_type_4}{/if}
                                {if $single_work_order[i].CUSTOMER_TYPE == '9'}{$translate_workorder_customer_type_4}{/if} 
                                {if $single_work_order[i].CUSTOMER_TYPE == '10'}{$translate_workorder_customer_type_4}{/if}                            
                            </p>
                        </td>
                    </tr>
                </table>            
            </td>

            <!-- Company Details -->
            <td valign="top">
                <table cellpadding="4" cellspacing="0" border="0">
                    {section name=d loop=$company}                
                        <tr>
                            <td>
                                <p><b><font size="+1">{$company[d].COMPANY_NAME}</font></b><br></p>
                                <p>
                                    <b>{$translate_workorder_address}:</b><br>
                                    {$company[d].COMPANY_ADDRESS}<br>
                                    {$company[d].COMPANY_CITY}, {$company[d].COMPANY_STATE} {$company[d].COMPANY_ZIP}
                                </p>
                                <p>
                                    <b>{$translate_workorder_phone}: </b>{$single_work_order[i].EMPLOYEE_WORK_PHONE}<br>                        
                                    <b>{$translate_workorder_mobile}: </b>{$company[d].COMPANY_MOBILE}<br>
                                    <b>{$translate_workorder_fax}: </b>{$company[d].COMPANY_FAX}<br>
                                    <b>{$translate_workorder_website}: </b>{$company[d].COMPANY_WWW}<br>   
                                    <b>{$translate_workorder_email}: </b>{$company[d].COMPANY_EMAIL}
                                </p>
                            </td>
                        </tr>
                    {/section}                
                </table>    
            </td>

        </tr>
    </table>

    <!-- Work Order Information -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>        
            <td valign="top" align="center" nowrap><b>{$translate_workorder_print_workorder_details_title}</b></td>        
            <td valign="top" align="center" nowrap><b>{$translate_workorder_print_summary_title}</b></td>
        </tr>
        <tr>
            
            <!-- Left Column -->
            <td valign="top" width="60%">
                <table border="0" cellpadding="4" cellspacing="0">               
                    <tr>
                        <td valign="top" nowrap><b>{$translate_workorder_scope}:</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap>{$single_work_order[i].WORK_ORDER_SCOPE}</td>
                    </tr>
                </table>

                <!-- Description -->
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{$translate_workorder_details_description_title}:</b></td>
                    </tr>
                    <tr>
                        <td><div>{$single_work_order[i].WORK_ORDER_DESCRIPTION}</div></td>
                    </tr>
                </table>

                <!-- Comments -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{$translate_workorder_details_comments_title}:</b></td>
                    </tr>
                    <tr>
                        <td><div style="min-height: 300px;">{$single_work_order[i].WORK_ORDER_COMMENT}</div></td>
                    </tr>
                </table>

                <!-- Resolution -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{$translate_workorder_details_resolution_title}:</b></td>
                    </tr>
                    {section name=r loop=$workorder_resolution}  
                        <tr>                                    
                            {if $workorder_resolution[r].EMPLOYEE_DISPLAY_NAME != ''}                            
                                <td><b>{$translate_workorder_closed_by}:</b>{$workorder_resolution[r].EMPLOYEE_DISPLAY_NAME} on <b>{$translate_workorder_date}: </b>{$workorder_resolution[r].WORK_ORDER_CLOSE_DATE|date_format:$date_format}</td>                                                       
                            {/if}
                        </tr>
                        <tr>
                            <td><div style="min-height: 150px;">{$workorder_resolution[r].WORK_ORDER_RESOLUTION}</div></td>                 
                        </tr>
                    {/section}   
                </table>

                <!-- Notes -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{$translate_workorder_details_notes_title}:</b></td>
                    </tr>
                    {section name=b loop=$work_order_notes}                        
                        {if $work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION != ''} 
                            <tr>
                                <td>
                                    <p>
                                        ------------------------------------------------------------------------------------<br>
                                        {$translate_workorder_print_this_note_was_created_by} <b>{$translate_workorder_technician}: </b>{$work_order_notes[b].EMPLOYEE_DISPLAY_NAME} {$translate_workorder_on} <b>{$translate_workorder_date}: </b> {$work_order_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format %R"}
                                    </p>
                                    <div>{$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}</div>                       
                                </td>
                            </tr>
                        {else}
                            <tr>
                                <td><div>{$translate_workorder_print_there_are_no_notes}</div></td>
                            </tr>                   
                        {/if}
                    {/section}
                </table>                    
            </td>

            <!-- Right Column -->
            <td valign="top" width="20%">

                <!-- Summary -->
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td valign="top" width="50%"><b>{$translate_workorder_work_order_id}</b></td>
                        <td valign="top">{$single_work_order[i].WORK_ORDER_ID}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{$translate_workorder_todays_display_date}</b></td>
                        <td valign="top">{$smarty.now|date_format:$date_format}</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap><b>{$translate_workorder_opened}</b></td>
                        <td valign="top">{$single_work_order[i].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
                    </tr>                
                    <tr>
                        <td valign="top" nowrap><b>{$translate_workorder_technician}</b></td>
                        <td valign="top">{$single_work_order[i].EMPLOYEE_DISPLAY_NAME}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{$translate_workorder_status}</b></td>
                        <td valign="top">
                            {if $single_workorder[i].WORK_ORDER_STATUS == '1'}{$translate_workorder_created}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '2'}{$translate_workorder_assigned}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '3'}{$translate_workorder_waiting_for_parts}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '6'}{$translate_workorder_closed}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '7'}{$translate_workorder_waiting_for_payment}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '8'}{$translate_workorder_payment_made}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '9'}{$translate_workorder_pending}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '10'}{$translate_workorder_open}{/if}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_last_change}:</b></td>
                        <td>{$single_work_order[i].LAST_ACTIVE|date_format:$date_format}</td>
                    </tr>
                </table>

                <!-- Service Time -->
                <hr>
                <table width="100%" border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td align="center" colspan="2"><b>{$translate_workorder_print_service_times_title}</b></td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_departed}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_started_wo}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_finished_wo}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_returned}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                </table>

                <!-- Schedule -->
                <hr>
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td align="center"><b>{$translate_workorder_print_schedule_title}</b></td>
                    </tr><tr>
                        <td>
                            {section name=e loop=$workorder_schedule}
                                <b>{$translate_workorder_start_time}:</b> {$workorder_scheduleule[e].SCHEDULE_START|date_format:"$date_format %R"}<br>
                                <b>{$translate_workorder_end_time}:</b> {$workorder_scheduleule[e].SCHEDULE_END|date_format:"$date_format %R"}<br>
                                <b>{$translate_workorder_schedule_notes}:</b><br>
                                <div>{$workorder_schedule[e].SCHEDULE_NOTES}</div>
                            {sectionelse}
                                {$translate_workorder_print_no_schedule_has_been_set}
                            {/section}
                        </td>
                    </tr>
                </table>

                <!-- Signatures -->
                <hr>
                <table width="100%" border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td align="center" colspan="2"><b>{$translate_workorder_print_signatures_title}</b></td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_client_name}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_signature}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_technician}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_workorder_print_signature}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>                    

            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td colspan="3" border="0" align="center">{$translate_workorder_print_this_workorder_is_confidential}</td>
        </tr>
    </table>            
{/section}