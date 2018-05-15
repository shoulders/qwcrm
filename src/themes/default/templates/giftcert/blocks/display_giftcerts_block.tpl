<!-- display_giftcerts_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
    <tr>
        <td class="olohead">{t}ID{/t}</td>
        <td class="olohead">{t}Code{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Expires{/t}</td>
        <td class="olohead">{t}Date Redeemed{/t}</td>
        <td class="olohead">{t}Is Active{/t}</td>
        <td class="olohead">{t}Amount{/t}</td>
        <td class="olohead">{t}Notes{/t}</td> 
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=g loop=$display_giftcerts}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=giftcert&page_tpl=details&giftcert_id={$display_giftcerts[g].user_id}';" class="row1">
            <td class="olotd4"><a href="index.php?component=giftcert&page_tpl=details&giftcert_id={$display_giftcerts[g].giftcert_id}">{$display_giftcerts[g].giftcert_id}</a></td>
            <td class="olotd4">{$display_giftcerts[g].giftcert_code}</td>
            <td class="olotd4"><a href="index.php?component=customer&page_tpl=details&customer_id={$display_giftcerts[g].customer_id}">{$display_giftcerts[g].customer_display_name}</a></td>
            <td class="olotd4">{$display_giftcerts[g].date_expires|date_format:$date_format}</td>
            <td class="olotd4">
                {if !$display_giftcerts[g].date_redeemed == ''}
                    {$display_giftcerts[g].date_redeemed|date_format:$date_format}
                {/if}
            </td>
            <td class="olotd4">
                {if $display_giftcerts[g].active == '1'}{t}Active{/t}{/if}
                {if $display_giftcerts[g].active == '0'}{t}Blocked{/t}{/if}
            </td> 
            <td class="olotd4">{$currency_sym} {$display_giftcerts[g].amount}</td>                                                            
            <td class="olotd4" nowrap>
                {if $display_giftcerts[g].notes != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Notes{/t}</strong></div><hr><div>{$display_giftcerts[g].notes}</div>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4">
                <a href="index.php?component=giftcert&page_tpl=details&giftcert_id={$display_giftcerts[g].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?component=giftcert&page_tpl=edit&giftcert_id={$display_giftcerts[g].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?component=giftcert&page_tpl=print&giftcert_id={$display_giftcerts[g].giftcert_id}&print_content=gift_certificate&print_type=print_html&theme=print" target="_blank"> 
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Print the Gift Certificate{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=giftcert&page_tpl=delete&giftcert_id={$display_giftcerts[g].giftcert_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Gift Certificate?{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Gift Certificate{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="6" class="error">{t}There are no gift certificates.{/t}</td>
        </tr>        
    {/section}
</table>