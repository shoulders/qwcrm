<!-- print.new.tpl - Print Work Order Template -->
{section name=i loop=$single_workorder_array}
<table  width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <td valign="top" align="center" colspan="2" bgcolor="#999999">
            <font size="+3">Service Work Log</font>
            <br /> Work Order ID# {$single_workorder_array[i].WORK_ORDER_ID}
        </td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="middle"><b>Client Information</b></td>
        <td width="50%" align="center" valign="middle"><b>Company Contact</b></td>
    </tr>
    <tr>
        <td width="50%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        <b>{$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}</b><br>
                            {$single_workorder_array[i].CUSTOMER_ADDRESS}<br>
                            {$single_workorder_array[i].CUSTOMER_CITY}, {$single_workorder_array[i].CUSTOMER_STATE} {$single_workorder_array[i].CUSTOMER_ZIP}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Contact: </b> {$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}<br />
                        <b>Phone: </b> {$single_workorder_array[i].CUSTOMER_PHONE}<br>
                        <b>Work: </b> {$single_workorder_array[i].CUSTOMER_WORK_PHONE}<br>
                        <b>Mobile: </b> {$single_workorder_array[i].CUSTOMER_MOBILE_PHONE}<br>
                        <b>Email: </b> {$single_workorder_array[i].CUSTOMER_EMAIL}<br>
                        <b>Type: </b> {$single_workorder_array[i].CUSTOMER_TYPE}<br>
                    </td>
                </tr>
            </table>            
        </td>
        <td width="50%">
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                {section name=d loop=$company}
                    <tr>
                        <td>
                            <b>{$company[d].COMPANY_NAME}</b><br>
                            {$company[d].COMPANY_ADDRESS}<br>
                            {$company[d].COMPANY_CITY}, {$company[d].COMPANY_STATE} {$company[d].COMPANY_ZIP}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Technician: </b>{$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}<br />
                            <b>Phone Numbers: </b><br>
                            <b>Primary: </b>{$company[d].COMPANY_TOLL_FREE}<br>
                            <b>Phone: </b>&nbsp {$single_workorder_array[i].EMPLOYEE_WORK_PHONE}<br>
                            <b>Mobile: </b>&nbsp {$company[d].COMPNAY_MOBILE}<br>
                            <b>Email: </b>{$single_workorder_array[i].EMPLOYEE_EMAIL}<br />
                        </td>
                    </tr>
                {/section}
            </table>    
        </td>
    </tr>
</table>

<!-- Bottom Section -->
<table  width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <!-- left Column -->
        <td valign="top" align="center" nowrap><b>Service Details</b></td>
        <!-- right column -->
        <td valign="top" align="center" nowrap><b>Summary</b></td>
    </tr>
    <tr>
        <!-- left Column -->
        <!-- <td valign="top" width="20%"> -->
            <!--OLD LINE-->
            <!-- <hr>
            <p><center><b>THANK YOU VARIABLE</b><br><br>Thank you for using our service. Your
              business is greatly appreciated!</center></p>
            -->
        <!-- </td> -->
        
        <!-- Center Column -->
        <td valign="top" width="60%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td><b>Description: </b></td>
                </tr>
                <tr>
                    <td>{$single_workorder_array[i].WORK_ORDER_DESCRIPTION}</td>
                </tr>
            </table>
            <hr>
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td><b>Comments:</b></td>
                </tr>
                <tr>
                    <td>{$single_workorder_array[i].WORK_ORDER_COMMENT}</td>
                </tr>
            </table>
            <hr>
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        <p><b>Notes:</b><br />
                        {section name=b loop=$work_order_notes}
                        <b>Technician: </b>{$work_order_notes[b].EMPLOYEE_DISPLAY_NAME}<br>
                        <b>Date: </b> {$work_order_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format"}<br>
                        {$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}<br>
                        </p>
                        {/section}
                    </td>
                </tr>
            </table>
            <hr>
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td><b>Resolution:</b><br>
                    {section name=r loop=$work_order_res}
                        {if $work_order_res[r].EMPLOYEE_DISPLAY_NAME != ''}
                            <b>Closed By: </b>{$work_order_res[r].EMPLOYEE_DISPLAY_NAME}<br />
                            <b>Date: </b>{$work_order_res[r].WORK_ORDER_CLOSE_DATE|date_format:"$date_format"}<br />
                            {$work_order_res[r].WORK_ORDER_RESOLUTION}
                        {/if}
                    {/section}
                    </td>
                </tr>
                <tr>
                    <td><br><br><br><br><br><br><br><br></td>
                </tr>
            </table>
        </td>
        
        <!-- right column -->
        <td valign="top" width="20%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td valign="top"><b>Work Order:</b></td>
                    <td valign="top">#{$single_workorder_array[i].WORK_ORDER_ID}</td>
                </tr>
                <tr>
                    <td valign="top" nowrap><b>Opened:</b></td>
                    <td valign="top">{$single_workorder_array[i].WORK_ORDER_OPEN_DATE|date_format:"$date_format %I:%M %p"}</td>
                </tr>
                <tr>
                    <td valign="top"><b>Scheduled:</b></td>
                    <td>
                        {section name=e loop=$work_order_sched}
                            {$work_order_sched[e].SCHEDUAL_START|date_format:"$date_format %I:%M  %p"}
                        {/section}
                    </td>
                <tr>
                <tr>
                    <td valign="top" nowrap><b>Scope:</b></td>
                    <td valign="top">{$single_workorder_array[i].WORK_ORDER_SCOPE}</td>
                </tr>
                <tr>
                    <td valign="top"><b>Status:</b></td>
                    <td valign="top">{if $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "1"}
                            Created
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "2"}
                            Assigned
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "3"}
                            Waiting For Parts
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "6"}
                            Closed
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "7"}    
                            Waiting For Payment
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "8"}    
                            Payment Made
                        {elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "9"}    
                            Pending    
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td><b>Last Change:</b></td>
                    <td>{$single_workorder_array[i].LAST_ACTIVE|date_format:"$date_format"}</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td align="center" colspan="2"><b>Service Time</b></td>
                </tr>
                <tr>
                    <td><b>Origination</b></td>
                    <td>___/____/____ __:__</td>
                </tr>
                <tr>
                    <td><b>Arrival</b></td>
                    <td>___/____/____ __:__</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td align="center" colspan="2"><b>Signature</b></td>
                </tr>
                <tr>
                    <td><b>Client Name</b></td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td><b>Signature</b></td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td><b>Tech Name</b></td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td><b>Signature</b></td>
                    <td>__________________</td>
                </tr>
            </table>
            <hr>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td align="center"><b>Schedule</b></td>
                </tr><tr>
                    <td>
                        {section name=e loop=$work_order_sched}
                            <b>Start</b> {$work_order_sched[e].SCHEDUAL_START|date_format:"$date_format %I:%M  %p"}<br>
                            <b>End</b> {$work_order_sched[e].SCHEDUAL_END|date_format:"$date_format %I:%M  %p "}<br>
                            <b>Schedule Notes</b><br>
                                {$work_order_sched[e].SCHEDUAL_NOTES}
                        {sectionelse}
                            No schedule has been set. Click the day on the calendar you want to set the schedule.
                        {/section}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Footer -->
    <tr border="0">
        <td colspan="3" border="0" align="center">This Work Order is confidential and contains privileged information.</td>
    </tr>
</table>
{/section}
