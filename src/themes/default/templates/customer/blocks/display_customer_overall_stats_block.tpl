<!-- display_customer_overall_stats_block.tpl -->
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
        <td class="row2"><b>{t}New This Month{/t}</b></td>
        <td class="row2"><b>{t}New This Year{/t}</b></td>
        <td class="row2"><b>{t}Total{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td>{$customer_overall_stats.month_count}</td>
        <td>{$customer_overall_stats.year_count}</td>
        <td>{$customer_overall_stats.total_count}</td>
    </tr>
</table>