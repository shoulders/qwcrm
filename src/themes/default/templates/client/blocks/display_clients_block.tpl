<!-- display_clients_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">ID</td>
        <td class="olohead">{t}Name{/t}</td>
        <td class="olohead">{t}Type{/t}</td>
        <td class="olohead">{t}Phone{/t}</td>        
        <td class="olohead">{t}Email{/t}</td>
        <td class="olohead">{t}Active{/t}</td>
        <td class="olohead">{t}Note{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=c loop=$display_clients.records}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=client&page_tpl=details&client_id={$display_clients.records[c].client_id}';" class="row1">
            <td class="olotd4" nowrap><a href="index.php?component=client&page_tpl=details&client_id={$display_clients.records[c].client_id}">{$display_clients.records[c].client_id}</a></td>
            <td class="olotd4" nowrap><a href="index.php?component=client&page_tpl=details&client_id={$display_clients.records[c].client_id}">{$display_clients.records[c].display_name}</a></td>
            <td class="olotd4" nowrap>
                {section name=s loop=$client_types}    
                    {if $display_clients.records[c].type == $client_types[s].type_key}{t}{$client_types[s].display_name}{/t}{/if}        
                {/section}
            </td>
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{t}Mobile{/t}: </b>{$display_clients.records[c].mobile_phone}<br><b>{t}Fax{/t}:</b>{$display_clients.records[c].fax}');" onMouseOut="hideddrivetip();">{$display_clients.records[c].primary_phone}</td>            
            <td class="olotd4" nowrap><a href="mailto:{$display_clients.records[c].email}"><font class="blueLink">{$display_clients.records[c].email}</font></a></td>
            <td class="olotd4" nowrap>{if $display_clients.records[c].active == 1}{t}Active{/t}{else}{t}Blocked{/t}{/if}</td>
            <td class="olotd4" nowrap>
                {if $display_clients.records[c].note}
                     <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_clients.records[c].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                 {/if}
            </td>
            <td class="olotd4" nowrap>
                <a href="index.php?component=client&page_tpl=details&client_id={$display_clients.records[c].client_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}View Client Details{/t}');" onMouseOut="hideddrivetip()"></a>&nbsp;
                <a href="index.php?component=workorder&page_tpl=new&client_id={$display_clients.records[c].client_id}"><img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Work Order{/t}');" onMouseOut="hideddrivetip();" alt=""></a>&nbsp;
                <a href="index.php?component=invoice&page_tpl=new&client_id={$display_clients.records[c].client_id}&invoice_type=invoice-only"><img src="{$theme_images_dir}icons/16x16/small_new_invoice_only.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Invoice Only{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
                <a href="index.php?component=user&page_tpl=new&client_id={$display_clients.records[c].client_id}"><img src="{$theme_images_dir}icons/16x16/small_new_client.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Client Login{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
            </td>
        </tr>
    {/section}    
    {if $display_clients.restricted_records}
        <tr>
            <td colspan="8">{t}Not all records are shown.{/t} {t}Click{/t} <a href="index.php?component=client&page_tpl=search">{t}here{/t}</a> {t}to see all records.{/t}</td>
        </tr>
    {/if}
    {if !$display_clients.records}
        <tr>
            <td colspan="8" class="error">{t}There are no clients.{/t}</td>
        </tr>        
    {/if} 
</table>