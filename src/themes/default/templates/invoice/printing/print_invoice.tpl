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
                        <td>{$company_details.website}</td>
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
                    <tr><td style="text-align:center"><b>{$company_details.display_name}</b></td></tr>
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
                                        <b>{t}Employee{/t} - </b>{$employee_details.display_name}<br>                                                                            
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
            <td align="center" class="olotd5" style="font-size: 20px;">{t}Invoice{/t} - {$customer_details.display_name}</td>
        </tr>
    </table>
    <br>

    <!-- Items Table Section -->

    <!-- Labour Table -->
    {if $labour_items}
        <table width="750" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td class="olohead" width="40"><b>{t}Qty{/t}</b></td>
                <td class="olohead"><b>{t}Labour Items{/t}</b></td>
                <td class="olohead" width="60" align="right"><b>{t}Unit Price{/t}</b></td>
                <td class="olohead" width="80" align="right"><b>{t}Sub Total{/t}</b></td>
            </tr>
            {section name=l loop=$labour_items}
                <tr class="olotd4">
                    <td class="olotd4" width="40">{$labour_items[l].qty}</td>
                    <td class="olotd4">{$labour_items[l].description}</td>
                    <td class="olotd4" width="60" align="right">{$currency_sym}{$labour_items[l].amount|string_format:"%.2f"}</td>
                    <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_items[l].sub_total|string_format:"%.2f"}</td>
                </tr>
            {/section}
            <tr>
                <td colspan="3" style="text-align:right;"><b>{t}Labour Total{/t}</b></td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
            </tr>
        </table>
        <br>
    {/if}

    <!-- Parts Table -->
    {if $parts_items}
        <table width="750" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="40" class="olohead"><b>{t}Qty{/t}</b></td>
                <td class="olohead"><b>{t}Parts Items{/t}</b></td>
                <td class="olohead" width="60" align="right"><b>{t}Unit Price{/t}</b></td>
                <td class="olohead" width="80" align="right"><b>{t}Sub Total{/t}</b></td>
            </tr>
            {section name=p loop=$parts_items}        
                <tr class="olotd4">
                    <td class="olotd4" width="40">{$parts_items[p].qty}</td>
                    <td class="olotd4">{$parts_items[p].description}</td>
                    <td class="olotd4" width="60" align="right">{$currency_sym}{$parts_items[p].amount|string_format:"%.2f"}</td>
                    <td class="olotd4" width="80" align="right">{$currency_sym}{$parts_items[p].sub_total|string_format:"%.2f"}</td>
                </tr>
            {/section}
            <tr>           
                <td colspan="3" style="text-align:right;"><b>{t}Parts Total{/t}</b></td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</td>
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
                    {if $payment_accepted_methods_statuses.cheque || $payment_accepted_methods_statuses.direct_deposit || $payment_accepted_methods_statuses.paypal}
                        <tr>
                            <td align="left" ><font size="-1"><b>{t}Payment Instructions{/t}</b></font></td>
                        </tr>

                        <!-- Cheque -->                        
                        {if $payment_accepted_methods_statuses.cheque}
                            <tr>
                                <td>                                    
                                    <img src="{$theme_images_dir}icons/cheque.jpeg" alt="" height="20"> <b>{t}Cheques{/t}</b><br>                                
                                </td>                                
                            </tr>
                            <tr>
                                <td>{$payment_options.invoice_cheque_msg}</td>
                            </tr>
                        {/if}

                        <!-- Direct Deposit -->
                        {if $payment_accepted_methods_statuses.direct_deposit}
                            <tr>
                                <td>
                                    <img src="{$theme_images_dir}icons/deposit.jpeg" alt="" height="20"> <b>{t}Direct Deposit{/t}</b><br>
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
                                    {$payment_options.invoice_direct_deposit_msg}
                                </td>
                            </tr>
                        {/if}

                        <!-- PayPal -->
                        {if $payment_accepted_methods_statuses.paypal}
                        <tr>
                            <td>
                                <img src="{$theme_images_dir}paypal/pay_now.gif" height="20" alt="PayPal - The safer, easier way to pay online"> <b>{t}PayPal{/t}</b><br>
                            </td>
                        </tr>
                    {/if}

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
                <table width="220" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td class="olotd4" align="left"><b>{t}Sub Total{/t}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice_details.sub_total|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{t}Discount{/t} (@ {$invoice_details.discount_rate|string_format:"%.2f"}%)</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details.discount_amount|string_format:"%.2f"}</td>
                    </tr>
                    {if $invoice_details.tax_type != 'none'}
                        <tr>
                            <td class="olotd4"><b>{t}Net{/t}</b></td>
                            <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details.net_amount|string_format:"%.2f"}</td>
                        </tr>                    
                        <tr>
                            <td class="olotd4"><b>
                                    {if $invoice_details.tax_type == 'vat'}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} (@ {$invoice_details.tax_rate}%)</b></td>
                            <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details.tax_amount|string_format:"%.2f"}</td>
                        </tr>
                    {/if}
                    <tr>
                        <td class="olotd4"><b>{t}Total{/t} ({t}Gross{/t})</b></td>
                        <td class="olotd4" width="80" align="right"><b>{$currency_sym}{$invoice_details.gross_amount|string_format:"%.2f"}</b></td>
                    </tr>
                    {*<tr>
                        <td class="olotd4"><b>{t}Balance{/t}</b></td>
                        <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice_details.balance|string_format:"%.2f"}</font></b></td>                        
                    </tr>*}
                </table>
            </td>
        </tr>
    </table>
    <br>
    <br>
    
    <!-- Footer Section -->    
    <table width="750" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        {if $customer_details.credit_terms != ''}
            <tr>
                <td align="center">
                    <b>{t}Credit Terms{/t}:</b> {$customer_details.credit_terms}
                </td>
            </tr>
        {/if}
        {if $invoice_details.tax_type == 'vat'}
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