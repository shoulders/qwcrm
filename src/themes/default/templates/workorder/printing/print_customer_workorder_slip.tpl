<!-- print_customer_workorder_slip.tpl -->
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
    <title>{t}WORKORDER_PRINT_CUSTOMER_WORKORDER_SLIP_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}WORKORDER_PRINT_CUSTOMER_WORKORDER_SLIP_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}WORKORDER_PRINT_CUSTOMER_WORKORDER_SLIP_META_KEYWORDS{/t}">
    
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
                <font size="+3">{t}Customer Work Order Slip{/t}</font><br />
                {t}Workorder ID{/t} {$workorder_details.workorder_id}
            </td>
            <td width="20%" valign="middle" align="center"></td>
        </tr>
    </table>

    <!-- Contact Information -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="50%" align="center" valign="middle"><b>{t}Customer Details{/t}</b></td>
            <td width="50%" align="center" valign="middle"><b>{t}Company Details{/t}</b></td>        
        </tr>    
        <tr>

            <!-- Client Details -->
            <td valign="top">
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td>
                            <p><b><font size="+1">{$customer_details.display_name}</font></b></p>
                            <p>                            
                                <b>{t}Address{/t}:</b><br>
                                {$customer_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                {$customer_details.city}<br>
                                {$customer_details.state}<br>
                                {$customer_details.zip}<br>
                                {$customer_details.country}
                            </p>
                            <p>
                                <b>{t}Contact{/t}: </b>{$customer_details.contact_name}<br />
                                <b>{t}Phone{/t}: </b>{$customer_details.primary_phone}<br>                                
                                <b>{t}Mobile{/t}: </b>{$customer_details.mobile_phone}<br>
                                <b>{t}Email{/t}: </b>{$customer_details.email}<br>                            
                            </p>
                            <p>
                                <b>{t}Type{/t}: </b> 
                                {section name=s loop=$customer_types}    
                                    {if $customer_details.type == $customer_types[s].customer_type_id}{t}{$customer_types[s].display_name}{/t}{/if}        
                                {/section}                             
                            </p>
                        </td>
                    </tr>
                </table>            
            </td>

            <!-- Company Info -->
            <td valign="top">
                <table cellpadding="4" cellspacing="0" border="0">                                  
                    <tr>
                        <td>
                            <p><b><font size="+1">{$company_details.display_name}</font></b><br></p>
                            <p>
                                <b>{t}Address{/t}:</b><br>
                                {$company_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                {$company_details.city}<br>
                                {$company_details.state}<br>
                                {$company_details.zip}<br>
                                {$company_details.country}
                            </p>
                            <p>
                                <b>{t}Phone{/t}: </b>{$company_details.primary_phone}<br>                        
                                <b>{t}Mobile{/t}: </b>{$company_details.mobile_phone}<br>
                                <b>{t}Fax{/t}: </b>{$company_details.fax}<br>
                                <b>{t}Website{/t}: </b>{$company_details.website}<br>   
                                <b>{t}Email{/t}: </b>{$company_details.email}
                            </p>
                        </td>
                    </tr>                                  
                </table>    
            </td>

        </tr>
    </table>

    <!-- Work Order Details -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
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
                        <td valign="top">{$workorder_details.open_date|date_format:$date_format}</td>
                    </tr>                
                    <tr>
                        <td valign="top" nowrap><b>{t}Employee{/t}</b></td>
                        <td valign="top">{$employee_details.display_name}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Workorder Status{/t}</b></td>
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
                        <td><b>{t}Employee{/t}</b></td>
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

    <!-- Equipment Receipt -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center"><b>{t}Equipment Receipt{/t}</b></td>
        </tr>
        <tr>
            <td border="0" align="center">{t}Below is a list of the equipment you have left with us. Please check and make sure the list is correct as it cannot be remedied later.{/t}</td>        
        </tr>
        <tr>
            <td>
                <br>
                <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            {t}Description{/t} : <input type="text" size="40" name="ddt1"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt2"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt3"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt4"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt5"><br><br>                                                                     
                        </td>
                        <td>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt1"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt2"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt3"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt4"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt5"><br><br>                                                                       
                        </td>
                        <td>
                            {t}Qty{/t} : <input type="text" size="3" name="tt1"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt2"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt3"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt4"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt5"><br><br>                                                                    
                        </td>
                    </tr>
                </table>
            </td>
        </tr>  
    </table>

    <!-- Disclaimer -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center"><b>{t}Disclaimer{/t}</b></td>
        </tr>
        <tr>
            <td border="0" align="center">{t}We have a duty of care to preserve your computers data whilst we are servicing it, however it is the customers responsibilty to ensure that this data is reliably backed before work is started just incase data loss should occur. If you do not agree to these terms you cannot use our services.{/t}</td>        
        </tr>
    </table>

    <!-- Important Notes -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center">
                <b>{t}Important Notes{/t}</b></td>
        </tr>
        <tr>
            <td border="0">
                <ul>
                    <li>{t}Please hold onto this receipt as proof of service request.{/t}</li>
                    <li>{t}This document (copies will not be accepted) MUST be produce this at time of pickup. If this can't be provided then photo identification is required.{/t}</li>
                </ul>
            </td>        
        </tr>
    </table>

    <!-- Footer Section -->
    <table width="750" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center">{t}This Work Order is confidential and contains privileged information.{/t}</td>
        </tr>
    </table>
        
</body>
</html>