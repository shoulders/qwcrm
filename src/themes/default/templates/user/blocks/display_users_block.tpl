<!-- display_users_block.tpl -->
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
        <td class="olohead">{t}Display Name{/t}</td>
        <td class="olohead">{t}First Name{/t}</td>
        <td class="olohead">{t}Last Name{/t}</td>
        <td class="olohead">{t}Type{/t}</td>
        <td class="olohead">{t}Usergroup{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Email{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=u loop=$display_users}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=user&page_tpl=details&user_id={$display_users[u].user_id}';" class="row1">
            <td class="olotd4"><a href="index.php?component=user&page_tpl=details&user_id={$display_users[u].user_id}">{$display_users[u].user_id}</a></td>
            <td class="olotd4">
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('{$display_users[u].address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$display_users[u].city}<br>{$display_users[u].state}<br>{$display_users[u].zip}<br>{$display_users[u].country}');" onMouseOut="hideddrivetip();">
                {$display_users[u].display_name}
            </td>
            <td class="olotd4">{$display_users[u].first_name}</td>
            <td class="olotd4">{$display_users[u].last_name}</td>
            <td class="olotd4">
                {if $display_users[u].is_employee == '0'}{t}Customer{/t}{/if}
                {if $display_users[u].is_employee == '1'}{t}Employee{/t}{/if}                                                            
            </td>
            <td class="olotd4">
                {section name=g loop=$usergroups}
                    {if $display_users[u].usergroup == $usergroups[g].usergroup_id}{$usergroups[g].usergroup_display_name}{/if}
                {/section}   
            </td>
            <td class="olotd4">
                {if $display_users[u].active == '0'}{t}Blocked{/t}{/if}
                {if $display_users[u].active == '1'}{t}Active{/t}{/if}                                                            
            </td>
            <td class="olotd4"><a href="mailto: {$display_users[u].email}"><font class="blueLink">{$display_users[u].email}</font></a></td>
            <td class="olotd4"><a href="index.php?component=user:details&user_id={$display_users[u].user_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Users Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;<a href="index.php?page=user&page_tpl=edit&user_id={$display_users[u].user_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a></td>                                                        
        </tr>
    {sectionelse}
        <tr>
            <td colspan="9" class="error">{t}There are no users.{/t}</td>
        </tr>        
    {/section}
</table>