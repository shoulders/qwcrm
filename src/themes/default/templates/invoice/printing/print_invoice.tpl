<!-- print_invoice.tpl -->
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
    <title>{t}INVOICE_PRINT_INVOICE_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}INVOICE_PRINT_INVOICE_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}INVOICE_PRINT_INVOICE_META_KEYWORDS{/t}">
    
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
            
            <!-- Invoice Details -->
            <div id="company-name" style="float: right; width: 200px;">
                <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td align="top" class="olotd5">
                            <table width="180" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td>
                                        <b>{t}Invoice ID{/t} - </b>{$invoice_details.invoice_id}<br>
                                        <b>{t}Status{/t} - </b>
                                        {section name=s loop=$invoice_statuses}    
                                            {if $invoice_details.status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}        
                                        {/section}<br>
                                        <b>{t}Date{/t} - </b>{$invoice_details.date|date_format:$date_format}<br>
                                        <b>{t}Due Date{/t} - </b>{$invoice_details.due_date|date_format:$date_format}<br>                                                                                                                 
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

        <!-- Workorder Row -->
        {if $invoice_details.workorder_id}    
            <table width="675" border="0" cellpadding="3" cellspacing="0">
                <tr>
                    <td><b>{t}Work Order{/t} {t}Description{/t}</b></td>
                    <td><b>{t}Work Order{/t} {t}Resolution{/t}</b></td>
                </tr>
                <tr>
                    <td width="50%" valign="top"><div style="min-height: 100px;">{$workorder_details.description}</div></td>
                    <td width="50%" valign="top" style="border-left: 1px solid;"><div style="min-height: 100px;">{$workorder_details.resolution}</div></td>
                </tr>
            </table>
        {/if}
  
        {*<!-- Invoice To Box -->
        <table width="675" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td align="center" class="olotd5" style="font-size: 20px;">{t}Invoice{/t} - {$client_details.display_name}</td>
            </tr>
        </table>
        <br>*}

        <!-- Invoice Items Table Section -->
        {if $invoice_items}
            <table width="675" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td><b>{t}Invoice Items{/t}</b></td>
                </tr>
            </table>
            <table width="675" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>                
                    <td class="olohead"><b>{t}Description{/t}</b></td>
                    <td class="olohead" width="40" align="right"><b>{t}Unit Qty{/t}</b></td>
                    <td class="olohead" width="50" align="right">
                        {if $invoice_details.tax_system != 'no_tax'}
                            <b>{t}Unit Net{/t}</b>
                        {else}
                            <b>{t}Unit Gross{/t}</b> 
                        {/if}
                    </td>
                    <td class="olohead" width="50" align="right"><b>{t}Unit Discount{/t}</b></td>
                    {if $invoice_details.tax_system != 'no_tax'}
                        <td class="olohead" width="40" align="right"><b>{t}Net{/t}</b></td>                        
                        <td class="olohead" width="40" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                        <td class="olohead" width="40" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>  
                    {/if}
                    <td class="olohead" width="40" align="right"><b>{t}Gross{/t}</b></td>                
                </tr>            
                {section name=l loop=$invoice_items}
                    <tr class="olotd4">        
                        <td>{$invoice_items[l].description}</td>
                        <td>{$invoice_items[l].unit_qty|string_format:"%.2f"}</td>                                                                
                        <td>{$currency_sym}{$invoice_items[l].unit_net|string_format:"%.2f"}</td>
                        <td>{$currency_sym}{$invoice_items[l].unit_discount|string_format:"%.2f"}</td>
                        {if $invoice_details.tax_system != 'no_tax'}
                            <td>{$currency_sym}{$invoice_items[l].subtotal_net|string_format:"%.2f"}</td>
                            <td align="center">
                                {if $invoice_items[l].sales_tax_exempt}
                                    {t}Exempt{/t}
                                {elseif $invoice_items[l].vat_tax_code == 'T2'}
                                    {t}Exempt{/t}
                                {elseif $invoice_items[l].vat_tax_code == 'T9'}
                                    {t}n/a{/t}
                                {else}
                                    {$invoice_items[l].unit_tax_rate|string_format:"%.2f"}%
                                {/if}
                            </td>                            
                            <td>{$currency_sym}{$invoice_items[l].subtotal_tax|string_format:"%.2f"}</td>                                                                                             
                        {/if}
                        <td>{$currency_sym}{$invoice_items[l].subtotal_gross|string_format:"%.2f"}</td>                                                            
                    </tr>
                {/section} 
            </table>
            <br>
            
            {*
            <table style="margin-top: 10px;" width="675" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="text-align:right;"><b>{t}Invoice Items{/t} {t}Totals{/t}</b></td>
                    <td class="olotd4" width="80" align="right">{t}Discount{/t}: {$currency_sym}{$invoice_items_subtotals.subtotal_discount|string_format:"%.2f"}</td>
                    {if $invoice_details.tax_system != 'no_tax'}
                        <td class="olotd4" width="80" align="right">{t}Net{/t}: {$currency_sym}{$invoice_items_subtotals.subtotal_net|string_format:"%.2f"}</td>
                        <td class="olotd4" width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$invoice_items_subtotals.subtotal_tax|string_format:"%.2f"}</td>
                    {/if}
                    <td class="olotd4" width="80" align="right">{t}Gross{/t}: {$currency_sym}{$invoice_items_subtotals.subtotal_gross|string_format:"%.2f"}</td>
                </tr>
            </table>*}
            
            
        {/if}        

        <!-- Vouchers Table -->
        {*if $display_vouchers.total_results}
            <br><br>
            <table width="675" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td class="olohead"><b>{t}Voucher{/t} {t}Code{/t}</b></td>                
                    <td class="olohead" width="80" align="right"><b>{t}Expiry Date{/t}</b></td>                
                    {if $qw_tax_system != 'no_tax'}
                        <td class="olohead" nowrap>{t}Net{/t}</td>
                        <td class="olohead"><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                    {/if} 
                    <td class="olohead" width="80" align="right"><b>{t}Gross{/t}</b></td>
                </tr>
                {section name=p loop=$display_vouchers.records}      
                    <tr class="olotd4">
                        <td class="olotd4">{$display_vouchers.records[p].voucher_code}</td>                    
                        <td class="olotd4" align="right">{$display_vouchers.records[p].expiry_date|date_format:$date_format}</td>                    
                        {if $qw_tax_system != 'no_tax'}
                            <td class="olotd4">{$currency_sym}{$display_vouchers.records[p].unit_net}</td>
                            <td class="olotd4">{$currency_sym}{$display_vouchers.records[p].unit_tax}</td>
                        {/if}
                        <td class="olotd4" align="right">{$currency_sym}{$display_vouchers.records[p].unit_gross}</td>
                    </tr>
                {/section}            
            </table>
            <br>
            <table style="margin-top: 10px;" width="675" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="text-align:right;"><b>{t}Voucher{/t} {t}Totals{/t}</b></td>
                    {if $invoice_details.tax_system != 'no_tax'}
                        <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$voucher_subtotals.subtotal_net|string_format:"%.2f"}</td>                                            
                        <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$voucher_subtotals.subtotal_tax|string_format:"%.2f"}</td>
                    {/if}
                    <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$voucher_subtotals.subtotal_gross|string_format:"%.2f"}</td>
                </tr>
            </table>
            <br>
        {/if*}

        <!-- Financial Section -->         

        <table width="675" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>

                <!-- Payments Methods -->            
                <td colspan="1" valign="top">
                    <table width="530" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">

                        <!-- Only show payments section if there are valid ones enabled -->
                        {if $display_payment_instructions}
                            <tr>
                                <td align="left" ><font size="-1"><b>{t}Payment Instructions{/t}</b></font></td>
                            </tr>

                            {section name=s loop=$payment_methods}

                                <!-- Bank Transfer -->
                                {if $payment_methods[s].method_key == 'bank_transfer' && $payment_methods[s].enabled}
                                    <tr>
                                        <td>
                                            <b>{t}Bank Transfer{/t}</b>: <img src="{$theme_images_dir}icons/deposit.jpeg" alt="" height="20"><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>                                
                                            {t}Bank Account Name{/t}: {$payment_options.bank_account_name}<br>
                                            {t}Bank Name{/t}: {$payment_options.bank_name}<br>
                                            {t}Account Number{/t}: {$payment_options.bank_account_number}<br>
                                            {t}Sort Code{/t}: {$payment_options.bank_sort_code}<br>
                                            {if $payment_options.bank_iban}{t}IBAN{/t}: {$payment_options.bank_iban}{/if}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {$payment_options.invoice_bank_transfer_msg}
                                        </td>
                                    </tr>
                                {/if}

                                <!-- Cheque -->                        
                                {if $payment_methods[s].method_key == 'cheque' && $payment_methods[s].enabled}
                                    <tr>
                                        <td>                                    
                                            <b>{t}Cheques{/t}</b>: <img src="{$theme_images_dir}icons/cheque.jpeg" alt="" height="20"><br>                                
                                        </td>                                
                                    </tr>
                                    <tr>
                                        <td>{$payment_options.invoice_cheque_msg}</td>
                                    </tr>
                                {/if}                        

                                <!-- PayPal -->
                                {if $payment_methods[s].method_key == 'paypal' && $payment_methods[s].enabled}
                                    <tr>
                                        <td>
                                            <b>{t}PayPal{/t}</b>: <img src="{$theme_images_dir}paypal/pay_now.gif" height="20" alt="PayPal - The safer, easier way to pay online"><br>
                                        </td>
                                    </tr>
                                {/if}

                            {/section}

                        <!-- If no_tax of the above payment methods are enabled then display this message -->                        
                        {else}
                            <tr>
                                <td>{t}Please call us to discuss payment options.{/t}</td>
                            </tr>
                        {/if}

                    </table>
                </td>           

               <!-- Totals Box -->
                <td colspan="2" valign="top">
                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                        <tr>
                            <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t}</b></td>
                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_discount|string_format:"%.2f"}</td>
                        </tr>
                        {if $invoice_details.tax_system != 'no_tax'}
                            <tr>
                                <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_net|string_format:"%.2f"}</td>
                            </tr>
                            <tr>                                                            
                                <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$invoice_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_tax|string_format:"%.2f"}</td>                                                            
                            </tr>
                        {/if}
                        <tr>
                            <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_gross|string_format:"%.2f"}</td>
                        </tr> 
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <br>

        <!-- Footer Section -->    
        <table width="675" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            {if $client_details.credit_terms}
                <tr>
                    <td align="center">
                        <b>{t}Credit Terms{/t}:</b> {$client_details.credit_terms}
                    </td>
                </tr>
            {/if}
            {if '/^vat_/'|preg_match:$invoice_details.tax_system}
                <tr>
                    <td align="center"><b>{t}VAT Number{/t}:</b> {$company_details.vat_number}</td>
                </tr>
            {/if}
            <tr>
                <td align="center">{$payment_options.invoice_footer_msg}</td>
            </tr>
        </table>

    </div>        
</body>
</html>