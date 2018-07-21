<!-- print_technician_job_sheet.tpl -->
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
    <title>{t}WORKORDER_PRINT_TECHNICIAN_JOB_SHEET_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}WORKORDER_PRINT_TECHNICIAN_JOB_SHEET_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}WORKORDER_PRINT_TECHNICIAN_JOB_SHEET_META_KEYWORDS{/t}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>

    <!-- Header Section -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="20%" valign="middle" align="center"><img src="{$company_logo}" alt="" height="50"></td>
            <td valign="top" align="center">            
                <font size="+3">{t}Technician Job Sheet{/t}</font><br />
                {t}Workorder ID{/t} {$workorder_details.workorder_id}
            </td>
            <td width="20%" valign="middle" align="center"></td>
        </tr>
    </table>

    <!-- Job Details -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
        <tr>

            <!-- Left Column -->
            <td style="width: 50%" valign="top">
                <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td style="width: 150px">                        
                            <b>{t}Client{/t}: </b><br>
                            <b>{t}Workorder ID{/t}: </b><br>     
                            <b>{t}Date{/t}: <b><br>
                            <b>{t}Opened{/t}: <b><br>     
                            <b>{t}Phone{/t}: <b><br>
                            <b>{t}Mobile{/t}: <b><br>
                            <b>{t}Email{/t}: <b><br>                        
                        </td>
                        <td>
                            {$client_details.display_name}<br>
                            {$workorder_details.workorder_id}<br>
                            {$smarty.now|date_format:$date_format}<br>
                            {$workorder_details.open_date|date_format:$date_format}<br>
                            {$client_details.primary_phone}<br>
                            {$client_details.mobile_phone}<br>
                            {$client_details.email}<br>
                        </td>        
                    </tr>    
                </table>
            </td>

            <!-- Right Column -->
            <td style="width: 50%" valign="top">
                <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td style="width: 150px">
                            <b>{t}Contact{/t}:<b>                            
                        </td>
                        <td>
                            {$client_details.first_name} {$client_details.last_name}                            
                        </td>        
                    </tr>
                    <tr>
                        <td style="width: 150px">                            
                            <b>{t}Address{/t}:<b>
                        </td>
                        <td>
                            {$client_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                            {$client_details.city}<br>
                            {$client_details.state}<br>
                            {$client_details.zip}<br>
                            {$client_details.country}
                        </td>        
                    </tr>
                </table>
            </td>      
        </tr>    
    </table>
    <br />
    
    <!-- Job Information -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
        
        <!-- Scope -->
        <tr>
            <td valign="top" nowrap><b>{t}Scope{/t}:</td>
        </tr>
        <tr>
            <td valign="top" nowrap>{$workorder_details.scope}</td>
        </tr>
        
        <!-- Description -->
        <tr>
            <td><b>{t}Description{/t}:<b></td>       
        </tr>
        <tr>
            <td><div style="min-height: 75px;">{$workorder_details.description}</div></td>
        </tr>
        <tr>
            <td><b>{t}Required Passwords{/t}:</b><br /><br /></td>
        </tr>
    </table>
    <br />

    <!-- Notes -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr style="border-bottom: 2px solid black;">
            <td style="width: 100px; text-align: center; border-right: 2px solid black;"><b>{t}Date{/t}</b></td>
            <td style="width: 100px; text-align: center; border-right: 2px solid black;"><b>{t}Start Time{/t}</b></td>
            <td style="width: 100px; text-align: center; border-right: 2px solid black;"><b>{t}End Time{/t}</b></td>
            <td style="text-align: center;"><b>{t}Notes{/t}</b></td>  
        </tr>
        <tr>
            <td style="width: 100px; text-align: center; border-right: 2px solid black; height: 340px;"></td>
            <td style="width: 100px; text-align: center; border-right: 2px solid black; height: 340px;"></td>
            <td style="width: 100px; text-align: center; border-right: 2px solid black; height: 340px;"></td>
            <td style="text-align: center; height: 300px;"></td>          
        </tr>
    </table>
    <br />

    <!-- Parts Used -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
        <tr>
            <td><b>{t}Parts Used{/t}:<b></td>       
        </tr>
        <tr>
            <td><div style="min-height: 100px;"></div></td>
        </tr>
    </table>
    <br />

    <!-- Resolution -->
    <table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border: 3px solid black;" bgcolor="#999999">
        <tr>
            <td><b>{t}Resolution{/t}:<b></td>       
        </tr>
        <tr>
            <td><div style="min-height: 100px;"></div></td>
        </tr>
        <tr>
            <td style="text-align: right;"><p><b>{t}Closed{/t}:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><br /><br /></td>
        </tr>
    </table>
    
</body>
</html>