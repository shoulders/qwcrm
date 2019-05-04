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
    
    <!-- Header Section -->
    
    <table width="750" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            
            <!-- Company Details -->
            <td valign="top" align="left" width="200">                
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
                        <td>{$company_details.website|regex_replace:"/^https?:\/\//":""}</td>
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

            <!-- LOGO and Company Name-->
            <td valign="top" align="center" width="300">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td width="100%" align="center"><img src="{$company_logo}" height="100" alt="" border="0"></td>                        
                    </tr>
                    <tr><td style="text-align:center"><b>{$company_details.company_name}</b></td></tr>
                </table>
            </td>

            <!-- Invoice details -->
            <td valign="top" align="right" width="200">
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
                                        <b>{t}Work Order{/t} - </b>{if !$workorder_details}{t}n/a{/t}{else}{$invoice_details.workorder_id}{/if}<br>
                                        <b>{t}Employee{/t} - </b>{$employee_display_name}<br>                                                                            
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            
        </tr>
    </table>
    <br>
    
    <!-- Workorder Row -->
    {if $workorder_details.scope}    
        <table width="750" border="0" cellpadding="3" cellspacing="0">
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
    <br>
    
    <!-- Invoice To Box -->
    <table width="750" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
            <td align="center" class="olotd5" style="font-size: 20px;">{t}Invoice{/t} - {$client_details.display_name}</td>
        </tr>
    </table>
    <br>

    <!-- Items Table Section -->

    <!-- Labour Table -->
    {if $labour_items}
        <table width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td><b>{t}Labour Items{/t}</b></td>
            </tr>
        </table>
        <table width="750" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>                
                <td class="olohead"><b>{t}Description{/t}</b></td>
                <td class="olohead" width="50" align="right"><b>{t}Unit Qty{/t}</b></td>                                                            
                <td class="olohead" width="50" align="right"><b>{t}Unit Net{/t}</b></td>
                {if $invoice_details.tax_system != 'none'}
                    <td class="olohead" width="50" align="right"><b>{t}Net{/t}</b></td>                
                    {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="olohead"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                    <td class="olohead" width="50" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                    <td class="olohead" width="50" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Applied{/t}</b></td>  
                {/if}
                <td class="olohead" width="50" align="right"><b>{t}Gross{/t}</b></td>                
            </tr>
            {section name=l loop=$labour_items}
                <tr class="olotd4">                    
                    <td>{$labour_items[l].description}</td>
                    <td>{$labour_items[l].unit_qty|string_format:"%.2f"}</td>                                                                
                    <td>{$currency_sym}{$labour_items[l].unit_net|string_format:"%.2f"}</td>    
                    {if $invoice_details.tax_system != 'none'}
                        <td>{$currency_sym}{$labour_items[l].sub_total_net|string_format:"%.2f"}</td>                      
                        {if $labour_items[l].vat_tax_code == 'T2' || $labour_items[l].sales_tax_exempt}
                            <td colspan="2" align="center">{t}Exempt{/t}</td>
                        {elseif '/^vat_/'|preg_match:$invoice_details.tax_system}
                            <td>
                                {section name=s loop=$vat_tax_codes}
                                    {if $labour_items[l].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                {/section}
                            </td>
                        {/if}                                                                    
                        <td>{$labour_items[l].unit_tax_rate|string_format:"%.2f"}%</td> 
                        <td>{$currency_sym}{$labour_items[l].sub_total_tax|string_format:"%.2f"}</td>                        
                    {/if}
                    <td>{$currency_sym}{$labour_items[l].sub_total_gross|string_format:"%.2f"}</td>                    
                </tr>
            {/section} 
        </table>
        <br>
        <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="text-align:right;"><b>{t}Labour{/t} {t}Totals{/t}</b></td>
                {if $invoice_details.tax_system != 'none'}
                    <td class="olotd4" width="80" align="right">{t}Net{/t}: {$currency_sym}{$labour_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                    <td class="olotd4" width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$labour_items_sub_totals.sub_total_tax|string_format:"%.2f"}</td>
                {/if}
                <td class="olotd4" width="80" align="right">{t}Gross{/t}: {$currency_sym}{$labour_items_sub_totals.sub_total_gross|string_format:"%.2f"}</td>
            </tr>
        </table>        
        <br>
    {/if}

    <!-- Parts Table -->
    {if $parts_items}
        <table width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td><b>{t}Parts Items{/t}</b></td>
            </tr>
        </table>
        <table width="750" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr class="olotd4">                
                <td class="olohead"><b>{t}Description{/t}</b></td>
                <td class="olohead" width="40" align="right"><b>{t}Unit Qty{/t}</b></td>                                                            
                <td class="olohead" width="40" align="right"><b>{t}Unit Net{/t}</b></td>
                {if $invoice_details.tax_system != 'none'}
                    <td class="olohead" width="40" align="right"><b>{t}Net{/t}</b></td>                
                    {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="olohead"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                    <td class="olohead" width="40" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                    <td class="olohead" width="40" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Applied{/t}</b></td>
                {/if}
                <td class="olohead" width="40" align="right"><b>{t}Gross{/t}</b></td>                
            </tr>
            {section name=p loop=$parts_items}
                <tr class="olotd4">                    
                    <td>{$parts_items[p].description}</td>
                    <td>{$parts_items[p].unit_qty|string_format:"%.2f"}</td>                                                                
                    <td>{$currency_sym}{$parts_items[p].unit_net|string_format:"%.2f"}</td>    
                    {if $invoice_details.tax_system != 'none'}
                        <td>{$currency_sym}{$parts_items[p].sub_total_net|string_format:"%.2f"}</td>
                        {if $parts_items[p].vat_tax_code == 'T2' || $parts_items[p].sales_tax_exempt}
                            <td colspan="2" align="center">{t}Exempt{/t}</td>
                        {elseif '/^vat_/'|preg_match:$invoice_details.tax_system}
                            <td>
                                {section name=s loop=$vat_tax_codes}
                                    {if $parts_items[p].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                {/section}
                            </td>
                        {/if}                                                                    
                        <td>{$parts_items[p].unit_tax_rate|string_format:"%.2f"}%</td> 
                        <td>{$currency_sym}{$parts_items[p].sub_total_tax|string_format:"%.2f"}</td>                        
                    {/if}
                    <td>{$currency_sym}{$parts_items[p].sub_total_gross|string_format:"%.2f"}</td>                
                </tr>
            {/section}                                                        
        </table>
        <br>
        <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="text-align:right;"><b>{t}Parts{/t} {t}Totals{/t}</b></td>
                {if $invoice_details.tax_system != 'none'}
                    <td class="olotd4" width="80" align="right">{t}Net{/t}: {$currency_sym}{$parts_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>                                                                        
                    <td class="olotd4" width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$parts_items_sub_totals.sub_total_tax|string_format:"%.2f"}</td>
                {/if}
                <td class="olotd4" width="80" align="right">{t}Gross{/t}: {$currency_sym}{$parts_items_sub_totals.sub_total_gross|string_format:"%.2f"}</td>
            </tr>            
        </table>        
        <br>
    {/if}
    
    <!-- Vouchers Table -->
    {if $display_vouchers}
        <table width="750" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td class="olohead"><b>{t}Voucher{/t} {t}Code{/t}</b></td>                
                <td class="olohead" width="80" align="right"><b>{t}Expiry Date{/t}</b></td>                
                <td class="olohead" width="80" align="right"><b>{t}Gross{/t}</b></td>
            </tr>
            {section name=p loop=$display_vouchers}        
                <tr class="olotd4">
                    <td class="olotd4">{$display_vouchers[p].voucher_code}</td>                    
                    <td class="olotd4" align="right">{$display_vouchers[p].expiry_date|date_format:$date_format}</td>                    
                    <td class="olotd4" align="right">{$currency_sym}{$display_vouchers[p].unit_gross}</td>
                </tr>
            {/section}            
        </table>
        <br>
        <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="text-align:right;"><b>{t}Voucher{/t} {t}Totals{/t}</b></td>
                {if $invoice_details.tax_system != 'none'}
                    <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$voucher_sub_totals.sub_total_net|string_format:"%.2f"}</td>                                            
                    <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$voucher_sub_totals.sub_total_tax|string_format:"%.2f"}</td>
                {/if}
                <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$voucher_sub_totals.sub_total_gross|string_format:"%.2f"}</td>
            </tr>
        </table>
        <br>
    {/if}

    <!-- Financial Section -->         
    
    <table width="750" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
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
                                        <img src="{$theme_images_dir}icons/deposit.jpeg" alt="" height="20"> <b>{t}Bank Transfer{/t}</b><br>
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
                                        <img src="{$theme_images_dir}icons/cheque.jpeg" alt="" height="20"> <b>{t}Cheques{/t}</b><br>                                
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
                                        <img src="{$theme_images_dir}paypal/pay_now.gif" height="20" alt="PayPal - The safer, easier way to pay online"> <b>{t}PayPal{/t}</b><br>
                                    </td>
                                </tr>
                            {/if}
                        
                        {/section}

                    <!-- If none of the above payment methods are enabled then display this message -->                        
                    {else}
                        <tr>
                            <td>{t}Please call us to discuss payment options.{/t}</td>
                        </tr>
                    {/if}

                </table>
            </td>           
            
           <!-- Totals Box -->
            <td colspan="2" valign="TOP">
                <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                    <tr>
                        <td class="olotd4" width="80%" align="right"><b>{t}Labour{/t}</b></td>
                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$labour_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4" width="80%" align="right"><b>{t}Parts{/t}</b></td>
                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$parts_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t} (@ {$invoice_details.discount_rate|string_format:"%.2f"}%)</b></td>
                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.discount_amount|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4" width="80%" align="right"><b>{t}Vouchers{/t}</b></td>
                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$voucher_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                    </tr>
                    {if $invoice_details.tax_system != 'none'}
                        <tr>
                            <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.net_amount|string_format:"%.2f"}</td>
                        </tr>
                        <tr>                                                            
                            <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$invoice_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.tax_amount|string_format:"%.2f"}</td>                                                            
                        </tr>
                    {/if}
                    <tr>
                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.gross_amount|string_format:"%.2f"}</td>
                    </tr> 
                </table>
            </td>
        </tr>
    </table>
    <br>
    <br>
    
    <!-- Footer Section -->    
    <table width="750" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
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
    
</body>
</html>