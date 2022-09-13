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
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Closed without Invoice{/t}:</b></td>
                    <td><b>{$workorder_stats.count_closed_without_invoice}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed with Invoice{/t}:</b></td>
                    <td><b>{$workorder_stats.count_closed_with_invoice}</b></td>
                </tr>
            </table>
        </td>       

        <!-- Invoice Stats -->
        <td class="olotd4" valign="top">
            <table>                
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$invoice_stats.count_open}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_open_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Discounted{/t}:</b></td>
                    <td><b>{$invoice_stats.count_discounted}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_discounted_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Pending{/t}:</b></td>
                    <td><b>{$invoice_stats.count_pending}</b></td> 
                    <td><b>({$currency_sym}{$invoice_stats.sum_pending_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Unpaid{/t}:</b></td>
                    <td><b>{$invoice_stats.count_unpaid}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_unpaid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Paid{/t}:</b></td>
                    <td><b>{$invoice_stats.count_partially_paid}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_partially_paid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>                
                <tr>
                    <td><b>{t}In Dispute{/t}:</b></td>
                    <td><b>{$invoice_stats.count_in_dispute}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_in_dispute_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Overdue{/t}:</b></td>
                    <td><b>{$invoice_stats.count_overdue}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_overdue_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Collections{/t}:</b></td>
                    <td><b>{$invoice_stats.count_collections}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_collections_unit_gross|string_format:"%.2f"})</b></td>
                </tr>                
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$invoice_stats.count_opened}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_opened_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$invoice_stats.count_closed}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_closed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t} {t}with{/t} {t}Discount{/t}:</b></td>
                    <td><b>{$invoice_stats.count_closed_discounted}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_closed_discounted_unit_gross|string_format:"%.2f"})</b></td>
                </tr> 
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Paid{/t}:</b></td>
                    <td><b>{$invoice_stats.count_paid}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_paid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t}:</b></td>
                    <td><b>{$invoice_stats.count_refunded}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_refunded_unit_gross|string_format:"%.2f"})</b></td>
                </tr>                
                <tr>
                    <td><b>{t}Cancelled{/t}:</b></td>
                    <td><b>{$invoice_stats.count_cancelled}</b></td>
                    <td><b>({$currency_sym}{$invoice_stats.sum_cancelled_unit_gross|string_format:"%.2f"})</b></td>
                </tr>                               
            </table>
        </td>   
        
        <!-- Vouchers -->
        <td class="olotd4" valign="top">
            <table>                
                <tr>
                    <td colspan="2"><h2>{t}Current{/t} ({t}Purchased{/t})</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$voucher_stats.count_open}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_open_unit_gross|string_format:"%.2f"})</b></td>
                </tr>                
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Unused{/t}:</b></td>
                    <td><b>{$voucher_stats.count_paid_unused}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_paid_unused_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                
                <tr>
                    <td><b>{t}Suspended{/t}:</b></td>
                    <td><b>{$voucher_stats.count_suspended}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_suspended_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Redeemed{/t}:</b></td>
                    <td><b>{$voucher_stats.count_redeemed}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_redeemed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>                
                <tr>
                    <td colspan="3"><h2>{t}Historic{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$voucher_stats.count_opened}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_opened_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$voucher_stats.count_closed}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_closed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>                    
                </tr>
                <tr>
                    <td><b>{t}Expired Unused{/t}:</b></td>
                    <td><b>{$voucher_stats.count_expired_unused}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_expired_unused_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Refunded{/t}:</b></td>
                    <td><b>{$voucher_stats.count_refunded}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_refunded_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Cancelled{/t}:</b></td>
                    <td><b>{$voucher_stats.count_cancelled}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_cancelled_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="3"><h2>{t}Claimed{/t}</h2></td>
                </tr>
                <tr>
                    <td colspan="2">{t}These are Vouchers purchased by other clients that have redeemed against this clients invoices. This client has not bought these.{/t}</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Claimed{/t}:</b></td>
                    <td><b>{$voucher_stats.count_claimed}</b></td>
                    <td><b>({$currency_sym}{$voucher_stats.sum_claimed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
            </table>
        </td>
                
        <!-- Monies -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="3"><h2>{t}{t}Payments{/t}{/t}</h2></td>
                </tr>                
                <tr>
                    <td><b>{t}Received{/t}:</b></td>
                    <td><span style="color: green;"><b>{$currency_sym}{$payment_stats.sum_received|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: green;"><b>({$payment_stats.count_received})</b></span></td>                    
                </tr> 
                <tr>
                    <td><b>{t}Sent{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_sym}{$payment_stats.sum_sent|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_sent})</b></span></td>
                </tr>
                <tr>
                    <td colspan="3"><h2>{t}{t}Refunds{/t}{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Refunds{/t}&nbsp;{t}[G]{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_sym}{$refund_stats.sum_unit_gross|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$refund_stats.count_items})</b></span></td>                    
                </tr> 
                <tr>
                    <td colspan="3"><h2>{t}{t}Invoiced{/t}{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Discount{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_unit_discount|string_format:"%.2f"}</b></td>
                </tr>
                {if $qw_tax_system != 'no_tax'}                    
                    <tr>
                        <td><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}:</b></td>
                        <td><b>{$currency_sym}{$invoice_stats.sum_unit_tax|string_format:"%.2f"}</b></td>
                    </tr>                
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><b>{t}Invoiced{/t}&nbsp;{t}[N]{/t}:</b></td>
                        <td><b>{$currency_sym}{$invoice_stats.sum_unit_net|string_format:"%.2f"}</b></td>
                    </tr>
                {/if}
                <tr>
                    <td><b>{t}Invoiced{/t}&nbsp;{t}[G]{/t}:</b></td>
                    <td><b>{$currency_sym}{$invoice_stats.sum_unit_gross|string_format:"%.2f"}</b></td>
                </tr>                 
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 15px;{if $invoice_stats.sum_balance > 0} color: red;{else} color: green;{/if}"><b>{t}Balance{/t}:</b></span>
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
    
    {if $allowed_to_create_creditnote}
        <tr>
            <td colspan="2">
                <button type="button" onclick="window.open('index.php?component=creditnote&page_tpl=new&client_id={$client_details.client_id}', '_self');">{t}Add Sales Credit Note (Standalone){/t}</button>
            </td>
        </tr>
    {/if}
    
</table>