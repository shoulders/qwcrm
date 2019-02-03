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
        <td class="olohead">{t}Voucher Stats{/t}</td>
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
                    <td><b>{$invoice_stats.count_pending}</b></td>                    
                </tr>
                <tr>
                    <td><b>{t}Unpaid{/t}:</b></td>
                    <td><b>{$invoice_stats.count_unpaid}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Paid{/t}:</b></td>
                    <td><b>{$invoice_stats.count_partially_paid}</b></td>
                </tr>       
                <tr>
                    <td><b>{t}Paid{/t}:</b></td>
                    <td><b>{$invoice_stats.count_paid}</b></td>
                </tr>
                <tr>
                    <td><b>{t}In Dispute{/t}:</b></td>
                    <td><b>{$invoice_stats.count_in_dispute}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Overdue{/t}:</b></td>
                    <td><b>{$invoice_stats.count_overdue}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Collections{/t}:</b></td>
                    <td><b>{$invoice_stats.count_collections}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t}:</b></td>
                    <td><b>{$invoice_stats.count_refunded}</b></td>
                </tr>                
                <tr>
                    <td><b>{t}Cancelled{/t}:</b></td>
                    <td><b>{$invoice_stats.count_cancelled}</b></td>
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
                    <td><b>{$invoice_stats.count_closed_discounted}</b></td>
                </tr>                
            </table>
        </td>   
        
        <!-- Vouchers -->
        <td class="olotd4" valign="top">
            <table>                
                <tr>
                    <td colspan="3"><h2>{t}Current{/t} ({t}Purchased{/t})</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$voucher_stats.count_open}</b></td>
                </tr>                
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Unused{/t}:</b></td>
                    <td><b>{$voucher_stats.count_unused}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_unused|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Redeemed{/t}:</b></td>
                    <td><b>{$voucher_stats.count_redeemed}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_redeemed|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Suspended{/t}:</b></td>
                    <td><b>{$voucher_stats.count_suspended}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_suspended|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Expired{/t}:</b></td>
                    <td><b>{$voucher_stats.count_expired}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_expired|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t}:</b></td>
                    <td><b>{$voucher_stats.count_refunded}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_refunded|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t}:</b></td>
                    <td><b>{$voucher_stats.count_cancelled}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_cancelled|string_format:"%.2f"})</b></td>
                </tr>                
                <tr>
                    <td colspan="3"><h2>{t}Historic{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$voucher_stats.count_opened}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$voucher_stats.count_closed}</b></td>
                </tr>
                <tr>
                    <td colspan="3"><h2>{t}Claimed{/t}</h2></td>
                </tr>
                <tr>
                    <td colspan="2">{t}The client has used these Vouchers against their own invoices. They have not bought them.{/t}</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Claimed{/t}:</b></td>
                    <td><b>{$voucher_stats.count_claimed}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_claimed|string_format:"%.2f"})</b></td>
                </tr>
            </table>
        </td>
                
        <!-- Monies -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="2"><h2>{t}{t}Payments{/t}{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Received{/t} {t}[N]{/t}:</b></td>
                    <td><span style="color: green;"><b>{$currency_sym}{$payment_stats.sum_received|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: green;"><b>({$payment_stats.count_received})</b></span></td>                    
                </tr>
                <tr>
                    <td><b>{t}Transmitted{/t} {t}[N]{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_sym}{$payment_stats.sum_transmitted|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_transmitted})</b></span></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="2"><h2>{t}Revenue{/t}</h2></td>
                </tr>                
                <tr>
                    <td><b>{t}Refunded{/t} {t}[N]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_refunded_net|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t} {t}[G]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_refunded_gross|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t} {t}[N]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_cancelled_net|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t} {t}[G]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_cancelled_gross|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Discount{/t} {t}Applied{/t} {t}[N]{/t}:</b> not 100% happy with this, does not account for cancelled invoices</td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_discount_amount|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Sales Tax{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_sales_tax_amount|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td><b>{t}VAT Tax{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_vat_tax_amount|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Invoiced{/t} {t}[N]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_net_amount|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Invoiced{/t} {t}[G]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_gross_amount|string_format:"%.2f"}</b></td>
                </tr>                 
                <tr>
                    <td><b>{t}Paid{/t} {t}Invoices{/t} {t}[G]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_paid_amount|string_format:"%.2f"}</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 15px;{if $invoice_stats.sum_balance > 0} color: red;{else} color: green;{/if}"><b>{t}Balance{/t} {t}[G]{/t}:</b></span>
                    </td>
                    <td>
                        <hr>
                        <span style="font-size: 15px;{if $invoice_stats.sum_balance > 0} color: red;{else} color: green;{/if}"><b>{$currency_sym}{$invoice_stats.sum_balance|string_format:"%.2f"}</b></span>
                        <hr>
                    </td>
                </tr>
            </table>
        </td>

    </tr>
</table>