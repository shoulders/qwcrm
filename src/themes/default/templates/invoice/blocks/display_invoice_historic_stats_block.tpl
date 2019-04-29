<!-- display_invoice_historic_stats_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<br>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="row2"><b>{t}Opened{/t}</b></td>
        <td class="row2"><b>{t}Closed{/t}</b></td>
        <td class="row2"><b>{t}Deleted{/t}</b></td>
        <td class="row2"><b>{t}Invoiced Total{/t}</b></td>                                                                                      
        <td class="row2"><b>{t}Received Monies{/t}</b></td>
        <td class="row2"><b>{t}Outstanding Balance{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td>{$invoice_stats.count_opened}</td>
        <td>{$invoice_stats.count_closed}</td>
        <td>{$invoice_stats.count_closed_deleted}</td>
        <td><font color="green">{$currency_sym}{$invoice_stats.invoiced_total|string_format:"%.2f"}</font> [G]</td>
        <td><font color="green">{$currency_sym}{$invoice_stats.received_monies|string_format:"%.2f"}</font> [G]</td>
        <td><font color="cc0000">{$currency_sym}{$invoice_stats.outstanding_balance|string_format:"%.2f"}</font> [G]</td>
    </tr>
</table>