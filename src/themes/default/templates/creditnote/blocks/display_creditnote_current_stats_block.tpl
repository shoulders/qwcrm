<!-- display_creditnote_current_stats_block.tpl -->
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
        <td class="row2"><b>{t}Unused{/t}</b></td>
        <td class="row2"><b>{t}Partially Used{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td>{$creditnote_stats.count_open}</td>
        <td>{$creditnote_stats.count_pending}</td>
        <td>{$creditnote_stats.count_unused}</td>
        <td>{$creditnote_stats.count_partially_used}</td> 
    </tr>
</table>