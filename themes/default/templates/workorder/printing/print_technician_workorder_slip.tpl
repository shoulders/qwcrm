<!-- print_technician_workorder_slip.tpl - Technician Work Order Slip Print Template -->
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{t}Invoice{/t} {$invoice_details.INVOICE_ID}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{$meta_description}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{$meta_keywords}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>

    <!-- Header Section -->
    <table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr bgcolor="#999999">
            <td width="20%" valign="middle" align="center"><img src="{$company_logo}" alt="" height="50"></td>
            <td valign="top" align="center">            
                <font size="+3">{t}Technician Workorder Slip{/t}</font><br />
                {t}Workorder ID{/t} {$single_workorder[i].WORK_ORDER_ID}
            </td>
            <td width="20%" valign="middle" align="center"></td>
        </tr>
    </table>

    <!-- Contact Information -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="50%" align="center" valign="middle"><b>{t}Customer Details{/t}</b></td>
            <td width="50%" align="center" valign="middle"><b>{t}Company Details{/t}</b></td>        
        </tr>    
        <tr>

            <!-- Customer Details -->
            <td valign="top">
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td>
                            <p><b><font size="+1">{$single_workorder[i].CUSTOMER_DISPLAY_NAME}</font></b></p>
                            <p>                            
                                <b>{t}Address{/t}:</b><br>
                                {$single_workorder[i].CUSTOMER_ADDRESS}<br>
                                {$single_workorder[i].CUSTOMER_CITY}, {$single_workorder[i].CUSTOMER_STATE} {$single_workorder[i].CUSTOMER_ZIP}
                            </p>
                            <p>
                                <b>{t}Contact{/t}: </b>{$single_workorder[i].CUSTOMER_FIRST_NAME} {$single_workorder[i].CUSTOMER_LAST_NAME}<br />
                                <b>{t}Phone{/t}: </b>{$single_workorder[i].CUSTOMER_PHONE}<br>
                                <b>{t}Work{/t}: </b>{$single_workorder[i].CUSTOMER_WORK_PHONE}<br>
                                <b>{t}Mobile{/t}: </b>{$single_workorder[i].CUSTOMER_MOBILE_PHONE}<br>
                                <b>{t}Email{/t}: </b>{$single_workorder[i].CUSTOMER_EMAIL}<br>                            
                            </p>
                            <p>
                                <b>{t}Type{/t}: </b> 
                                {if $single_workorder[i].CUSTOMER_TYPE == '1'}{t}CUSTOMER_TYPE_1{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '2'}{t}CUSTOMER_TYPE_2{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '3'}{t}CUSTOMER_TYPE_3{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '4'}{t}CUSTOMER_TYPE_4{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '5'}{t}CUSTOMER_TYPE_5{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '6'}{t}CUSTOMER_TYPE_6{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '7'}{t}CUSTOMER_TYPE_7{/t}{/if}
                                {if $single_workorder[i].CUSTOMER_TYPE == '8'}{t}CUSTOMER_TYPE_8{/t}{/if}
                                {if $single_workorder[i].CUSTOMER_TYPE == '9'}{t}CUSTOMER_TYPE_9{/t}{/if} 
                                {if $single_workorder[i].CUSTOMER_TYPE == '10'}{t}CUSTOMER_TYPE_10{/t}{/if}                            
                            </p>
                        </td>
                    </tr>
                </table>            
            </td>

            <!-- Company Details -->
            <td valign="top">
                <table cellpadding="4" cellspacing="0" border="0">                                
                    <tr>
                        <td>
                            <p><b><font size="+1">{$company_details.COMPANY_NAME}</font></b><br></p>
                            <p>
                                <b>{t}Address{/t}:</b><br>
                                {$company_details.COMPANY_ADDRESS}<br>
                                {$company_details.COMPANY_CITY}, {$company_details.COMPANY_STATE} {$company_details.COMPANY_ZIP}
                            </p>
                            <p>
                                <b>{t}Phone{/t}: </b>{$single_workorder[i].EMPLOYEE_WORK_PHONE}<br>                        
                                <b>{t}Mobile{/t}: </b>{$company_details.COMPANY_MOBILE}<br>
                                <b>{t}Fax{/t}: </b>{$company_details.COMPANY_FAX}<br>
                                <b>{t}Website{/t}: </b>{$company_details.COMPANY_WWW}<br>   
                                <b>{t}Email{/t}: </b>{$company_details.COMPANY_EMAIL}
                            </p>
                        </td>
                    </tr>                               
                </table>    
            </td>

        </tr>
    </table>

    <!-- Work Order Information -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>        
            <td valign="top" align="center" nowrap><b>{t}Work Order Details{/t}</b></td>        
            <td valign="top" align="center" nowrap><b>{t}Summary{/t}</b></td>
        </tr>
        <tr>

            <!-- Left Column -->
            <td valign="top" width="60%">
                <table border="0" cellpadding="4" cellspacing="0">               
                    <tr>
                        <td valign="top" nowrap><b>{t}Scope{/t}:</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap>{$single_workorder[i].WORK_ORDER_SCOPE}</td>
                    </tr>
                </table>

                <!-- Description -->
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Description{/t}:</b></td>
                    </tr>
                    <tr>
                        <td><div>{$single_workorder[i].WORK_ORDER_DESCRIPTION}</div></td>
                    </tr>
                </table>

                <!-- Comments -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Comments{/t}:</b></td>
                    </tr>
                    <tr>
                        <td><div style="min-height: 300px;">{$single_workorder[i].WORK_ORDER_COMMENT}</div></td>
                    </tr>
                </table>

                <!-- Resolution -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Resolution{/t}:</b></td>
                    </tr>                    
                    <tr>                                    
                        {if $single_workorder[i].EMPLOYEE_DISPLAY_NAME != ''}                            
                            <td><b>{t}Closed by{/t}:</b>{$single_workorder[i].EMPLOYEE_DISPLAY_NAME} on <b>{t}Date{/t}: </b>{$workorder_resolution[r].WORK_ORDER_CLOSE_DATE|date_format:$date_format}</td>                                                       
                        {/if}
                    </tr>
                    <tr>
                        <td><div style="min-height: 150px;">{$single_workorder[i].WORK_ORDER_RESOLUTION}</div></td>                 
                    </tr>                     
                </table>

                <!-- Notes -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Notes{/t}:</b></td>
                    </tr>
                    {section name=b loop=$workorder_notes}                        
                        {if $workorder_notes[b].WORK_ORDER_NOTES_DESCRIPTION != ''} 
                            <tr>
                                <td>
                                    <p>
                                        ------------------------------------------------------------------------------------<br>
                                        {t}This note was created by{/t} <b>{t}Technician{/t}: </b>{$workorder_notes[b].EMPLOYEE_DISPLAY_NAME} {t}on{/t} </b> {$workorder_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format %R"}
                                    </p>
                                    <div>{$workorder_notes[b].WORK_ORDER_NOTES_DESCRIPTION}</div>                       
                                </td>
                            </tr>
                        {else}
                            <tr>
                                <td><div>{t}There are no notes.{/t}</div></td>
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
                        <td valign="top" width="50%"><b>{t}Workorder ID{/t}</b></td>
                        <td valign="top">{$single_workorder[i].WORK_ORDER_ID}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Today's Date{/t}</b></td>
                        <td valign="top">{$smarty.now|date_format:$date_format}</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap><b>{t}Opened{/t}</b></td>
                        <td valign="top">{$single_workorder[i].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
                    </tr>                
                    <tr>
                        <td valign="top" nowrap><b>{t}Technician{/t}</b></td>
                        <td valign="top">{$single_workorder[i].EMPLOYEE_DISPLAY_NAME}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Status{/t}</b></td>
                        <td valign="top">
                            {if $single_workorder[i].WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '3'}{t}Waiting For Parts{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                            {if $single_workorder[i].WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{t}Last Activity{/t}:</b></td>
                        <td>{$single_workorder[i].LAST_ACTIVE|date_format:$date_format}</td>
                    </tr>
                </table>

                <!-- Service Time -->
                <hr>
                <table width="100%" border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td align="center" colspan="2"><b>{t}Service Times{/t}</b></td>
                    </tr>
                    <tr>
                        <td><b>{t}Departed from office{/t}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                    <tr>
                        <td><b>{t}Started Work Order{/t}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                    <tr>
                        <td><b>{t}Finished Work Order{/t}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                    <tr>
                        <td><b>{t}Returned to office{/t}</b></td>
                        <td>___/____/____ __:__</td>
                    </tr>
                </table>

                <!-- Schedule -->
                <hr>
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td align="center"><b>{t}Schedule{/t}</b></td>
                    </tr><tr>
                        <td>
                            {section name=e loop=$workorder_schedule}
                                <b>{t}Start Time{/t}:</b> {$workorder_schedules[e].SCHEDULE_START|date_format:"$date_format %R"}<br>
                                <b>{t}End Time{/t}:</b> {$workorder_schedules[e].SCHEDULE_END|date_format:"$date_format %R"}<br>
                                <b>{t}Notes{/t}:</b><br>
                                <div>{$workorder_schedule[e].SCHEDULE_NOTES}</div>
                            {sectionelse}
                                {t}No schedule has been set. Click the day on the calendar you want to set the schedule.{/t}
                            {/section}
                        </td>
                    </tr>
                </table>

                <!-- Signatures -->
                <hr>
                <table width="100%" border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td align="center" colspan="2"><b>{t}Signatures{/t}</b></td>
                    </tr>
                    <tr>
                        <td><b>{t}Client Name{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{t}Signature{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{t}Technician{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{t}Signature{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>                    

            </td>
        </tr>
    </table>

    <!-- Footer Section -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td colspan="3" border="0" align="center">{t}This Workorder is confidential and contains privileged information.{/t}</td>
        </tr>
    </table>

</body>
</html>