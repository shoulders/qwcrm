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
        <td class="olohead" nowrap>{t}Customer{/t}</td>                                                        
        <td class="olohead" nowrap>{t}Employee{/t}</td>
        <td class="olohead" nowrap>{t}Labour{/t}</td>
        <td class="olohead" nowrap>{t}Parts{/t}</td>
        <td class="olohead" nowrap>{t}Status{/t}</td>        
        <td class="olohead" nowrap>{t}Net{/t}</td>
        <td class="olohead" nowrap>{t}Gross{/t}</td>
        <td class="olohead" nowrap>{t}Balance{/t}</td>
    </tr>
    {section name=i loop=$display_invoices}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=invoice&page_tpl={if $display_invoices[i].is_closed}details{else}edit{/if}&invoice_id={$display_invoices[i].invoice_id}';" class="row1">
            <td class="olotd4" nowrap><a href="index.php?component=invoice&page_tpl={if $display_invoices[i].is_closed}details{else}edit{/if}&invoice_id={$display_invoices[i].invoice_id}">{$display_invoices[i].invoice_id}</a></td>
            <td class="olotd4" nowrap><a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_invoices[i].workorder_id}">{$display_invoices[i].workorder_id}</a></td>
            <td class="olotd4" nowrap>{$display_invoices[i].date|date_format:$date_format}</td>            
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Contact{/t}: </b>{$display_invoices[i].customer_contact_name}<br><b>{t}Phone{/t}: </b>{$display_invoices[i].customer_phone}<br><b>{t}Mobile{/t}: </b>{$display_invoices[i].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$display_invoices[i].customer_fax}');" onMouseOut="hideddrivetip();"><a href="index.php?component=customer&page_tpl=details&customer_id={$display_invoices[i].customer_id}"> {$display_invoices[i].customer_display_name}</a></td>
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$display_invoices[i].employee_work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$display_invoices[i].employee_work_mobile_phone}<br><b>{t}Personal{/t}: </b>{$display_invoices[i].employee_home_mobile_phone}');" onMouseOut="hideddrivetip();"><a  href="index.php?component=user&page_tpl=details&user_id={$display_invoices[i].employee_id}"> {$display_invoices[i].employee_display_name}</td>
            <td class="olotd4" nowrap>
                {if $display_invoices[i].labour_items}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_invoices[i].labour_items|htmlentities|regex_replace:"/\|\|\|/":"<br>"}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_invoices[i].parts_items}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_invoices[i].parts_items|htmlentities|regex_replace:"/\|\|\|/":"<br>"}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {section name=s loop=$invoice_statuses}    
                    {if $display_invoices[i].status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}        
                {/section} 
            </td>            
            <td class="olotd4" nowrap>{$currency_sym}{$display_invoices[i].net_amount}</td>                                                          
            <td class="olotd4" nowrap>{$currency_sym}{$display_invoices[i].gross_amount}</td>
            <td class="olotd4" nowrap>{$currency_sym}{$display_invoices[i].balance}</td> 
        </tr>
    {sectionelse}
        <tr>
            <td colspan="13" class="error">{t}There are no invoices.{/t}</td>
        </tr>        
    {/section}
</table>