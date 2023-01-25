<!-- display_otherincomes_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">{t}Otherincome ID{/t}</td>        
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
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=e loop=$display_otherincomes.records}
        <!-- This allows double clicking on a row and opens the corresponding otherincome view details -->
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_otherincomes.records[e].status != 'deleted'} onDblClick="window.location='index.php?component=otherincome&page_tpl=details&otherincome_id={$display_otherincomes.records[e].otherincome_id}';"{/if}>
            <td class="olotd4" nowrap><a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$display_otherincomes.records[e].otherincome_id}">{$display_otherincomes.records[e].otherincome_id}</a></td>
            <td class="olotd4" nowrap>{$display_otherincomes.records[e].payee}</td>
            <td class="olotd4" nowrap>{$display_otherincomes.records[e].date|date_format:$date_format}</td>                                                            
            <td class="olotd4" nowrap>
                {section name=s loop=$otherincome_types}    
                    {if $display_otherincomes.records[e].type == $otherincome_types[s].type_key}{t}{$otherincome_types[s].display_name}{/t}{/if}        
                {/section} 
            </td>
            {if '/^vat_/'|preg_match:$qw_tax_system} 
                <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes.records[e].unit_net|string_format:"%.2f"}</td>                
                <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes.records[e].unit_tax|string_format:"%.2f"}</td>
            {/if}
            <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes.records[e].unit_gross|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes.records[e].balance|string_format:"%.2f"}</td>
            <td class="olotd4" nowrap>
               {section name=s loop=$otherincome_statuses}    
                   {if $display_otherincomes.records[e].status == $otherincome_statuses[s].status_key}{t}{$otherincome_statuses[s].display_name}{/t}{/if}        
               {/section} 
            </td>
            <td class="olotd4" nowrap>
                {if $display_otherincomes.records[e].otherincome_items}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_otherincomes.records[e].otherincome_items|htmlentities|regex_replace:"/\|\|\|/":"<br>"|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_otherincomes.records[e].note}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_otherincomes.records[e].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                <a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$display_otherincomes.records[e].otherincome_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}View Otherincome Details{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=otherincome&page_tpl=edit&otherincome_id={$display_otherincomes.records[e].otherincome_id}">
                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit Otherincome Details{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                {*<a href="index.php?component=otherincome&page_tpl=delete&otherincome_id={$display_otherincomes.records[e].otherincome_id}" onclick="return confirm('{t}Are you Sure you want to delete this Otherincome Record? This will permanently remove the record from the database.{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Otherincome Record{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>*}
            </td>
        </tr>
    {/section}     
    {if $display_otherincomes.restricted_records}
        <tr>
            <td colspan="13">{t}Not all records are shown here.{/t} {t}Click{/t} <a href="index.php?component=otherincome&page_tpl=search">{t}here{/t}</a> {t}to see all records.{/t}</td>
        </tr>
    {/if}
    {if !$display_otherincomes.records}
        <tr>
            <td colspan="13" class="error">{t}There are no otherincomes.{/t}</td>
        </tr>        
    {/if}  
 </table>