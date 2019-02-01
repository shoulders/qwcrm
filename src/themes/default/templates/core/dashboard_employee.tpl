<!-- dashboard_employee.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            
            <!-- Surrounding Table (for styling) -->
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                
                <!-- Header -->
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm - Welcome to your Online Office{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CORE_DASHBOARD_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CORE_DASHBOARD_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td>                                    
                                    <table>
                                        
                                        <!-- Welcome Message -->
                                        <tr>
                                            <td>
                                                <table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{t}Welcome Message{/t}</b></td>
                                                    </tr>
                                                    <tr class="olotd4">
                                                        <td>{$welcome_msg}</td>
                                                    </tr>
                                                </table>
                                                <br> 
                                            </td>
                                        </tr>
                                        
                                        <!-- Employee Workorder Stats (logged in) -->
                                        <tr>
                                            <td>
                                                <a name="employee_workorder_stats"></a>
                                                {include file='workorder/blocks/display_workorder_current_stats_block.tpl' workorder_stats=$employee_workorder_stats block_title=_gettext("Work Order Current Stats")|cat:" ($login_display_name)"}
                                            </td>
                                        </tr> 
                                        
                                        <!-- Employee Workorders (logged in) -->
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellpadding="10" cellspacing="0">                                                    
                                                    <tr>
                                                        <td>
                                                            <a name="assigned"></a>
                                                            {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$employee_workorders_assigned block_title=_gettext("Assigned")|cat:" ($login_display_name)"}
                                                        </td>
                                                    </tr>                                                    
                                                    <tr>
                                                        <td>
                                                            <a name="waiting_for_parts"></a>
                                                            {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$employee_workorders_waiting_for_parts block_title=_gettext("Waiting for Parts")|cat:" ($login_display_name)"}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="scheduled"></a>
                                                            {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$employee_workorders_scheduled block_title=_gettext("Scheduled")|cat:" ($login_display_name)"}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="with_client"></a>
                                                            {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$employee_workorders_with_client block_title=_gettext("With Client")|cat:" ($login_display_name)"}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="on_hold"></a>
                                                            {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$employee_workorders_on_hold block_title=_gettext("On Hold")|cat:" ($login_display_name)"}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="management"></a>
                                                            {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$employee_workorders_management block_title=_gettext("Management")|cat:" ($login_display_name)"}
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
        </td>
    </tr>
</table>