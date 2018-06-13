<!-- display_refunds_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">{t}Refund ID{/t}</td>
        <td class="olohead">{t}INV ID{/t}</td>
        <td class="olohead">{t}Payee{/t}</td>
        <td class="olohead">{t}Date{/t}</td>
        <td class="olohead">{t}Type{/t}</td>
        <td class="olohead">{t}Payment Method{/t}</td>
        <td class="olohead">{t}Net Amount{/t}</td>
        <td class="olohead">{t}VAT Rate{/t}</td>
        <td class="olohead">{t}VAT Amount{/t}</td>
        <td class="olohead">{t}Gross Amount{/t}</td>
        <td class="olohead">{t}Note{/t}</td>
        <td class="olohead">{t}Items{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=r loop=$display_refunds}                                                            
        <!-- This allows double clicking on a row and opens the corresponding refund view details -->
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=refund&page_tpl=details&refund_id={$display_refunds[r].refund_id}';" class="row1">                                                                
            <td class="olotd4" nowrap><a href="index.php?component=refund&page_tpl=details&refund_id={$display_refunds[r].refund_id}">{$display_refunds[r].refund_id}</a></td>                                                                
            <td class="olotd4" nowrap><a href="index.php?component=invoice&page_tpl=details&invoice_id={$display_refunds[r].invoice_id}">{$display_refunds[r].invoice_id}</a></td>
            <td class="olotd4" nowrap>{$display_refunds[r].payee}</td>                                                                
            <td class="olotd4" nowrap>{$display_refunds[r].date|date_format:$date_format}</td>                                                                
            <td class="olotd4" nowrap>
                {section name=s loop=$refund_types}    
                    {if $display_refunds[r].type == $refund_types[s].refund_type_id}{t}{$refund_types[s].display_name}{/t}{/if}        
                {/section}   
            </td>                                                                
            <td class="olotd4" nowrap>
                {section name=s loop=$payment_methods}    
                    {if $display_refunds[r].payment_method == $payment_methods[s].purchase_method_id}{t}{$payment_methods[s].display_name}{/t}{/if}        
                {/section} 
            </td>                                                               
            <td class="olotd4" nowrap>{$currency_sym} {$display_refunds[r].net_amount}</td>                                                               
            <td class="olotd4" nowrap>{$display_refunds[r].vat_rate} %</td>                                                                
            <td class="olotd4" nowrap>{$currency_sym} {$display_refunds[r].vat_amount}</td>                                                            
            <td class="olotd4" nowrap>{$currency_sym} {$display_refunds[r].gross_amount}</td>                                                                
            <td class="olotd4" nowrap>{if $display_refunds[r].note != ''}<img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_refunds[r].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">{/if}</td>                                                            
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$display_refunds[r].items|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();"></td>                                                                
            <td class="olotd4" nowrap>
                <a href="index.php?component=refund&page_tpl=details&refund_id={$display_refunds[r].refund_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}View Refund Details{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=refund&page_tpl=edit&refund_id={$display_refunds[r].refund_id}">
                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit Refund Details{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=refund&page_tpl=delete&refund_id={$display_refunds[r].refund_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Refund Record? This will permanently remove the record from the database.{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Refund Record{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="13" class="error">{t}There are no refunds.{/t}</td>
        </tr>        
    {/section}
</table>