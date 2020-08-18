<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">                
                <tr>                    
                    <td class="menuhead2" width="80%">{t}Cronjob Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=cronjob&page_tpl=edit&cronjob_id={$cronjob_id}" ><img src="{$theme_images_dir}icons/edit.gif" alt="" height="16" border="0">{t}Edit{/t}</a>&nbsp;
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CRONJOB_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CRONJOB_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                             
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Client Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{t}Cronjob ID{/t} {$cronjob_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Cron ID{/t}</b></td>
                                            <td class="menutd">{$cronjob_id}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Name{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.name}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Description{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.description}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Active{/t}</b></td>
                                            <td class="menutd">{if $cronjob_details.active == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td>                                                                                  
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Pseudo Cron System Allowed{/t}</b></td>
                                            <td class="menutd">{if $cronjob_details.pseudo_allowed == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Last Active{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.last_run_time|date_format:$date_format} {$cronjob_details.last_run_time|date_format:'H:i:s'}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Last Run Status{/t}</b></td>
                                            <td class="menutd">{if $cronjob_details.last_run_status == 1}{t}Success{/t}{else}{t}Failed{/t}{/if}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Locked{/t}</b></td>
                                            <td class="menutd">{if $cronjob_details.locked == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;"><hr></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Minute{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.minute}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Hour{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.hour}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Day{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.day}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Month{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.month}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Weekday{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.weekday}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Command{/t}</b></td>
                                            <td class="menutd">{$cronjob_details.command|regex_replace:"/\{|\}|\"/":""|regex_replace:"/,/":" , "}</td>                                                                                     
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;"><hr></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">                                                
                                                <button type="button" class="olotd4" onclick="if (confirm('{t}Are you Sure you want to run this cronjob now?{/t}')) window.location.href='index.php?component=cronjob&page_tpl=run&cronjob_id={$cronjob_id}';">{t}Run Now{/t}</button>
                                                {if $cronjob_details.locked}<button type="button" class="olotd4" onclick="if (confirm('{t}Are you Sure you want to unlock this cronjob?{/t}')) window.location.href='index.php?component=cronjob&page_tpl=unlock&unlock_type=cronjob&cronjob_id={$cronjob_id}';">{t}Unlock{/t}</button>{/if}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>                
            </table>
        </td>
    </tr>
</table>