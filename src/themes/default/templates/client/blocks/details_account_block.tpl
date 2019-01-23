<!-- details_account_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
    <tr>         
        <td class="olohead">{t}Work Orders{/t}</td> 
        <td class="olohead">{t}Invoice Stats{/t}</td>
        <td class="olohead">{t}Monies{/t}</td>                                                               
    </tr>                                                    

    <tr>

        <!-- Work Orders -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td><b>{t}Total{/t} {t}Work Orders{/t}:</b></td>
                    <td><font color="green"><b>{$workorder_stats.count_open}</b></font></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t} {t}Work Orders{/t}:</b></td>
                    <td><b>{$workorder_stats.count_opened}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t} {t}Work Orders{/t}:</b></td>
                    <td><b>{$workorder_stats.count_closed}</b></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t} {t}Work Orders{/t}:</b></td>
                    <td><b>{$workorder_stats.count_open}</b></td>
                </tr>
            </table>
        </td>       

        <!-- Invoice Stats -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td><b>{t}Total{/t} {t}Invoices{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_total}</b></font></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t} {t}Invoices{/t}:</b></td>
                    <td><b>{$invoice_stats.count_opened}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t} {t}Invoices{/t}:</b></td>
                    <td><b>{$invoice_stats.count_closed}</b></td>
                </tr>                
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Invoices{/t} {t}with{/t} {t}Discount{/t}:</b></td>
                    <td><font color="orange"><b>{$invoice_stats.count_discounted}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t} {t}Invoices{/t}:</b></td>
                    <td><font {if $invoice_stats.count_cancelled > 0}color="red"{else}color="green"{/if}><b>{$invoice_stats.count_cancelled}</b></font></b></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Unpaid{/t} {t}Invoices{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_unpaid}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Paid{/t} {t}Invoices{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_partially_paid}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Paid{/t} {t}Invoices{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_paid}</b></font></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Invoices{/t} {t}with{/t} {t}Outstanding Balance{/t}:</b></td>
                    <td><font {if $invoice_stats.count_outstanding > 0}color="red"{else}color="green"{/if}><b>{$invoice_stats.count_outstanding}</b></font></td>
                </tr>                 
                <tr>
                    <td colspan="2"></td>
                </tr>
                
            </table>
        </td>      
                
        <!-- Monies -->
        <td class="olotd4" valign="top">
            <table>
                
                <tr>
                    <td><b>{t}Discount{/t} {t}Applied{/t} {t}[N]{/t}:</b></td>
                    <td><font color="orange"><b>{$currency_sym}{$invoice_stats.sum_discount_amount|string_format:"%.2f"}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t} {t}Invoices{/t} {t}[G]{/t}:</b></td>
                    <td><font {if $invoice_stats.sum_cancelled}color="red"{else}color="green"{/if}><b>{$currency_sym}{$invoice_stats.sum_cancelled|string_format:"%.2f"}</b></font></b></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Invoiced{/t} ({t}Net{/t}) {t}[N]{/t}:</b></td>
                    <td><font color="green"><b>{$currency_sym}{$invoice_stats.sum_net_amount|string_format:"%.2f"}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Invoiced{/t} ({t}Gross{/t}) {t}[G]{/t}:</b></td>
                    <td><font color="green"><b>{$currency_sym}{$invoice_stats.sum_gross_amount|string_format:"%.2f"}</b></font></td>
                </tr>
                 
                <tr>
                    <td><b>{t}Paid{/t} {t}Invoices{/t} {t}[G]{/t}:</b></td>
                    <td><font color="green"><b>{$currency_sym}{$invoice_stats.sum_paid_amount|string_format:"%.2f"}</b></font></td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td><b>{t}Outstanding Balance{/t} {t}[G]{/t}:</b></td>
                    <td><font {if $invoice_stats.sum_balance > 0}color="red"{else}color="green"{/if}><b>{$currency_sym}{$invoice_stats.sum_balance|string_format:"%.2f"}</b></font></td>
                </tr>
                
            </table>
        </td>

    </tr>
</table>