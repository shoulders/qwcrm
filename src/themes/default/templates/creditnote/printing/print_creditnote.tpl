<!-- print_creditnote.tpl -->
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
    <title>{t}CREDITNOTE_PRINT_CREDITNOTE_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}CREDITNOTE_PRINT_CREDITNOTE_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}CREDITNOTE_PRINT_CREDITNOTE_META_KEYWORDS{/t}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>
    
    <!-- Margins - mPDF Workaround -->
    <div style="//padding-top: 55px;">
    
        <!-- Header Section 676px max + 55px margins width in mPDF -->        
        <div style="height: 85px; width: 675px;">

            <!-- Logo -->
            <div id="logo" style="float: left; width: 150px;">
                {if $company_logo}<img src="{$company_logo}" alt="" style="max-height: 75px; max-width: 150px;">{/if}
            </div>
            
            <!-- Company Name -->
            <div id="company-name" style="float: left; width: 325px;">
                <span style="font-size: 18px; font-weight: bold;"> <b>{$company_details.company_name}</b></span>
            </div>
            
            <!-- Credit Note Details -->
            <div id="company-name" style="float: right; width: 200px;">
                <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td align="top" class="olotd5">
                            <table width="180" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td>
                                        <b>{t}Credit Note ID{/t} - </b>{$creditnote_details.creditnote_id}<br>                                                                                           
                                        {if $creditnote_details.type == 'sales'}
                                            <b>{t}Invoice ID{/t} - </b>
                                            {if $creditnote_details.invoice_id}{$creditnote_details.invoice_id}{else}{t}n/a{/t}{/if}</b><br>                                                       
                                        {else}
                                            <b>{t}Expense ID{/t} - </b>
                                            {if $creditnote_details.expense_id}{$creditnote_details.expense_id}{else}{t}n/a{/t}{/if}</b><br>                                                   
                                        {/if}                                        
                                        <b>{t}Status{/t} - </b>
                                        {section name=s loop=$creditnote_statuses}    
                                            {if $creditnote_details.status == $creditnote_statuses[s].status_key}{t}{$creditnote_statuses[s].display_name}{/t}{/if}        
                                        {/section}<br>
                                        <b>{t}Date{/t} - </b>{$creditnote_details.date|date_format:$date_format}<br>
                                        <b>{t}Exipry Date{/t} - </b>{$creditnote_details.expiry_date|date_format:$date_format}<br>
                                        <b>{t}Reference{/t} - </b>{$creditnote_details.reference}<br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="clear: both;"></div>
        
        <!-- Envelope Window Section -->        
        <div style="width: 675px; height: 150px;">
            
            <!-- Customer details (in Envelope Window) -->
            <div style="float: left; width: 310px; height: 110px; padding: 20px; background: lightgrey; border-radius: 5px; border: 1px solid black; overflow: hidden;">
               <span style="font-size: 13px; font-weight: bold;">                   
                    {$client_details.display_name}<br>               
                    {$client_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                    {$client_details.city}<br>
                    {$client_details.state}<br>
                    {$client_details.zip}<br>
                    {$client_details.country}                   
                </span>
            </div>            
            
            <!-- Company Details -->
            <div style="float: right; width: 320px; padding-top: 10px; margin: ">
                <div style="width: 250px; margin-left: auto; overflow: hidden;">
                    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td valign="top" align="left">                
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                    <tr>                                        
                                        <td valign="top"><b>{t}Address{/t} :&nbsp;</b></td>
                                        <td>
                                            {$company_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                            {$company_details.city}<br>
                                            {$company_details.state}<br>
                                            {$company_details.zip}<br>
                                            {$company_details.country}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{t}Phone{/t} :&nbsp;</b></td>
                                        <td>{$company_details.primary_phone}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{t}Website{/t} :&nbsp;</b></td>
                                        <td>{$company_details.website|regex_replace:"/^https?:\/\//":""|regex_replace:"/\/$/":""}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{t}Email{/t} :&nbsp;</b></td>
                                        <td>{$company_details.email}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{t}Company Number{/t} :&nbsp;</b></td>
                                        <td>{$company_details.company_number}</td>
                                    </tr>
                                </table>     
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <br>

        <!-- Credit Note Items Table Section -->
        {if $creditnote_items}
            <table width="675" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td><b>{t}Items{/t}</b></td>
                </tr>
            </table>
            <table width="675" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>                
                    <td class="olohead"><b>{t}Description{/t}</b></td>
                    <td class="olohead" width="40" align="right"><b>{t}Unit Qty{/t}</b></td>
                    <td class="olohead" width="50" align="right">
                        {if $creditnote_details.tax_system != 'no_tax'}
                            <b>{t}Unit Net{/t}</b>
                        {else}
                            <b>{t}Unit Gross{/t}</b>
                        {/if}
                    </td>
                    <td class="olohead" width="50" align="right"><b>{t}Unit Discount{/t}</b></td>
                    {if $creditnote_details.tax_system != 'no_tax'}
                        <td class="olohead" width="40" align="right"><b>{t}Net{/t}</b></td>                        
                        <td class="olohead" width="40" align="right"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                        <td class="olohead" width="40" align="right"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>  
                    {/if}
                    <td class="olohead" width="40" align="right"><b>{t}Gross{/t}</b></td>                
                </tr>            
                {section name=l loop=$creditnote_items}
                    <tr class="olotd4">        
                        <td>{$creditnote_items[l].description}</td>
                        <td>{$creditnote_items[l].unit_qty|string_format:"%.2f"}</td>                                                                
                        <td>{$currency_sym}{$creditnote_items[l].unit_net|string_format:"%.2f"}</td>
                        <td>{$currency_sym}{$creditnote_items[l].unit_discount|string_format:"%.2f"}</td>                        
                        {if $creditnote_details.tax_system != 'no_tax'}
                            <td>{$currency_sym}{$creditnote_items[l].subtotal_net|string_format:"%.2f"}</td>
                            <td align="center">
                                {if $creditnote_items[l].sales_tax_exempt}
                                    {t}Exempt{/t}
                                {elseif $creditnote_items[l].vat_tax_code == 'T2'}
                                    {t}Exempt{/t}
                                {elseif $creditnote_items[l].vat_tax_code == 'T9'}
                                    {t}n/a{/t}
                                {else}
                                    {$creditnote_items[l].unit_tax_rate|string_format:"%.2f"}%
                                {/if}
                            </td>
                            <td>{$currency_sym}{$creditnote_items[l].subtotal_tax|string_format:"%.2f"}</td>
                        {/if}
                        <td>{$currency_sym}{$creditnote_items[l].subtotal_gross|string_format:"%.2f"}</td>                                                            
                    </tr>
                {/section} 
            </table>
            <br>            
        {/if}      

        <!-- Financial Section -->
        <table width="675" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="500">&nbsp;</td>
               <!-- Totals Box -->
                <td colspan="2" valign="top">
                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                        <tr>
                            <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t}</b></td>
                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_discount|string_format:"%.2f"}</td>
                        </tr>
                        {if $creditnote_details.tax_system != 'no_tax'}
                            <tr>
                                <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_net|string_format:"%.2f"}</td>
                            </tr>
                            <tr>                                                            
                                <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$creditnote_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_tax|string_format:"%.2f"}</td>                                                            
                            </tr>
                        {/if}
                        <tr>
                            <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_gross|string_format:"%.2f"}</td>
                        </tr> 
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <br>

        <!-- Footer Section -->    
        <table width="675" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">            
            {if '/^vat_/'|preg_match:$creditnote_details.tax_system}
                <tr>
                    <td align="center"><b>{t}VAT Number{/t}:</b> {$company_details.vat_number}</td>
                </tr>
            {/if}
            {if $creditnote_details.note}
                <tr>
                    <td>
                        <div style="width: 300px; word-wrap: break-word;">
                            <div><strong>{t}Reason for Credit Note{/t}</strong></div>
                            <div>{$creditnote_details.note}</div>
                        </div>
                    </td>
                </tr>
            {/if}
            <tr>
                <td>
                    <strong>{t}This Credit Note will expire on{/t}: </strong>{$creditnote_details.expiry_date|date_format:$date_format}
                </td>
            </tr>
            <tr>
                <td align="center">{$creditnote_footer_msg}</td>
            </tr>
        </table>

    </div>        
</body>
</html>