<!-- display_invoice_stats_block.tpl -->
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
        <td class="row2"><b>{t}Open{/t}</b></td>        
        <td class="row2"><b>{t}Pending{/t}</b></td>
        <td class="row2"><b>{t}Unpaid{/t}</b></td>
        <td class="row2"><b>{t}Partially Paid{/t}</b></td>
        <td class="row2"><b>{t}Paid{/t}</b></td>
        <td class="row2"><b>{t}In Dispute{/t}</b></td>
        <td class="row2"><b>{t}Overdue{/t}</b></td> 
        <td class="row2"><b>{t}Cancelled{/t}</b></td> 
        <td class="row2"><b>{t}Refunded{/t}</b></td> 
        <td class="row2"><b>{t}Collections{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td>{$invoice_stats.open_count}</td>
        <td>{$invoice_stats.pending_count}</td>
        <td>{$invoice_stats.unpaid_count}</td>
        <td>{$invoice_stats.partially_paid_count}</td>
        <td>{$invoice_stats.paid_count}</td>
        <td>{$invoice_stats.in_dispute_count}</td>
        <td>{$invoice_stats.overdue_count}</td>
        <td>{$invoice_stats.cancelled_count}</td>
        <td>{$invoice_stats.refunded_count}</td>
        <td>{$invoice_stats.collections_count}</td>
    </tr>
</table>