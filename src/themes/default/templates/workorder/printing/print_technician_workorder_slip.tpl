<!-- print_technician_workorder_slip.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{t}WORKORDER_PRINT_TECHNICIAN_WORKORDER_SLIP_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}WORKORDER_PRINT_TECHNICIAN_WORKORDER_SLIP_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}WORKORDER_PRINT_TECHNICIAN_WORKORDER_SLIP_META_KEYWORDS{/t}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>

    <!-- Header Section -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr bgcolor="#999999">
            <td width="20%" valign="middle" align="center"><img src="{$company_logo}" alt="" height="50"></td>
            <td valign="top" align="center">            
                <font size="+3">{t}Technician Work Order Slip{/t}</font><br />
                {t}Workorder ID{/t} {$workorder_details.workorder_id}
            </td>
            <td width="20%" valign="middle" align="center"></td>
        </tr>
    </table>

    <!-- Contact Information -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="50%" align="center" valign="middle"><b>{t}Client Details{/t}</b></td>
            <td width="50%" align="center" valign="middle"><b>{t}Company Details{/t}</b></td>        
        </tr>    
        <tr>

            <!-- Client Details -->
            <td valign="top">
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td>
                            <p><b><font size="+1">{$client_details.display_name}</font></b></p>
                            <p>                            
                                <b>{t}Address{/t}:</b><br>
                                {$client_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                {$client_details.city}<br>
                                {$client_details.state}<br>
                                {$client_details.zip}<br>
                                {$client_details.country}
                            </p>
                            <p>
                                <b>{t}Contact{/t}: </b>{$client_details.first_name} {$client_details.last_name}<br />
                                <b>{t}Phone{/t}: </b>{$client_details.primary_phone}<br>
                                <b>{t}Mobile{/t}: </b>{$client_details.mobile_phone}<br>
                                <b>{t}Fax{/t}: </b>{$client_details.primary_phone}<br>                                
                                <b>{t}Email{/t}: </b>{$client_details.email}<br>                            
                            </p>
                            <p>
                                <b>{t}Type{/t}: </b> 
                                {section name=s loop=$client_types}    
                                    {if $client_details.type == $client_types[s].type_key}{t}{$client_types[s].display_name}{/t}{/if}        
                                {/section}                           
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
                            <p><b><font size="+1">{$company_details.company_name}</font></b><br></p>
                            <p>
                                <b>{t}Address{/t}:</b><br>
                                {$company_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                {$company_details.city}<br>
                                {$company_details.state}<br>
                                {$company_details.zip}<br>
                                {$company_details.country}
                            </p>
                            <p>
                                <b>{t}Phone{/t}: </b>{$employee_details.work_primary_phone}<br>                        
                                <b>{t}Mobile{/t}: </b>{$company_details.mobile_phone}<br>
                                <b>{t}Fax{/t}: </b>{$company_details.fax}<br>
                                <b>{t}Website{/t}: </b>{$company_details.website|regex_replace:"/^https?:\/\//":""}<br>   
                                <b>{t}Email{/t}: </b>{$company_details.email}
                            </p>
                        </td>
                    </tr>                               
                </table>    
            </td>

        </tr>
    </table>

    <!-- Work Order Information -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>        
            <td valign="top" align="center" nowrap><b>{t}Work Order Details{/t}</b></td>        
            <td valign="top" align="center" nowrap><b>{t}Summary{/t}</b></td>
        </tr>
        <tr>

            <!-- Left Column -->
            <td valign="top" width="60%">
                
                <!-- Scope -->
                <table border="0" cellpadding="4" cellspacing="0">               
                    <tr>
                        <td valign="top" nowrap><b>{t}Scope{/t}:</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap>{$workorder_details.scope}</td>
                    </tr>
                </table>

                <!-- Description -->
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Description{/t}:</b></td>
                    </tr>
                    <tr>
                        <td><div>{$workorder_details.description}</div></td>
                    </tr>
                </table>

                <!-- comment -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}comment{/t}:</b></td>
                    </tr>
                    <tr>
                        <td><div style="min-height: 250px;">{$workorder_details.comment}</div></td>
                    </tr>
                </table>

                <!-- Resolution -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Resolution{/t}:</b></td>
                    </tr>                    
                    <tr>                                    
                        {if $workorder_details.closed_on != ''}                            
                            <td><b>{t}Closed by{/t}:</b>{$employee_details.display_name} on <b>{t}Date{/t}: </b>{$workorder_details.closed_on|date_format:$date_format}</td>                                                       
                        {/if}
                    </tr>
                    <tr>
                        <td><div style="min-height: 150px;">{$workorder_details.resolution}</div></td>                 
                    </tr>                     
                </table>

                <!-- Notes -->
                <hr>
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Notes{/t}:</b></td>
                    </tr>
                    {section name=b loop=$workorder_notes}                        
                        {if $workorder_notes[b].description != ''} 
                            <tr>
                                <td>
                                    <p>
                                        ------------------------------------------------------------------------------------<br>
                                        {t}This note was created by{/t} <b>{t}Technician{/t}: </b>{$workorder_notes[b].employee_id} {t}on{/t} </b> {$workorder_notes[b].date|date_format:"$date_format %R"}
                                    </p>
                                    <div>{$workorder_notes[b].description}</div>                       
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
                        <td valign="top">{$workorder_details.workorder_id}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Today's Date{/t}</b></td>
                        <td valign="top">{$smarty.now|date_format:$date_format}</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap><b>{t}Opened{/t}</b></td>
                        <td valign="top">{$workorder_details.opened_on|date_format:$date_format}</td>
                    </tr>                
                    <tr>
                        <td valign="top" nowrap><b>{t}Technician{/t}</b></td>
                        <td valign="top">{$employee_details.display_name}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Status{/t}</b></td>
                        <td valign="top">
                            {section name=s loop=$workorder_statuses}    
                                {if $workorder_details.status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
                            {/section}                             
                        </td>
                    </tr>
                    <tr>
                        <td><b>{t}Last Activity{/t}:</b></td>
                        <td>{$workorder_details.last_active|date_format:$date_format}</td>
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
                            {section name=e loop=$workorder_schedules}
                                <b>{t}Start Time{/t}:</b> {$workorder_schedules[e].start_time|date_format:"$date_format %R"}<br>
                                <b>{t}End Time{/t}:</b> {$workorder_schedules[e].end_time|date_format:"$date_format %R"}<br>
                                <b>{t}Note{/t}:</b><br>
                                <div>{$workorder_schedules[e].note}</div>
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
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td colspan="3" border="0" align="center">{t}This Workorder is confidential and contains privileged information.{/t}</td>
        </tr>
    </table>

</body>
</html>