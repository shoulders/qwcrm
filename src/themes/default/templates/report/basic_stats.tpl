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
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}REPORT_BASIC_STATS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}REPORT_BASIC_STATS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
                                                          
                            <!-- Employee Stats (Logged in user) -->
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_current_stats_block.tpl' workorder_stats=$employee_workorder_current_stats block_title=_gettext("Work Order Current Stats")|cat:" ($login_display_name)"}                                          
                                </td>
                            </tr>                            
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_historic_stats_block.tpl' workorder_stats=$employee_workorder_historic_stats block_title=_gettext("Work Order Historic Stats")|cat:" ($login_display_name)"}                                            
                                </td>
                            </tr>
                            
                            <!-- Client Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='client/blocks/display_client_historic_stats_block.tpl' client_stats=$global_client_historic_stats block_title=_gettext("Client Historic Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                             
                                </td>
                            </tr>                            
                            
                            <!-- Work Order Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_current_stats_block.tpl' workorder_stats=$global_workorder_current_stats block_title=_gettext("Work Order Current Stats")|cat:" ("|cat:_gettext("Global")|cat:")"} 
                                </td>
                            </tr>                            
                            <tr>
                                <td>
                                    {include file='workorder/blocks/display_workorder_historic_stats_block.tpl' workorder_stats=$global_workorder_historic_stats block_title=_gettext("Work Order Historic Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                             
                                </td>
                            </tr>                      
                            
                            <!-- Invoice Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='invoice/blocks/display_invoice_current_stats_block.tpl' invoice_stats=$global_invoice_current_stats block_title=_gettext("Invoice Current Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                            
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {include file='invoice/blocks/display_invoice_historic_stats_block.tpl' invoice_stats=$global_invoice_historic_stats block_title=_gettext("Invoice Historic Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                              
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {include file='invoice/blocks/display_invoice_revenue_stats_block.tpl' invoice_stats=$global_invoice_revenue_stats block_title=_gettext("Invoice Revenue Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                              
                                </td>
                            </tr> 
                            
                            <!-- Voucher Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='voucher/blocks/display_voucher_revenue_stats_block.tpl' voucher_stats=$global_voucher_revenue_stats block_title=_gettext("Voucher Revenue Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                            
                                </td>
                            </tr>
                            
                            <!-- Payment Stats (Global) -->
                            <tr>
                                <td>
                                    {include file='payment/blocks/display_payment_revenue_stats_block.tpl' payment_stats=$global_payment_revenue_stats block_title=_gettext("Payment Revenue Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                            
                                </td>
                            </tr>


                            <!-- Buttons -->
                            <tr>
                                <td><button onclick="printThisPage();">{t}Print this report{/t}</button></td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
        </tr>
</table>