<!-- overview.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Work Orders Overview{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}WORKORDER_OVERVIEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}WORKORDER_OVERVIEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                                        <!-- Search Bar -->
                                        <tr>
                                            <td>
                                                {include file='core/blocks/theme_searchbar_block.tpl'}
                                            </td>
                                        </tr> 
                                        
                                        <tr>
                                            <td>
                                                <a name="workorder_stats"></a>                                                
                                                {include file='workorder/blocks/display_workorder_current_stats_block.tpl' workorder_stats=$overview_workorder_stats block_title=_gettext("Work Order Current Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                              
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="unassigned"></a>                                                
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_unassigned block_title=_gettext("Unassigned")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="assigned"></a>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_assigned block_title=_gettext("Assigned")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="waiting_for_parts"></a>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_waiting_for_parts block_title=_gettext("Waiting for Parts")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="scheduled"></a>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_scheduled block_title=_gettext("Scheduled")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="with_client"></a>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_with_client block_title=_gettext("With Client")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="on_hold"></a>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_on_hold block_title=_gettext("On Hold")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="management"></a>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$overview_workorders_management block_title=_gettext("Management")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="button" onclick="window.open('index.php?component=workorder&page_tpl=print&commContent=technician_workorder_slip&commType=htmlBrowser&blankMedia=true');">{t}Print{/t} {t}Blank{/t} {t}Technician Work Order Slip{/t}</button><br>                                                  
                                                <button type="button" onclick="window.open('index.php?component=workorder&page_tpl=print&commContent=technician_job_sheet&commType=htmlBrowser&blankMedia=true');">{t}Print{/t} {t}Blank{/t} {t}Technician Job Sheet{/t}</button><br>
                                                <button type="button" onclick="window.open('index.php?component=workorder&page_tpl=print&commContent=client_workorder_slip&commType=htmlBrowser&blankMedia=true');">{t}Print{/t} {t}Blank{/t} {t}Client Work Order Slip{/t}</button><br>
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