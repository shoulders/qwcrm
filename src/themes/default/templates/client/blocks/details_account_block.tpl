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
        <td class="olohead">{t}Gift Certificate Stats{/t}</td>
        <td class="olohead">{t}Monies{/t}</td>                                                               
    </tr>                                                    

    <tr>

        <!-- Work Orders -->
        <td class="olotd4" valign="top">
            <table>                
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$workorder_stats.count_open}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Assigned{/t}:</b></td>
                    <td><b>{$workorder_stats.count_assigned}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Waiting for Parts{/t}:</b></td>
                    <td><b>{$workorder_stats.count_waiting_for_parts}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Scheduled{/t}:</b></td>
                    <td><b>{$workorder_stats.count_scheduled}</b></td>
                </tr>
                <tr>
                    <td><b>{t}With Client{/t}:</b></td>
                    <td><b>{$workorder_stats.count_with_client}</b></td>
                </tr>
                <tr>
                    <td><b>{t}On Hold{/t}:</b></td>
                    <td><b>{$workorder_stats.count_on_hold}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Management{/t}:</b></td>
                    <td><b>{$workorder_stats.count_management}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed without Invoice{/t}:</b></td>
                    <td><b>{$workorder_stats.count_closed_without_invoice}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed with Invoice{/t}:</b></td>
                    <td><b>{$workorder_stats.count_closed_with_invoice}</b></td>
                </tr> 
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$workorder_stats.count_opened}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$workorder_stats.count_closed}</b></td>
                </tr>                           
            </table>
        </td>       

        <!-- Invoice Stats -->
        <td class="olotd4" valign="top">
            <table>                
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$invoice_stats.count_open}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Discounted{/t}:</b></td>
                    <td><b>{$invoice_stats.count_discounted}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Pending{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_pending}</b></font></td>                    
                </tr>
                <tr>
                    <td><b>{t}Unpaid{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_unpaid}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Paid{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_partially_paid}</b></font></td>
                </tr>       
                <tr>
                    <td><b>{t}Paid{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_paid}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}In Dispute{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_in_dispute}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Overdue{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_overdue}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Collections{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_collections}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t}:</b></td>
                    <td><font color="green"><b>{$invoice_stats.count_refunded}</b></font></td>
                </tr>                
                <tr>
                    <td><b>{t}Cancelled{/t}:</b></td>
                    <td><font {if $invoice_stats.count_cancelled > 0}color="red"{else}color="green"{/if}><b>{$invoice_stats.count_cancelled}</b></font></b></td>
                </tr>
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$invoice_stats.count_opened}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$invoice_stats.count_closed}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t} {t}with{/t} {t}Discount{/t}:</b></td>
                    <td><font color="orange"><b>{$invoice_stats.count_closed_discounted}</b></font></td>
                </tr>                
            </table>
        </td>   
        
        <!-- Gift Certificates -->
        <td class="olotd4" valign="top">
            <table>                
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_open}</b></td>
                </tr>                
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Unused{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_unused}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Redeemed{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_redeemed}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Suspended{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_suspended}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Expired{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_expired}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_refunded}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_cancelled}</b></td>
                </tr>
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_opened}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$giftcert_stats.count_closed}</b></td>
                </tr>                
            </table>
        </td>
                
        <!-- Monies -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="2"><h2>{t}{t}Received{/t}{/t}</h2></td>
                </tr>
                {*<tr>
                    <td><b>Monies received as payments from client:</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>gift certificate info here</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>{t}Discount{/t} {t}Applied{/t} {t}[N]{/t}:</b></td>
                    <td><font color="orange"><b>{$currency_sym}{$invoice_stats.sum_discount_amount|string_format:"%.2f"}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t} {t}Invoices{/t} {t}[G]{/t}:</b></td>
                    <td><font {if $invoice_stats.sum_cancelled}color="red"{else}color="green"{/if}><b>{$currency_sym}{$invoice_stats.sum_cancelled|string_format:"%.2f"}</b></font></b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
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
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Outstanding Balance{/t} {t}[G]{/t}:</b></td>
                    <td><font {if $invoice_stats.sum_balance > 0}color="red"{else}color="green"{/if}><b>{$currency_sym}{$invoice_stats.sum_balance|string_format:"%.2f"}</b></font></td>
                </tr>
                <tr>
                    <td colspan="2"><h2>{t}Owed{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Discount{/t} {t}Applied{/t} {t}[N]{/t}:</b></td>
                    <td><font color="orange"><b>{$currency_sym}{$invoice_stats.sum_discount_amount|string_format:"%.2f"}</b></font></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t} {t}Invoices{/t} {t}[G]{/t}:</b></td>
                    <td><font {if $invoice_stats.sum_cancelled}color="red"{else}color="green"{/if}><b>{$currency_sym}{$invoice_stats.sum_cancelled|string_format:"%.2f"}</b></font></b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
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
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Outstanding Balance{/t} {t}[G]{/t}:</b></td>
                    <td><font {if $invoice_stats.sum_balance > 0}color="red"{else}color="green"{/if}><b>{$currency_sym}{$invoice_stats.sum_balance|string_format:"%.2f"}</b></font></td>
                </tr>*}
            </table>
        </td>

    </tr>
</table>