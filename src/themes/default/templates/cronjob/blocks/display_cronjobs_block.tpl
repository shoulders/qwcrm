<!-- display_cronjobs_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">{t}Cronjob ID{/t}</td>
        <td class="olohead">{t}Name{/t}</td>
        <td class="olohead">{t}Description{/t}</td>
        <td class="olohead">{t}Active{/t}</td>
        <td class="olohead">{t}Pseudo Cron System Allowed{/t}</td>        
        <td class="olohead">{t}Last Run Time{/t}</td>
        <td class="olohead">{t}Last Run Status{/t}</td>
        <td class="olohead">{t}Locked{/t}</td>
        <td class="olohead">{t}Minute{/t}</td>
        <td class="olohead">{t}Hour{/t}</td>
        <td class="olohead">{t}Day{/t}</td>
        <td class="olohead">{t}Month{/t}</td>
        <td class="olohead">{t}Weekday{/t}</td>        
        <td class="olohead">{t}Command{/t}</td>
        <td class="olohead">{t}Actions{/t}</td>
    </tr>
    {section name=r loop=$display_cronjobs.records}                                                            
        <!-- This allows double clicking on a row and opens the corresponding cron view details -->
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=cronjob&page_tpl=details&cronjob_id={$display_cronjobs.records[r].cronjob_id}';">                                                                
            <td class="olotd4" nowrap><a href="index.php?component=cronjob&page_tpl=details&cronjob_id={$display_cronjobs.records[r].cronjob_id}">{$display_cronjobs.records[r].cronjob_id}</a></td>
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].name}</td>
            <td class="olotd4" nowrap>{if $display_cronjobs.records[r].description != ''}<img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Description{/t}</strong></div><hr><div>{$display_cronjobs.records[r].description|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">{/if}</td>                                                            
            <td class="olotd4" nowrap>{if $display_cronjobs.records[r].active == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td>
            <td class="olotd4" nowrap>{if $display_cronjobs.records[r].pseudo_allowed == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td>             
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].last_run_time|date_format:$date_format} {$display_cronjobs.records[r].last_run_time|date_format:'H:i:s'}</td>   
            <td class="olotd4">{if $display_cronjobs.records[r].last_run_status == 1}{t}Success{/t}{else}{t}Failed{/t}{/if}</td>
            <td class="olotd4">{if $display_cronjobs.records[r].locked == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td> 
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].minute}</td>
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].hour}</td>
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].day}</td>
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].month}</td>
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].weekday}</td>            
            <td class="olotd4" nowrap>{$display_cronjobs.records[r].command|regex_replace:"/\{|\}|\"/":""|regex_replace:"/,/":" , "}</td>
                     
            <td class="olotd4" nowrap>
                <a href="index.php?component=cronjob&page_tpl=details&cronjob_id={$display_cronjobs.records[r].cronjob_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}Details{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=cronjob&page_tpl=edit&cronjob_id={$display_cronjobs.records[r].cronjob_id}">
                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit{/t}</b>');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?component=cronjob&page_tpl=run&cronjob_id={$display_cronjobs.records[r].cronjob_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}Run Now{/t}');" onMouseOut="hideddrivetip();" onclick="return confirm('{t}Are you Sure you want to run this cron now?{/t}');">
                </a>
            </td>
        </tr>
    {/section}
    {if $display_cronjobs.restricted_records}
        <tr>
            <td colspan="13">{t}Not all records are shown.{/t} {t}Click{/t} <a href="index.php?component=cronjob&page_tpl=overview">{t}here{/t}</a> {t}to see all records.{/t}</td>
        </tr>
    {/if}
    {if !$display_cronjobs.records}
        <tr>
            <td colspan="13" class="error">{t}There are no crons.{/t}</td>
        </tr>        
    {/if} 
</table>