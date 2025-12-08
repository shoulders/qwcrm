<!-- display_creditnote_revenue_stats_block.tpl -->
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
        <td class="row2"><b>{t}Credit Note Total{/t} ({t}Excl.{/t} {t}Cancelled{/t})</b></td>
        <td class="row2"><b>{t}Cancelled{/t}</b></td>
        <td class="row2"><b>{t}Outstanding Balance{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td><font color="green">{$currency_symbol}{$creditnote_stats.sum_unit_gross|string_format:"%.2f"}</font> [G]</td>
        <td><font color="green">{$currency_symbol}{$creditnote_stats.sum_cancelled_unit_gross|string_format:"%.2f"}</font> [G]</td>
        <td><font color="cc0000">{$currency_symbol}{$creditnote_stats.sum_balance|string_format:"%.2f"}</font> [G]</td>
    </tr>
</table>
