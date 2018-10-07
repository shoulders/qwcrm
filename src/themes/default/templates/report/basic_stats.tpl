<!-- basic_stats.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Basic QWcrm Statistics{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REPORT_BASIC_STATS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REPORT_BASIC_STATS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">                            
                            
                            <!-- Current Work Order Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_stats_block.tpl' workorder_stats=$global_workorder_stats block_title=_gettext("Current Work Order Stats")|cat:" ("|cat:_gettext("Global")|cat:")"} 
                                </td>
                            </tr>                            
                            
                            <!-- Overall Work Order Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_overall_stats_block.tpl' workorder_overall_stats=$global_workorder_overall_stats block_title=_gettext("Overall Work Order Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                             
                                </td>
                            </tr>                      
                            
                            <!-- Current Invoice Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='invoice/blocks/display_invoice_stats_block.tpl' invoice_stats=$global_invoice_stats block_title=_gettext("Current Invoice Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                            
                                </td>
                            </tr>
                            
                            <!-- Overall Invoice Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='invoice/blocks/display_invoice_overall_stats_block.tpl' invoice_overall_stats=$global_invoice_overall_stats block_title=_gettext("Overall Invoice Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                              
                                </td>
                            </tr>                                                        
                            
                            <!-- Client Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='client/blocks/display_client_overall_stats_block.tpl' client_overall_stats=$global_client_overall_stats block_title=_gettext("Overall Client Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                             
                                </td>
                            </tr>
                            
                            <!-- Separator -->
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            
                            <!-- Current Work Order Stats (Logged in Employee) -->
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_stats_block.tpl' workorder_stats=$employee_workorder_stats block_title=_gettext("Current Work Order Stats")|cat:" ($login_display_name)"}                                          
                                </td>
                            </tr>                            
                            
                            <!-- Overall Work Order Stats (Logged in Employee) -->
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_overall_stats_block.tpl' workorder_overall_stats=$employee_workorder_overall_stats block_title=_gettext("Overall Work Order Stats")|cat:" ($login_display_name)"}                                            
                                </td>
                            </tr>                            
                            
                        </table>
                    </td>
                </tr>
            </table>
        </tr>
</table>