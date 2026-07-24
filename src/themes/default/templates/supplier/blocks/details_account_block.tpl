<!-- details_account_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">{t}Expense Stats{/t}</td>
        <td class="olohead">{t}Other Income Stats{/t}</td>
        <td class="olohead">{t}Credit Note Stats{/t} ({t}Purchase{/t})</td>
        <td class="olohead">{t}Payments{/t}</td>
    </tr>
    <tr>

        <!-- Expense Stats -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$expense_stats.count_open}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_open_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Pending{/t}:</b></td>
                    <td><b>{$expense_stats.count_pending}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_pending_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Unused{/t}:</b></td>
                    <td><b>{$expense_stats.count_unpaid}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_unpaid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Paid{/t}:</b></td>
                    <td><b>{$expense_stats.count_partially_paid}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_partially_paid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$expense_stats.count_opened}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_opened_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$expense_stats.count_closed}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_closed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Paid{/t}:</b></td>
                    <td><b>{$expense_stats.count_paid}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_paid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Voided{/t}:</b></td>
                    <td><b>{$expense_stats.count_voided}</b></td>
                    <td><b>({$currency_symbol}{$expense_stats.sum_voided_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
            </table>
        </td>

        <!-- Other Income Stats -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_open}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_open_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Pending{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_pending}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_pending_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Unpaid{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_unpaid}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_unpaid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Paid{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_partially_paid}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_partially_paid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_opened}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_opened_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_closed}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_closed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Paid{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_paid}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_paid_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Voided{/t}:</b></td>
                    <td><b>{$otherincome_stats.count_voided}</b></td>
                    <td><b>({$currency_symbol}{$otherincome_stats.sum_voided_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
            </table>
        </td>

        <!-- Credit Note Stats -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="2"><h2>{t}Current{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Open{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_open}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_open_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Pending{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_pending}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_pending_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Unused{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_unused}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_unused_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Partially Used{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_partially_used}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_partially_used_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="2"><h2>{t}Historic{/t}</h2></td>
                    <td><b>{t}[G]{/t}</b></td>
                </tr>
                <tr>
                    <td><b>{t}Opened{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_opened}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_opened_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Closed{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_closed}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_closed_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>{t}Used{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_used}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_used_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
                <tr>
                    <td><b>{t}Voided{/t}:</b></td>
                    <td><b>{$creditnote_stats.count_voided}</b></td>
                    <td><b>({$currency_symbol}{$creditnote_stats.sum_voided_unit_gross|string_format:"%.2f"})</b></td>
                </tr>
            </table>
        </td>

        <!-- Payments -->
        <td class="olotd4" valign="top">
            <table>
                <tr>
                    <td colspan="3"><h2>{t}Expenses{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Sent{/t} {t}to{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_symbol}{$payment_stats.sum_expense|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_expense})</b></span></td>
                </tr>
                <tr>
                    <td colspan="3"><hr /></td>
                </tr>
                <tr>
                    <td colspan="3"><h2>{t}Other Incomes{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Received{/t} {t}from{/t}:</b></td>
                    <td><span style="color: green;"><b>{$currency_symbol}{$payment_stats.sum_otherincome|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: green;"><b>({$payment_stats.count_otherincome})</b></span></td>
                </tr>
                <tr>
                    <td colspan="3"><hr /></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h2>{t}Credit Notes{/t} ({t}Purchase{/t})</h2>
                    </td>
                </tr>
                {*<tr>
                    <td><b>{t}Used for Closing{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_symbol}{$payment_stats.sum_creditnote_used_for_closing|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_creditnote_used_for_closing})</b></span></td>
                </tr>*}
                <tr>
                    <td><b>{t}Used for Credits{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_symbol}{$payment_stats.sum_creditnote_used_for_credits|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_creditnote_used_for_credits})</b></span></td>
                </tr>
                <tr>
                    <td><b>{t}Used for Payments{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_symbol}{$payment_stats.sum_creditnote_used_for_payments|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_creditnote_used_for_payments})</b></span></td>
                </tr>
                <tr>
                    <td colspan="3"><hr /></td>
                </tr>
                <tr>
                    <td colspan="3"><h2>{t}Real Monies{/t}</h2></td>
                </tr>
                <tr>
                    <td><b>{t}Received{/t} {t}from{/t}:</b></td>
                    <td><span style="color: green;"><b>{$currency_symbol}{$payment_stats.sum_real_monies_received|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: green;"><b>({$payment_stats.count_real_monies_received})</b></span></td>
                </tr>
                <tr>
                    <td><b>{t}Sent{/t} {t}to{/t}:</b></td>
                    <td><span style="color: red;"><b>{$currency_symbol}{$payment_stats.sum_real_monies_sent|string_format:"%.2f"}</b></span></td>
                    <td><span style="color: red;"><b>({$payment_stats.count_real_monies_sent})</b></span></td>
                </tr>
                <tr>
                    <td colspan="3"><hr /></td>
                </tr>
                <tr>
                    <td colspan="3"><h2>{t}Account Balance{/t}</h2></td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 15px;{if $expense_stats.sum_balance > 0} color: red;{else} color: green;{/if}"><b>{t}Balance{/t}:</b></span>
                    </td>
                    <td>
                        <hr>
                        <span style="font-size: 15px;{if $expense_stats.sum_balance > 0} color: red;{else} color: green;{/if}"><b>{$currency_symbol}{$expense_stats.sum_balance|string_format:"%.2f"}</b></span>
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">* {t}The balance is calculated from outstanding expenses only.{/t}</td>
                </tr>
            </table>
        </td>
    </tr>

    {if $allowed_to_create_creditnote}
        <tr>
            <td colspan="2">
                <button type="button" onclick="if(confirm('{t}Are you sure you want to create a credit note against this client?{/t}')) { window.location.href='index.php?component=creditnote&page_tpl=new&client_id={$client_details.client_id}'; } ">{t}Create Sales Credit Note (Client){/t}</button>
            </td>
        </tr>
    {/if}

</table>
