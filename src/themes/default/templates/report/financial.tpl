<!-- financial.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>    
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Financial Report{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REPORT_FINANCIAL_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REPORT_FINANCIAL_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">
                        
                        <!-- Date Range -->
                        <form action="index.php?component=report&page_tpl=financial" method="post" name="stats_report" id="stats_report">
                            
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Date Range{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                        <tr align="left">
                                                            <td><b>{t}Report Date From{/t}: </b></td>
                                                            <td><b>{t}Report Date To{/t}: </b></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left">
                                                                <input id="start_date" name="start_date" class="olotd5" size="10" value="{$start_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                                <button type="button" id="start_date_button">+</button>
                                                                <script>                            
                                                                    Calendar.setup( {
                                                                        trigger     : "start_date_button",
                                                                        inputField  : "start_date",
                                                                        dateFormat  : "{$date_format}"                                                                                            
                                                                    } );                            
                                                                </script>                
                                                            </td>
                                                            <td>
                                                                <input id="end_date" name="end_date" class="olotd5" size="10" value="{$end_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                                <button type="button" id="end_date_button">+</button>
                                                                <script>                            
                                                                    Calendar.setup( {
                                                                        trigger     : "end_date_button",
                                                                        inputField  : "end_date",
                                                                        dateFormat  : "{$date_format}"                                                                                            
                                                                    } );                            
                                                                </script>                    
                                                            </td>
                                                        </tr>                                                        
                                                    </table>
                                                </td>
                                            </tr>                                            
                                        </table>
                                    </td>
                                </tr>
                            </table>
                                                        
                            <!-- Submit Button -->
                            <br />
                            <div style="width: 65%; text-align: center;"><button type="submit" name="submit" value="submit">{t}Submit{/t}</button></div>
                            <br />
                            
                        </form>                
                        
                        <!-- The Report Section -->                        
                        
                        {if $enable_report_section}
                            <!-- Basic Statistics -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Basic Statisitics{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">

                                                        <tr>
                                                            <td class="olohead">{t}Clients{/t}</td>
                                                            <td class="olohead">{t}Work Orders{/t}</td>
                                                            <td class="olohead">{t}Invoices{/t}</td>
                                                        </tr>                                                    

                                                        <tr>

                                                            <!-- Clients -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}New{/t}:</b></td>
                                                                        <td><font color="red"><b> {$client_stats.count_new}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <!-- Workorders -->
                                                            <td class="olotd4" valign="top">
                                                                <table >
                                                                    <tr>
                                                                        <td><b>{t}Opened{/t}:</b></td>
                                                                        <td><font color="red"><b> {$workorder_stats.count_opened}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Closed{/t}:</b></td>
                                                                        <td><font color="red"><b> {$workorder_stats.count_closed}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <!-- Invoices -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Opened{/t}:</b></td>
                                                                        <td><font color="red"><b> {$invoice_stats.count_opened}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Closed{/t}:</b></td>
                                                                        <td><font color="red"><b> {$invoice_stats.count_closed}</b></font></td>
                                                                    </tr>                                                                                                                                        
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Discounted{/t}:</b></td>
                                                                        <td><font color="red"><b> {$invoice_stats.count_closed_discounted}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Paid{/t}:</b></td>
                                                                        <td><font color="red"><b> {$invoice_stats.count_closed_paid}</b></font></td>
                                                                    </tr>                                                                     
                                                                    <tr>
                                                                        <td><b>{t}Refunded{/t}:</b></td>
                                                                        <td><font color="red"><b> {$invoice_stats.count_closed_refunded}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Cancelled{/t}:</b></td>
                                                                        <td><font color="red"><b> {$invoice_stats.count_closed_cancelled}</b></font></td>
                                                                    </tr>                                                                    
                                                                </table>
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

                            <!-- Attached Invoice Statistics -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Attached Invoice Statistics{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="olohead">{t}Labour{/t}</td>
                                                            <td class="olohead">{t}Parts{/t}</td>
                                                            <td class="olohead">{t}Vouchers{/t}</td>
                                                        </tr>
                                                        <tr>

                                                            <!-- Labour -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$invoice_stats.labour_count_items}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Qty{/t}: </b></td>
                                                                        <td><font color="red"><b>{$invoice_stats.labour_sum_items}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Sub{/t} {t}[N]{/t}: </b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <!-- Parts -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$invoice_stats.parts_count_items}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Qty{/t}:</b></td>
                                                                        <td><font color="red"><b>{$invoice_stats.parts_sum_items}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Sub{/t} {t}[N]{/t}: </b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.parts_sum_sub_total|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>    
                                                            
                                                            <!-- Vouchers -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Opened{/t}: </b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_opened}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Closed{/t}: </b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_closed}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Expired{/t}:</b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_expired}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Redeemed{/t}: </b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_redeemed}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Claimed{/t}: </b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_claimed}</b></font></td>
                                                                    </tr>
                                                                </table>
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

                            <!-- Other Components Statistics -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Other Components Statistics{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                                                        <tr>                                                             
                                                            <td class="olohead">{t}Expenses{/t}</td>
                                                            <td class="olohead">{t}Refunds{/t}</td>
                                                            <td class="olohead">{t}Other Income{/t}</td>
                                                        </tr>
                                                        <tr>

                                                            <!-- Expenses -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$expense_stats.count_items}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Net{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_net_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}VAT{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_vat_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_gross_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <!-- Refunds -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$refund_stats.count_items}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Net{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_net_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}VAT{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_vat_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_gross_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>   
                                                            
                                                            <!-- Other Income -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Expired Count{/t}: </b></td>
                                                                        <td><font color="red"><b>{$otherincome_stats.count_expired}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Expired Sum{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$otherincome_stats.sum_expired|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Redeemed Count{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$otherincome_stats.count_expired|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Redeemed Sum{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$otherincome_stats.sum_redeemed|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
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
                            
                            <!-- Revenue -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Revenue Calculations{/t}</td>
                                            </tr>                                        
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">

                                                        <tr>                                                        
                                                            <td class="olohead">{t}Invoiced{/t}</td>
                                                            <td class="olohead">{t}VAT{/t}</td>
                                                            <td class="olohead">{t}Profit{/t}</td>                                                        
                                                        </tr>                                                    

                                                        <tr>

                                                            <!-- Revenue -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Sub Total{/t} {t}[N]{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_sub_total|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Discount{/t} {t}[N]{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_discount_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Net{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_net_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>                                                                    
                                                                    {*<tr>
                                                                        <td><b>{if $tax_system == 'vat_standard'}{t}VAT{/t}{elseif $tax_system == 'sales_tax'}{t}Sales Tax{/t}{else}{t}Sales Tax{/t} / {t}VAT{/t}{/if}:</b></td>                                                                    
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_tax_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>*}
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Sales Tax{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_sales_tax_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}VAT{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_vat_tax_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_gross_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td><b>{t}Received Monies{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_paid_amount|string_format:"%.2f"}</b></font></td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td><b>{t}Outstanding Balance{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_balance|string_format:"%.2f"}</b></font></td>
                                                                    </tr> 
                                                                </table>
                                                            </td>

                                                            <!-- VAT -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="2">{t}In{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Invoiced{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.invoice|string_format:"%.2f"}</b></font></td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td><b>{t}Other Income{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.otherincome|string_format:"%.2f"}</b></font></td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">{t}Out{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Expenses{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.expense|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Refunds{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.refund|string_format:"%.2f"}</b></font></td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">{t}Calculations{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Total In{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.total_in|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Total Out{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.total_out|string_format:"%.2f"}</b></font></td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td><b>{t}Balance{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.balance|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td> 

                                                            <!-- Profit -->
                                                            <td class="olotd4" valign="top">
                                                                <table cellpadding="5">
                                                                    <tr>
                                                                        <td style="text-align: center;">                                                                        
                                                                            <p>{t}[G]{/t} = {t}Gross{/t}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{t}[N]{/t} = {t}Net{/t}</p>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- No Tax -->
                                                                    <tr>
                                                                        <td style="text-align: center;">
                                                                            <p><strong>{t}No Tax{/t}</strong></p>
                                                                            <p>{t}Profit{/t} = ( {t}Invoiced{/t}{t}[G]{/t} + {t}Other Income{/t}{t}[G]{/t} ) - ( {t}Expenses{/t}{t}[G]{/t} + {t}Refunds{/t}{t}[G]{/t} )</p>                                                                       
                                                                            <p>{$currency_sym}{$profit_totals.no_tax|string_format:"%.2f"} = ({$currency_sym}{$invoice_stats.sum_gross_amount|string_format:"%.2f"} + {$currency_sym}{$otherincome_stats.sum_gross_amount|string_format:"%.2f"}) - ({$currency_sym}{$expense_stats.sum_gross_amount|string_format:"%.2f"} + {$currency_sym}{$refund_stats.sum_gross_amount|string_format:"%.2f"})</p>                                                                        
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Sales Tax -->
                                                                    <tr>
                                                                        <td style="text-align: center;">                                                                        
                                                                            <p><strong>{t}Sales Tax{/t}</strong></p>
                                                                            <p>{t}Profit{/t} = ( {t}Invoiced{/t}{t}[N]{/t} + {t}Other Income{/t}{t}[G]{/t} ) - ( {t}Expenses{/t}{t}[G]{/t} + {t}Refunds{/t}{t}[G]{/t} )</p> 
                                                                            <p>{$currency_sym}{$profit_totals.sales_tax|string_format:"%.2f"} = ({$currency_sym}{$invoice_stats.sum_net_amount|string_format:"%.2f"} + {$currency_sym}{$otherincome_stats.sum_gross_amount|string_format:"%.2f"}) - ({$currency_sym}{$expense_stats.sum_gross_amount|string_format:"%.2f"} + {$currency_sym}{$refund_stats.sum_gross_amount|string_format:"%.2f"})</p>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- VAT Tax -->
                                                                    <tr>
                                                                        <td style="text-align: center;">                                                                        
                                                                            <p><strong>{t}VAT{/t}</strong></p>
                                                                            <p>{t}Profit{/t} = ( {t}Invoiced{/t}{t}[N]{/t} + {t}Other Income{/t}{t}[G]{/t} ) - ( {t}Expenses{/t}{t}[N]{/t} + {t}Refunds{/t}{t}[N]{/t} )</p> 
                                                                            <p>{$currency_sym}{$profit_totals.vat_tax|string_format:"%.2f"} = ({$currency_sym}{$invoice_stats.sum_net_amount|string_format:"%.2f"} + {$currency_sym}{$otherincome_stats.sum_net_amount|string_format:"%.2f"}) - ({$currency_sym}{$expense_stats.sum_net_amount|string_format:"%.2f"} + {$currency_sym}{$refund_stats.sum_net_amount|string_format:"%.2f"})</p>
                                                                        </td>
                                                                    </tr>                                                                                                                               
                                                                </table>
                                                            </td>                                                                                                              

                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        {else}
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd"><strong>{t}To generate a report, submit a date range above.{/t}</strong</td>
                                </tr>
                            </table>
                        {/if}
                                                                    
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>