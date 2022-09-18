<!-- display_creditnotes_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead" nowrap>{t}Credit Note ID{/t}</td>        
        <td class="olohead" nowrap>{t}Date{/t}</td>  
        <td class="olohead" nowrap>{t}Expiry Date{/t}</td>
        <td class="olohead" nowrap>{t}Client{/t}</td>
        <td class="olohead" nowrap>{t}Invoice ID{/t}</td>
        <td class="olohead" nowrap>{t}Supplier{/t}</td>
        <td class="olohead" nowrap>{t}Expense ID{/t}</td>
        <td class="olohead" nowrap>{t}Employee{/t}</td>
        <td class="olohead" nowrap>{t}Type{/t}</td>
        <td class="olohead" nowrap>{t}Status{/t}</td>        
        {if $qw_tax_system != 'no_tax'}
            <td class="olohead" nowrap>{t}Net{/t}</td>
            <td class="olohead"><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
        {/if}
        <td class="olohead" nowrap>{t}Gross{/t}</td>
        <td class="olohead" nowrap>{t}Balance{/t}</td>
        <td class="olohead" nowrap>{t}Redemptions{/t}</td>
        <td class="olohead" nowrap>{t}Additional Info{/t}</td> 
    </tr>
    {section name=i loop=$display_creditnotes.records}
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_creditnotes.records[i].status != 'deleted'} onDblClick="window.location='index.php?component=creditnote&page_tpl={if $display_creditnotes.records[i].is_closed}details{else}edit{/if}&creditnote_id={$display_creditnotes.records[i].creditnote_id}';"{/if}>
            <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].status == 'deleted'}
                    {$display_creditnotes.records[i].creditnote_id}
                {else}                    
                    <a href="index.php?component=creditnote&page_tpl=details&creditnote_id={$display_creditnotes.records[i].creditnote_id}">{$display_creditnotes.records[i].creditnote_id}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap>{$display_creditnotes.records[i].date|date_format:$date_format}</td>         
            <td class="olotd4" nowrap>{$display_creditnotes.records[i].expiry_date|date_format:$date_format}</td>
            <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].client_id && $display_creditnotes.records[i].status !== 'deleted'}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Contact{/t}: </b>{$display_creditnotes.records[i].client_first_name} {$display_creditnotes.records[i].client_last_name}<br><b>{t}Phone{/t}: </b>{$display_creditnotes.records[i].client_phone}<br><b>{t}Mobile{/t}: </b>{$display_creditnotes.records[i].client_mobile_phone}');" onMouseOut="hideddrivetip();"><a href="index.php?component=client&page_tpl=details&client_id={$display_creditnotes.records[i].client_id}"> {$display_creditnotes.records[i].client_display_name}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].invoice_id}
                    <a href="index.php?component=invoice&page_tpl=details&invoice_id={$display_creditnotes.records[i].invoice_id}">{$display_creditnotes.records[i].invoice_id}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].supplier_id && $display_creditnotes.records[i].status !== 'deleted'}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Contact{/t}: </b>{$display_creditnotes.records[i].supplier_first_name} {$display_creditnotes.records[i].supplier_last_name}<br><b>{t}Phone{/t}: </b>{$display_creditnotes.records[i].supplier_phone}<br><b>{t}Mobile{/t}: </b>{$display_creditnotes.records[i].supplier_mobile_phone}');" onMouseOut="hideddrivetip();"><a href="index.php?component=supplier&page_tpl=details&supplier_id={$display_creditnotes.records[i].supplier_id}"> {$display_creditnotes.records[i].supplier_display_name}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].expense_id}
                    <a href="index.php?component=expense&page_tpl=details&expense_id={$display_creditnotes.records[i].expense_id}">{$display_creditnotes.records[i].expense_id}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap>{if $display_creditnotes.records[i].status !== 'deleted'}<img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$display_creditnotes.records[i].employee_work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$display_creditnotes.records[i].employee_work_mobile_phone}<br><b>{t}Personal{/t}: </b>{$display_creditnotes.records[i].employee_home_mobile_phone}');" onMouseOut="hideddrivetip();"><a  href="index.php?component=user&page_tpl=details&user_id={$display_creditnotes.records[i].employee_id}"> {$display_creditnotes.records[i].employee_display_name}{else}&nbsp;{/if}</td>
            <td class="olotd4" nowrap>                
                {section name=s loop=$creditnote_types}                    
                    {if $display_creditnotes.records[i].type == $creditnote_types[s].type_key}{t}{$creditnote_types[s].display_name}{/t}{/if}                    
                {/section}                
            </td> 
            <td class="olotd4" nowrap>   
                {section name=s loop=$creditnote_statuses}                    
                    {if $display_creditnotes.records[i].status == $creditnote_statuses[s].status_key}{t}{$creditnote_statuses[s].display_name}{/t}{/if}                    
                {/section}                
            </td>
            {if $qw_tax_system != 'no_tax'}
                <td class="olotd4" nowrap>{$currency_sym}{$display_creditnotes.records[i].unit_net|string_format:"%.2f"}</td> 
                <td class="olotd4" nowrap>{$currency_sym}{$display_creditnotes.records[i].unit_tax|string_format:"%.2f"}</td>
            {/if}
            <td class="olotd4" nowrap>{$currency_sym}{$display_creditnotes.records[i].unit_gross|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>{$currency_sym}{$display_creditnotes.records[i].balance|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].redemptions|redemptions}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Redemptions{/t}</strong></div><hr><div>{$display_creditnotes.records[i].redemptions|redemptions|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
             </td>
             <td class="olotd4" nowrap>
                {if $display_creditnotes.records[i].additional_info|creditnoteadinfodisplay}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Additional Info{/t}</strong></div><hr><div>{$display_creditnotes.records[i].additional_info|creditnoteadinfodisplay|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
             </td>
        </tr>
    {/section}
    {if $display_creditnotes.restricted_records}
        <tr>
            <td colspan="13">{t}Not all records are shown here.{/t} {t}Click{/t} <a href="index.php?component=creditnote&page_tpl=search">{t}here{/t}</a> {t}to see all records.{/t}</td>
        </tr>
    {/if}
    {if !$display_creditnotes.records}
        <tr>
            <td colspan="13" class="error">{t}There are no creditnotes.{/t}</td>
        </tr>        
    {/if} 
</table>