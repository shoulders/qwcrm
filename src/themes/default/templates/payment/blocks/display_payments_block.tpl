<!-- display_payments_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead"><b>{t}Payment ID{/t}</b></td>
        <td class="olohead"><b>{t}Employee{/t}</b></td>
        <td class="olohead"><b>{t}Client{/t}</b></td>
        <td class="olohead"><b>{t}Supplier{/t}</b></td>
        <td class="olohead"><b>{t}Invoice{/t}</b></td>
        <td class="olohead"><b>{t}Expense{/t}</b></td>
        <td class="olohead"><b>{t}Otherincome{/t}</b></td>
        <td class="olohead"><b>{t}Credit Note{/t}</b></td>
        <td class="olohead"><b>{t}Voucher{/t}</b></td>
        <td class="olohead"><b>{t}Date{/t}</b></td>
        <td class="olohead"><b>{t}Type{/t}</b></td>
        <td class="olohead"><b>{t}Method{/t}</b></td>
        <td class="olohead"><b>{t}Direction{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
        <td class="olohead"><b>{t}Amount{/t}</b></td>
        <td class="olohead"><b>{t}Additional Info{/t}</b></td>
        <td class="olohead"><b>{t}Note{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=p loop=$display_payments.records}
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_payments.records[p].status != 'deleted'} onDblClick="window.location='index.php?component=payment&page_tpl=details&payment_id={$display_payments.records[p].payment_id}';"{/if}>
            <td class="olotd4"><a href="index.php?component=payment&page_tpl=details&payment_id={$display_payments.records[p].payment_id}">{$display_payments.records[p].payment_id}</a></td>
            <td class="olotd4" nowrap><a class="link1" href="index.php?component=user&page_tpl=details&user_id={$display_payments.records[p].employee_id}">{$display_payments.records[p].employee_display_name}</a></td>
            <td class="olotd4" nowrap><a class="link1" href="index.php?component=client&page_tpl=details&client_id={$display_payments.records[p].client_id}">{$display_payments.records[p].client_display_name}</a></td>
            <td class="olotd4" nowrap><a class="link1" href="index.php?component=supplier&page_tpl=details&supplier_id={$display_payments.records[p].supplier_id}">{$display_payments.records[p].supplier_display_name}</a></td>
            <td class="olotd4"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$display_payments.records[p].invoice_id}">{$display_payments.records[p].invoice_id}</a></td>
            <td class="olotd4"><a href="index.php?component=expense&page_tpl=details&expense_id={$display_payments.records[p].expense_id}">{$display_payments.records[p].expense_id}</a></td>
            <td class="olotd4"><a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$display_payments.records[p].otherincome_id}">{$display_payments.records[p].otherincome_id}</a></td>
            <td class="olotd4"><a href="index.php?component=creditnote&page_tpl=details&creditnote_id={$display_payments.records[p].creditnote_id}">{$display_payments.records[p].creditnote_id}</a></td>
            <td class="olotd4"><a href="index.php?component=voucher&page_tpl=details&voucher_id={$display_payments.records[p].voucher_id}">{$display_payments.records[p].voucher_id}</a></td>
            <td class="olotd4"> {$display_payments.records[p].date|date_format:$date_format}</td>
            <td class="olotd4" align="center">
                {section name=t loop=$payment_types}
                    {if $display_payments.records[p].type == $payment_types[t].type_key}{t}{$payment_types[t].display_name}{/t}{/if}
                {/section}
            </td>
            <td class="olotd4" align="center">
                {section name=m loop=$payment_methods}
                    {if $display_payments.records[p].method == $payment_methods[m].method_key}{t}{$payment_methods[m].display_name}{/t}{/if}
                {/section}
            </td>
            <td class="olotd4" align="center">
                {section name=d loop=$payment_directions}
                    {if $display_payments.records[p].direction == $payment_directions[d].key}{t}{$payment_directions[d].display_name}{/t}{/if}
                {/section}
            </td>
            <td class="olotd4" align="center">
                {section name=t loop=$payment_statuses}
                    {if $display_payments.records[p].status == $payment_statuses[t].status_key}{t}{$payment_statuses[t].display_name}{/t}{/if}
                {/section}
            </td>
            <td class="olotd4" nowrap>{$display_payments.records[p].amount|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>
                {if $display_payments.records[p].additional_info|paymentadinfodisplay}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Additional Info{/t}</strong></div><hr><div>{$display_payments.records[p].additional_info|paymentadinfodisplay|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_payments.records[p].note}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_payments.records[p].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?component=payment&page_tpl=details&payment_id={$display_payments.records[p].payment_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}View the payment.{/t}');" onMouseOut="hideddrivetip();">
                </a>
                {*<a href="index.php?component=payment&page_tpl=delete&payment_id={$display_payments.records[p].payment_id}" onclick="return confirm('{t}Are you Sure you want to delete this Payment Record? This will permanently remove the record from the database.{/t}');">
                     <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Payment Record{/t}</b>');" onMouseOut="hideddrivetip();">
                 </a>*}
            </td>

        </tr>
        {/section}
        {if $display_payments.restricted_records}
            <tr>
                <td colspan="17">{t}Not all records are shown here.{/t} {t}Click{/t} <a href="index.php?component=payment&page_tpl=search">{t}here{/t}</a> {t}to see all records.{/t}</td>
            </tr>
        {/if}
        {if !$display_payments.records}
            <tr>
                <td colspan="17" class="error">{t}There are no payments.{/t}</td>
            </tr>
        {/if}
</table>
