<!-- details_giftcert_block.tpl -->
<b>{t}Active Gift Certificates{/t}</b>
<table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
    <tr>
        <td class="olohead">{t}ID{/t}</td>
        <td class="olohead">{t}Code{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Expires{/t}</td>
        <td class="olohead">{t}Date Redeemed{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Amount{/t}</td>
        <td class="olohead">{t}Notes{/t}</td> 
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=i loop=$active_giftcerts}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=giftcert:details&giftcert_id={$active_giftcerts[i].user_id}';" class="row1">
            <td class="olotd4"><a href="index.php?page=giftcert:details&giftcert_id={$active_giftcerts[i].giftcert_id}">{$active_giftcerts[i].giftcert_id}</a></td>
            <td class="olotd4">{$active_giftcerts[i].giftcert_code}</td>
            <td class="olotd4"><a href="index.php?page=customer:details&customer_id={$active_giftcerts[i].customer_id}">{$active_giftcerts[i].display_name}</a></td>
            <td class="olotd4">{$active_giftcerts[i].DATE_EXPIRES|date_format:$date_format}</td>
            <td class="olotd4">
                {if !$active_giftcerts[i].date_redeemed == ''}
                    {$active_giftcerts[i].date_redeemed|date_format:$date_format}
                {/if}
            </td>
            <td class="olotd4">
                {if $active_giftcerts[i].status == '1'}{t}Active{/t}{/if}
                {if $active_giftcerts[i].status == '0'}{t}Blocked{/t}{/if}
            </td> 
            <td class="olotd4">{$currency_sym} {$active_giftcerts[i].amount}</td>                                                            
            <td class="olotd4" nowrap>
                {if !$active_giftcerts[i].notes == ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{t}Notes{/t}</b><hr><p>{$active_giftcerts[i].notes|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4">
                <a href="index.php?page=giftcert:details&giftcert_id={$active_giftcerts[i].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?page=giftcert:edit&giftcert_id={$active_giftcerts[i].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?page=giftcert:delete&giftcert_id={$active_giftcerts[i].giftcert_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Gift Certificate?{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Gift Certificate{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
            </td>
        </tr>
    {/section}
</table>
<b>{t}Redeemed Gift Certificates{/t}</b>
<table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
    <tr>
        <td class="olohead">{t}ID{/t}</td>
        <td class="olohead">{t}Code{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Expires{/t}</td>
        <td class="olohead">{t}Date Redeemed{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Amount{/t}</td>
        <td class="olohead">{t}Notes{/t}</td> 
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=i loop=$redeemed_giftcerts}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=giftcert:details&giftcert_id={$redeemed_giftcerts[i].user_id}';" class="row1">
            <td class="olotd4"><a href="index.php?page=giftcert:details&giftcert_id={$redeemed_giftcerts[i].giftcert_id}">{$redeemed_giftcerts[i].giftcert_id}</a></td>
            <td class="olotd4">{$redeemed_giftcerts[i].giftcert_code}</td>
            <td class="olotd4"><a href="index.php?page=customer:details&customer_id={$redeemed_giftcerts[i].customer_id}">{$redeemed_giftcerts[i].display_name}</a></td>
            <td class="olotd4">{$redeemed_giftcerts[i].date_expires|date_format:$date_format}</td>
            <td class="olotd4">
                {if !$redeemed_giftcerts[i].date_redeemed == ''}
                    {$redeemed_giftcerts[i].date_redeemed|date_format:$date_format}
                {/if}
            </td>
            <td class="olotd4">
                {if $redeemed_giftcerts[i].status == '1'}{t}Active{/t}{/if}
                {if $redeemed_giftcerts[i].status == '0'}{t}Blocked{/t}{/if}
            </td> 
            <td class="olotd4">{$currency_sym} {$redeemed_giftcerts[i].amount}</td>                                                            
            <td class="olotd4" nowrap>
                {if !$redeemed_giftcerts[i].NOTES == ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{t}Notes{/t}</b><hr><p>{$redeemed_giftcerts[i].notes|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                {/if}
            </td>
            <td class="olotd4">
                <a href="index.php?page=giftcert:details&giftcert_id={$redeemed_giftcerts[i].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?page=giftcert:edit&giftcert_id={$redeemed_giftcerts[i].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?page=giftcert:delete&giftcert_id={$redeemed_giftcerts[i].giftcert_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Gift Certificate?{/t}');">
                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Gift Certificate{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
            </td>
        </tr>
    {/section}
</table>