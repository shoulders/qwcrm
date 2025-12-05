<!-- display_invoices_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead" nowrap>{t}INV ID{/t}</td>
        <td class="olohead" nowrap>{t}WO ID{/t}</td>
        <td class="olohead" nowrap>{t}Date{/t}</td>
        <td class="olohead" nowrap>{t}Client{/t}</td>
        <td class="olohead" nowrap>{t}Employee{/t}</td>
        <td class="olohead" nowrap>{t}Scope{/t}</td>
        <td class="olohead" nowrap>{t}Items{/t}</td>
        <td class="olohead" nowrap>{t}Vouchers{/t}</td>
        <td class="olohead" nowrap>{t}Status{/t}</td>
        {if $qw_tax_system != 'no_tax'}
            <td class="olohead" nowrap>{t}Net{/t}</td>
            <td class="olohead"><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
        {/if}
        <td class="olohead" nowrap>{t}Gross{/t}</td>
        <td class="olohead" nowrap>{t}Balance{/t}</td>
        <td class="olohead" nowrap>{t}Additional Info{/t}</td>
    </tr>
    {section name=i loop=$display_invoices.records}
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_invoices.records[i].status != 'deleted'} onDblClick="window.location='index.php?component=invoice&page_tpl={if $display_invoices.records[i].is_closed}details{else}edit{/if}&invoice_id={$display_invoices.records[i].invoice_id}';"{/if}>
            <td class="olotd4" nowrap>
                {if $display_invoices.records[i].status == 'deleted'}
                    {$display_invoices.records[i].invoice_id}
                {else}
                    <a href="index.php?component=invoice&page_tpl=details&invoice_id={$display_invoices.records[i].invoice_id}">{$display_invoices.records[i].invoice_id}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap><a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_invoices.records[i].workorder_id}">{$display_invoices.records[i].workorder_id}</a></td>
            <td class="olotd4" nowrap>{$display_invoices.records[i].date|date_format:$date_format}</td>
            <td class="olotd4" nowrap>{if $display_invoices.records[i].status !== 'deleted'}<img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Contact{/t}: </b>{$display_invoices.records[i].client_first_name} {$display_invoices.records[i].client_last_name}<br><b>{t}Phone{/t}: </b>{$display_invoices.records[i].client_phone}<br><b>{t}Mobile{/t}: </b>{$display_invoices.records[i].client_mobile_phone}<br><b>{t}Fax{/t}: </b>{$display_invoices.records[i].client_fax}');" onMouseOut="hideddrivetip();"><a href="index.php?component=client&page_tpl=details&client_id={$display_invoices.records[i].client_id}"> {$display_invoices.records[i].client_display_name}</a>{else}&nbsp;{/if}</td>
            <td class="olotd4" nowrap>{if $display_invoices.records[i].status !== 'deleted'}<img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$display_invoices.records[i].employee_work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$display_invoices.records[i].employee_work_mobile_phone}<br><b>{t}Personal{/t}: </b>{$display_invoices.records[i].employee_home_mobile_phone}');" onMouseOut="hideddrivetip();"><a  href="index.php?component=user&page_tpl=details&user_id={$display_invoices.records[i].employee_id}"> {$display_invoices.records[i].employee_display_name}{else}&nbsp;{/if}</td>
            <td class="olotd4" nowrap>
                {if $display_invoices.records[i].scope}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Workorder{/t} {t}Scope{/t}</strong></div><hr><div>{$display_invoices.records[i].scope|htmlentities|regex_replace:"/\|\|\|/":"<br>"|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_invoices.records[i].invoice_items}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_invoices.records[i].invoice_items|htmlentities|regex_replace:"/\|\|\|/":"<br>"|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_invoices.records[i].voucher_items}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Vouchers{/t}</strong></div><hr><div>{$display_invoices.records[i].voucher_items|vouchers_display|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {section name=s loop=$invoice_statuses}
                    {if $display_invoices.records[i].status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}
                {/section}
            </td>
            {if $qw_tax_system != 'no_tax'}
                <td class="olotd4" nowrap>{$currency_sym}{$display_invoices.records[i].unit_net|string_format:"%.2f"}</td>
                <td class="olotd4" nowrap>{$currency_sym}{$display_invoices.records[i].unit_tax|string_format:"%.2f"}</td>
            {/if}
            <td class="olotd4" nowrap>{$currency_sym}{$display_invoices.records[i].unit_gross|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>{$currency_sym}{$display_invoices.records[i].balance|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>
                {if $display_invoices.records[i].additional_info|invoice_addinfo_display}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Additional Info{/t}</strong></div><hr><div>{$display_invoices.records[i].additional_info|invoice_addinfo_display|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
        </tr>
    {/section}
    {if $display_invoices.restricted_records}
        <tr>
            <td colspan="13">{t}Not all records are shown here.{/t} {t}Click{/t} <a href="index.php?component=invoice&page_tpl=search">{t}here{/t}</a> {t}to see all records.{/t}</td>
        </tr>
    {/if}
    {if !$display_invoices.records}
        <tr>
            <td colspan="13" class="error">{t}There are no invoices.{/t}</td>
        </tr>
    {/if}
</table>
