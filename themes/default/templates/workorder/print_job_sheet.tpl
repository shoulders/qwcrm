<!-- print_job_sheet_slip.tpl - Job Sheet Print Template -->
<!-- Header -->
{section name=i loop=$single_work_order}
<table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <td width="20%" valign="middle" align="center"><img src="{$company_logo}" alt="" height="50"></td>
        <td width="60%" align="center">
            {section name=d loop=$company}
                <p><b><font size="+3">{$company[d].COMPANY_NAME}</font></b><br></p>
            {/section}  
        </td>
        <td width="20%" valign="middle" align="center">{$translate_workorder_print_job_sheet_title}</font></td>
    </tr>
</table>
        
<!-- Job Details -->
<table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
    <tr>
        <td style="width: 50%">
            <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="width: 150px">                        
                        <b>{$translate_workorder_customer}: </b><br>
                        <b>{$translate_workorder_work_order_id}: </b><br>     
                        <b>{$translate_workorder_date}: <b><br>
                        <b>{$translate_workorder_phone}: <b><br>
                        <b>{$translate_workorder_mobile}: <b><br>
                        <b>{$translate_workorder_email}: <b><br>                        
                    </td>
                    <td>
                        {$single_work_order[i].CUSTOMER_FIRST_NAME} {$single_work_order[i].CUSTOMER_LAST_NAME}<br>
                        {$single_work_order[i].WORK_ORDER_ID}<br>
                        {$smarty.now|date_format:$date_format} opn date - {$single_work_order[i].WORK_ORDER_OPEN_DATE|date_format:$date_format}<br>
                        {$single_work_order[i].CUSTOMER_WORK_PHONE}<br>
                        {$single_work_order[i].CUSTOMER_MOBILE_PHONE}<br>
                        {$single_work_order[i].CUSTOMER_EMAIL}<br>
                    </td>        
                </tr>    
            </table>
        </td>
        <td style="width: 50%">
            <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="width: 150px">
                        <b>{$translate_workorder_contact}: <b><br><br>
                        <b>{$translate_workorder_address}: <b><br><br>
                    </td>
                    <td>
                        {$single_work_order[i].CUSTOMER_ADDRESS}<br>
                        {$single_work_order[i].CUSTOMER_CITY}<br>
                        {$single_work_order[i].CUSTOMER_STATE}<br>
                        {$single_work_order[i].CUSTOMER_ZIP}<br />
                    </td>        
                </tr>    
            </table>
        </td>           
        </td>        
    </tr>    
</table>
<br />

<!-- Job Description -->
<table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
    <tr>
        <td><b>{$translate_workorder_details_description_title}:<b></td>       
    </tr>
    <tr>
        <td><div style="min-height: 140px;">{$single_work_order[i].WORK_ORDER_DESCRIPTION}</div></td>
    </tr>
    <tr>
        <td><b>Req. Passwords:</b><br /><br /></td>
    </tr>
</table>
<br />

<!-- Notes -->
<table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr style="border-bottom: 2px solid black;">
        <td style="width: 100px; text-align: center; border-right: 2px solid black;"><b>Date</b></td>
        <td style="width: 100px; text-align: center; border-right: 2px solid black;"><b>Start Time</b></td>
        <td style="width: 100px; text-align: center; border-right: 2px solid black;"><b>End Time</b></td>
        <td style="text-align: center;"><b>Notes</b></td>  
    </tr>
    <tr>
        <td style="width: 100px; text-align: center; border-right: 2px solid black; height: 500px;"></td>
        <td style="width: 100px; text-align: center; border-right: 2px solid black; height: 500px;"></td>
        <td style="width: 100px; text-align: center; border-right: 2px solid black; height: 500px;"></td>
        <td style="text-align: center; height: 500px;"></td>          
    </tr>
</table>
<br />

<!-- Parts Used -->
<table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
    <tr>
        <td><b>Parts Used:<b></td>       
    </tr>
    <tr>
        <td><div style="min-height: 100px;"></div></td>
    </tr>
</table>
<br />

<!-- Work Carried Out-->
<table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
    <tr>
        <td><b>Work Carried Out<b></td>       
    </tr>
    <tr>
        <td><div style="min-height: 100px;"></div></td>
    </tr>
    <tr>
        <td style="text-align: right;"><p><b>Completed:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><br /><br /></td>
    </tr>
</table>
{/section}
