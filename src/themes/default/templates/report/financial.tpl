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
                    <td class="menuhead2" width="80%">&nbsp;{t}Financial Report{/t} -  
                        {section name=s loop=$tax_systems}
                            {if $qw_tax_system == $tax_systems[s].type_key}{$tax_systems[s].display_name}{/if}
                        {/section}
                </td>
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
                                                            <td class="olohead">{t}Vouchers{/t}</td>
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
                                                                        <td><b>{t}Redeemed{/t}: </b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_redeemed}</b></font></td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td><b>{t}Expired{/t}:</b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_expired}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Refunded{/t}:</b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_expired}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Cancelled{/t}: </b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_cancelled}</b></font></td>
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

                            <!-- Invoice Statistics -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Invoiced{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                                                        <tr>                                                            
                                                            <td class="olohead">{t}Labour{/t}</td>
                                                            <td class="olohead">{t}Parts{/t}</td>
                                                            <td class="olohead">{t}Vouchers{/t}</td>
                                                            <td class="olohead">{t}Totals{/t}</td>
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
                                                                        <td><font color="red"><b>{$invoice_stats.labour_sum_items|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    {if $qw_tax_system != 'none'}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}: </b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: </b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>                                                                        
                                                                    {/if}
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}: </b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total_gross|string_format:"%.2f"}</b></font></td>
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
                                                                        <td><font color="red"><b>{$invoice_stats.parts_sum_items|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    {if $qw_tax_system != 'none'}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}: </b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$invoice_stats.parts_sum_sub_total_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: </b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>                                                                        
                                                                    {/if}
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}: </b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total_gross|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>    
                                                            
                                                            <!-- Vouchers -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Qty{/t}:</b></td>
                                                                        <td><font color="red"><b>{$voucher_stats.count_items}</b></font></td>
                                                                    </tr>
                                                                    {if $qw_tax_system != 'none'}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}: </b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_unit_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: </b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_unit_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                    {/if}
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}: </b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_unit_gross|string_format:"%.2f"}</b></font></td>
                                                                    </tr>                                                                    
                                                                </table>
                                                            </td>      
                                                            
                                                            <!-- Invoice Totals -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>{t}Labour{/t} {t}[N]{/t}</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.labour_sum_sub_total_net|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Parts{/t} {t}[N]{/t}</b></font></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.parts_sum_sub_total_net|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Vouchers{/t} {t}[N]{/t}</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_unit_net|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Discount{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_unit_discount|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    {if $qw_tax_system != 'none'}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_unit_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>                                                                        
                                                                        <tr>
                                                                            <td><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_unit_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                    {/if}
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_unit_gross|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Balance{/t}:</b><br>({t}Monies Not Received{/t})</td>
                                                                        <td><font color="red"><b>{$currency_sym}{$invoice_stats.sum_balance|string_format:"%.2f"}</b></font></td>
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
                            
                            <!-- Other Transactions -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Other Transactions{/t}</td>
                                            </tr>                                        
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">

                                                        <tr>                                                            
                                                            <td class="olohead">{t}Refunds{/t}</td>
                                                            <td class="olohead">{t}Expenses{/t}</td>
                                                            <td class="olohead">{t}Other Incomes{/t}</td>
                                                        </tr>     
                                                        <tr>

                                                            <!-- Refunds -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="2">{t}Invoice Refunds{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$refund_stats.count_items}</b></font></td>
                                                                    </tr>
                                                                    {if $qw_tax_system != 'none'}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_unit_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_unit_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                    {/if}
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_unit_gross|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Balance{/t}:</b><br>({t}Monies Not Sent{/t})</td>
                                                                        <td><font color="red"><b>{$currency_sym}{$refund_stats.sum_balance|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td> 
                                                            
                                                            <!-- Expenses -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$expense_stats.count_items}</b></font></td>
                                                                    </tr>
                                                                    {if '/^vat_/'|preg_match:$qw_tax_system}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_unit_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>{t}VAT{/t}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_unit_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                    {/if}
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_unit_gross|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Balance{/t}:</b><br>({t}Monies Not Sent{/t})</td>
                                                                        <td><font color="red"><b>{$currency_sym}{$expense_stats.sum_balance|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>   
                                                            
                                                            <!-- Other Incomes -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Items{/t}: </b></td>
                                                                        <td><font color="red"><b>{$otherincome_stats.count_items}</b></font></td>
                                                                    </tr>
                                                                    {if '/^vat_/'|preg_match:$qw_tax_system}
                                                                        <tr>
                                                                            <td><b>{t}Net{/t}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$otherincome_stats.sum_unit_net|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>{t}VAT{/t}:</b></td>
                                                                            <td><font color="red"><b>{$currency_sym}{$otherincome_stats.sum_unit_tax|string_format:"%.2f"}</b></font></td>
                                                                        </tr>
                                                                    {/if}
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$otherincome_stats.sum_unit_gross|string_format:"%.2f"}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Balance{/t}:</b><br>({t}Monies Not Received{/t})</td>
                                                                        <td><font color="red"><b>{$currency_sym}{$otherincome_stats.sum_balance|string_format:"%.2f"}</b></font></td>
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
                                                                    
                            <!-- Revenue, Tax and Profit Calculations -->
                            <table width="730px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Revenue, Tax and Profit Calculations {/t}</td>
                                            </tr>                                        
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">

                                                        <tr>  
                                                            <td class="olohead">{t}Payments{/t}</td>  
                                                            <td class="olohead">{t}Vouchers{/t}</td>
                                                            <td class="olohead">{t}Sales Tax{/t}</td>                                                            
                                                            <td class="olohead">{t}VAT{/t} {t}Liability{/t}</td>                                                                                                                                                                              
                                                        </tr>                                                    

                                                        <tr>
                                                                                                                        
                                                            <!-- Payments -->
                                                            <td class="olotd4" valign="top">                                                                
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="3">{t}Sent Monies{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Refunds{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$payment_stats.sum_refund|string_format:"%.2f"}</b></font></td>
                                                                        <td>({$payment_stats.count_refund})</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Expenses{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$payment_stats.sum_expense|string_format:"%.2f"}</b></font></td>
                                                                        <td>({$payment_stats.count_expense})</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">{t}Received Monies{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Invoices{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$payment_stats.sum_invoice|string_format:"%.2f"}</b></font></td>
                                                                        <td>({$payment_stats.count_invoice})</td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td><b>{t}Other Income{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$payment_stats.sum_otherincome|string_format:"%.2f"}</b></font></td>
                                                                        <td>({$payment_stats.count_otherincome})</td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td colspan="2">{t}Totals{/t}</td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td><b>{t}Sent{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$payment_stats.sum_sent|string_format:"%.2f"}</b></font></td>
                                                                        <td>({$payment_stats.count_sent})</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Received{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$payment_stats.sum_received|string_format:"%.2f"}</b></font></td>
                                                                        <td>({$payment_stats.count_received})</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            
                                                            <!-- Vouchers -->
                                                            <td class="olotd4" valign="top">
                                                                <table>                                                                    
                                                                    <tr>
                                                                        <td colspan="2">{t}Redeemed Vouchers{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Net{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_redeemed_net}</b></font></td>
                                                                    </tr>   
                                                                    <tr>
                                                                        <td><b>{t}Tax{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_expired_tax}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_redeemed_gross}</b></font></td>
                                                                    </tr>  
                                                                    <tr>
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">{t}Expired Vouchers{/t}</td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td><b>{t}Net{/t}</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_expired_net}</b></font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Tax{/t}</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_redeemed_tax}</b></font></td>
                                                                    </tr>   
                                                                    <tr>
                                                                        <td><b>{t}Gross{/t}</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$voucher_stats.sum_expired_gross}</b></font></td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <!-- Sales Tax -->
                                                            <td class="olotd4" valign="top">
                                                                Sales TAX
                                                            </td>

                                                            <!-- VAT Liability -->
                                                            <td class="olotd4" valign="top">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="2">{t}In{/t} ({t}Output VAT{/t})</td>
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
                                                                        <td colspan="2">{t}Out{/t} ({t}Input VAT{/t})</td>
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
                                                                        <td><hr></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Balance{/t}:</b></td>
                                                                        <td><font color="red"><b>{$currency_sym}{$vat_totals.balance|string_format:"%.2f"}</b></font> (in/out) need to calculate......</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <hr style="border-top: dotted 1px;" />
                                                                            <b>{t}NB{/t}:</b> {t}These are calculated from the relevant records and their dates, NOT by payments as per VAT rules.{/t}
                                                                        </td>                                                                        
                                                                    </tr>
                                                                </table>
                                                            </td>                                                             
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                            
                                            <!-- Profit -->
                                            <tr>
                                                <td class="olotd5" colspan="2">
                                                    <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">

                                                        <tr>                                                            
                                                            <td class="olohead" colspan="3">{t}Profit{/t}</td>                                                        
                                                        </tr>                                                    
                                                        <tr>
                                                            <td class="olotd4" valign="top">
                                                                <table cellpadding="5" style="margin: auto auto;">
                                                                    <tr>
                                                                        <td style="text-align: center;">                                                                        
                                                                            <p>{t}[G]{/t} = {t}Gross{/t}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{t}[N]{/t} = {t}Net{/t}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{t}[P]{/t} = {t}Payment{/t}</p>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                     <!-- No Tax -->
                                                                    <tr>
                                                                        <td style="text-align: center;">
                                                                            <p><strong>{t}No Tax{/t}</strong></p>
                                                                            <p>{t}Profit{/t}&nbsp;&nbsp;=&nbsp;&nbsp;( {t}Invoiced{/t}{t}[G]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Other Incomes{/t}{t}[G]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Expired{/t} {t}Vouchers{/t}{t}[G]{/t} )&nbsp;&nbsp;-&nbsp;&nbsp;( {t}Expenses{/t}{t}[G]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Refunds{/t}{t}[G]{/t} )</p>                                                                       
                                                                            <p>{$currency_sym}{$profit_totals.no_tax|string_format:"%.2f"} = ({$currency_sym}{$invoice_stats.sum_unit_gross|string_format:"%.2f"} + {$currency_sym}{$otherincome_stats.sum_unit_gross|string_format:"%.2f"}) - ({$currency_sym}{$expense_stats.sum_unit_gross|string_format:"%.2f"} + {$currency_sym}{$refund_stats.sum_unit_gross|string_format:"%.2f"})</p>                                                                        
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Sales Tax (Cash Basis)-->
                                                                    <tr>
                                                                        <td style="text-align: center;">                                                                        
                                                                            <p><strong>{t}Sales Tax{/t}</strong> ({t}Cash Basis{/t})</p>
                                                                            <p>{t}Profit{/t}&nbsp;&nbsp;=&nbsp;&nbsp;( {t}Invoiced{/t}{t}[N]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Other Incomes{/t}{t}[G]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Expired Vouchers{/t}{t}[G]{/t} )&nbsp;&nbsp;-&nbsp;&nbsp;( {t}Expenses{/t}{t}[G]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Refunds{/t}{t}[G]{/t} )</p>
                                                                            <p>{$currency_sym}{$profit_totals.sales_tax|string_format:"%.2f"} = ({$currency_sym}{$invoice_stats.sum_unit_net|string_format:"%.2f"} + {$currency_sym}{$otherincome_stats.sum_unit_gross|string_format:"%.2f"}) - ({$currency_sym}{$expense_stats.sum_unit_gross|string_format:"%.2f"} + {$currency_sym}{$refund_stats.sum_unit_gross|string_format:"%.2f"}) - {$currency_sym}{$refund_stats.sum_unit_gross|string_format:"%.2f"}</p>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- VAT Tax (Standard) -->
                                                                    <tr>
                                                                        <td style="text-align: center;">                                                                        
                                                                            <p><strong>{t}VAT{/t} {t}Standard{/t}</strong></p>
                                                                            <p>{t}Profit{/t}&nbsp;&nbsp;=&nbsp;&nbsp;( {t}Invoiced{/t}{t}[N]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Other Incomes{/t}{t}[N]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Expired Vouchers{/t}{t}[N]{/t} )&nbsp;&nbsp;-&nbsp;&nbsp;( {t}Expenses{/t}{t}[N]{/t}&nbsp;&nbsp;+&nbsp;&nbsp;{t}Refunds{/t}{t}[N]{/t} )</p> 
                                                                            <p>{$currency_sym}{$profit_totals.vat_tax|string_format:"%.2f"} = ({$currency_sym}{$invoice_stats.sum_unit_net|string_format:"%.2f"} + {$currency_sym}{$otherincome_stats.sum_unit_net|string_format:"%.2f"}) - ({$currency_sym}{$expense_stats.sum_unit_net|string_format:"%.2f"} + {$currency_sym}{$refund_stats.sum_unit_net|string_format:"%.2f"}) - {$currency_sym}{$refund_stats.sum_unit_net|string_format:"%.2f"}</p>                                                                            
                                                                        </td>
                                                                    </tr>  
                                                                    
                                                                    <!-- Note -->
                                                                    <tr>
                                                                        <tr>
                                                                        <td colspan="2">
                                                                            <hr style="border-top: dotted 1px;" />
                                                                            <b>{t}NB{/t}:</b> {t}These are calculated from the relevant payments and their date prorata against the corresponding transaction record, NOT by record dates.{/t}
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
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <b>{t}NB{/t}:</b> {t}Cancelled transactions (Records) are ignored in calculations.{/t}
                    </td>
                </tr>                
            </table>
        </td>
    </tr>
</table>