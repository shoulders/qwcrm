<!-- display_expenses_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">{t}Expense ID{/t}</td>
        <td class="olohead">{t}Supplier ID{/t}</td>
        <td class="olohead">{t}Payee{/t}</td>
        <td class="olohead">{t}Date{/t}</td>
        <td class="olohead">{t}Type{/t}</td>
        {if '/^vat_/'|preg_match:$qw_tax_system}
            <td class="olohead">{t}Net{/t}</td>
            <td class="olohead">{t}VAT{/t}</td>
        {/if}
        <td class="olohead">{t}Gross{/t}</td>
        <td class="olohead">{t}Balance{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Items{/t}</td>
        <td class="olohead">{t}Note{/t}</td>
        <td class="olohead">{t}Additional Info{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=e loop=$display_expenses.records}
        <!-- This allows double clicking on a row and opens the corresponding expense view details -->
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_expenses.records[e].status != 'deleted'} onDblClick="window.location='index.php?component=expense&page_tpl=details&expense_id={$display_expenses.records[e].expense_id}';"{/if}>
            <td class="olotd4" nowrap><a href="index.php?component=expense&page_tpl=details&expense_id={$display_expenses.records[e].expense_id}">{$display_expenses.records[e].expense_id}</a></td>
            <td class="olotd4" nowrap><a href="index.php?component=supplier&page_tpl=details&supplier_id={$display_expenses.records[e].supplier_id}">{$display_expenses.records[e].supplier_id}</a></td>
            <td class="olotd4" nowrap>{$display_expenses.records[e].display_name}</td>
            <td class="olotd4" nowrap>{$display_expenses.records[e].date|date_format:$date_format}</td>
            <td class="olotd4" nowrap>
                {section name=s loop=$expense_types}
                    {if $display_expenses.records[e].type == $expense_types[s].type_key}{t}{$expense_types[s].display_name}{/t}{/if}
                {/section}
            </td>
            {if '/^vat_/'|preg_match:$qw_tax_system}
                <td class="olotd4" nowrap>{$currency_sym}{$display_expenses.records[e].unit_net|string_format:"%.2f"}</td>
                <td class="olotd4" nowrap>{$currency_sym}{$display_expenses.records[e].unit_tax|string_format:"%.2f"}</td>
            {/if}
            <td class="olotd4" nowrap>{$currency_sym}{$display_expenses.records[e].unit_gross|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>{$currency_sym}{$display_expenses.records[e].balance|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>
                {section name=s loop=$expense_statuses}
                    {if $display_expenses.records[e].status == $expense_statuses[s].status_key}{t}{$expense_statuses[s].display_name}{/t}{/if}
                {/section}
            </td>
            <td class="olotd4" nowrap>
                {if $display_expenses.records[e].expense_items}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_expenses.records[e].expense_items|htmlentities|regex_replace:"/\|\|\|/":"<br>"|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_expenses.records[e].note}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_expenses.records[e].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_expenses.records[e].additional_info|expense_addinfo_display}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Additional Info{/t}</strong></div><hr><div>{$display_expenses.records[e].additional_info|expense_addinfo_display|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                <a href="index.php?component=expense&page_tpl=details&expense_id={$display_expenses.records[e].expense_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}View Expense Details{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=expense&page_tpl=edit&expense_id={$display_expenses.records[e].expense_id}">
                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit Expense Details{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                {*<a href="index.php?component=expense&page_tpl=delete&expense_id={$display_expenses.records[e].expense_id}" onclick="return confirm('{t}Are you Sure you want to delete this Expense Record? This will permanently remove the record from the database.{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Expense Record{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>*}
            </td>
        </tr>
    {/section}
    {if $display_expenses.restricted_records}
        <tr>
            <td colspan="13">{t}Not all records are shown here.{/t} {t}Click{/t} <a href="index.php?component=expense&page_tpl=search">{t}here{/t}</a> {t}to see all records.{/t}</td>
        </tr>
    {/if}
    {if !$display_expenses.records}
        <tr>
            <td colspan="13" class="error">{t}There are no expenses.{/t}</td>
        </tr>
    {/if}
 </table>
