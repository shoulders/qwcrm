<!-- display_otherincome_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">{t}Other Income ID{/t}</td>
        <td class="olohead">{t}Payee{/t}</td>
        <td class="olohead">{t}Date{/t}</td>
        <td class="olohead">{t}Item Type{/t}</td>
        {if '/^vat_/'|preg_match:$qw_tax_system} 
            <td class="olohead">{t}Net{/t}</td>
            <td class="olohead">{t}VAT{/t} {t}Rate{/t}</td>
            <td class="olohead">{t}VAT{/t}</td>
        {/if}
        <td class="olohead">{t}Gross{/t}</td>
        <td class="olohead">{t}Balance{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Note{/t}</td>
        <td class="olohead">{t}Items{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=r loop=$display_otherincomes}                                                            
        <!-- This allows double clicking on a row and opens the corresponding otherincome view details -->
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_otherincomes[r].status != 'deleted'} onDblClick="window.location='index.php?component=otherincome&page_tpl=details&otherincome_id={$display_otherincomes[r].otherincome_id}';"{/if}>                                                                
            <td class="olotd4" nowrap><a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$display_otherincomes[r].otherincome_id}">{$display_otherincomes[r].otherincome_id}</a></td>
            <td class="olotd4" nowrap>{$display_otherincomes[r].payee}</td>                                                                
            <td class="olotd4" nowrap>{$display_otherincomes[r].date|date_format:$date_format}</td>                                                                
            <td class="olotd4" nowrap>
                {section name=s loop=$otherincome_types}    
                    {if $display_otherincomes[r].item_type == $otherincome_types[s].type_key}{t}{$otherincome_types[s].display_name}{/t}{/if}        
                {/section}   
            </td>                                                                
             {if '/^vat_/'|preg_match:$qw_tax_system} 
                <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes[r].unit_net|string_format:"%.2f"}</td>
                <td class="olotd4" nowrap>{$display_otherincomes[r].unit_tax_rate|string_format:"%.2f"}%</td>
                <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes[r].unit_tax|string_format:"%.2f"}</td>
            {/if}                                                            
            <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes[r].unit_gross|string_format:"%.2f"}</td>  
            <td class="olotd4" nowrap>{$currency_sym}{$display_otherincomes[r].balance|string_format:"%.2f"}</td> 
            <td class="olotd4" nowrap>
               {section name=s loop=$otherincome_statuses}    
                   {if $display_otherincomes[r].status == $otherincome_statuses[s].status_key}{t}{$otherincome_statuses[s].display_name}{/t}{/if}        
               {/section} 
            </td> 
            <td class="olotd4" nowrap>{if $display_otherincomes[r].note != ''}<img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_otherincomes[r].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">{/if}</td>                                                            
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_otherincomes[r].items|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();"></td>                                                                
            <td class="olotd4" nowrap>
                <a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$display_otherincomes[r].otherincome_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}View Other IncomeDetails{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=otherincome&page_tpl=edit&otherincome_id={$display_otherincomes[r].otherincome_id}">
                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit Other IncomeDetails{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                {*<a href="index.php?component=otherincome&page_tpl=delete&otherincome_id={$display_otherincomes[r].otherincome_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Other IncomeRecord? This will permanently remove the record from the database.{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Other IncomeRecord{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>*}
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="13" class="error">{t}There are no otherincomes.{/t}</td>
        </tr>        
    {/section}
</table>