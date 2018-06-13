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
                            
                            <!-- Global - Current Work Order Stats -->
                            <tr>
                                <td>
                                    <a name="global_workorder_stats"></a>                                                
                                    {include file='workorder/blocks/display_workorder_stats_block.tpl' workorder_stats=$global_workorder_stats block_title=_gettext("Current Global Work Order Stats")}                                               
                                </td>
                            </tr>
                            
                            
                            <!-- Global - Overall Work Order Stats -->
                            <tr>
                                <td>
                                    <a name="global_workorder_stats"></a>                                                
                                    {include file='workorder/blocks/display_workorder_overall_stats_block.tpl' workorder_overall_stats=$global_workorder_overall_stats block_title=_gettext("Global Overall Work Order Stats")}                                             
                                </td>
                            </tr>  
                                                        
                            <!-- Logged In Employee - Current Work Order Stats -->
                            <tr>
                                <td>
                                    <a name="employee_workorder_stats"></a>                                                
                                    {include file='workorder/blocks/display_workorder_stats_block.tpl' workorder_stats=$employee_workorder_stats block_title=_gettext("Employee Current Work Order Stats")}                                             
                                </td>
                            </tr>                            
                            
                            <!-- Logged In Employee - Overall Work Order Stats -->
                            <tr>
                                <td>
                                    <a name="employee_workorder_overall_stats"></a>                                                
                                    {include file='workorder/blocks/display_workorder_overall_stats_block.tpl' workorder_overall_stats=$employee_workorder_overall_stats block_title=_gettext("Employee Overall Work Order Stats")}                                             
                                </td>
                            </tr> 
                            
                            <!-- Global - Current Invoice Stats -->
                            <tr>
                                <td>
                                    <a name="global_invoice_stats"></a>                                                
                                    {include file='invoice/blocks/display_invoice_stats_block.tpl' invoice_stats=$global_invoice_stats block_title=_gettext("Global - Current Invoice Stats")}                                             
                                </td>
                            </tr>
                            
                            <!-- Global - Overall Invoice Stats -->
                            <tr>
                                <td>
                                    <a name="global_invoice_overall_stats"></a>                                                
                                    {include file='invoice/blocks/display_invoice_overall_stats_block.tpl' invoice_overall_stats=$global_invoice_overall_stats block_title=_gettext("Global - Overall Invoice Stats")}                                             
                                </td>
                            </tr>                                                        
                            
                            <!-- Global - Customer Stats -->
                            <tr>
                                <td>
                                    <a name="global_customer_overall_stats"></a>                                                
                                    {include file='customer/blocks/display_customer_overall_stats_block.tpl' customer_overall_stats=$global_customer_overall_stats block_title=_gettext("Global - Customer Stats")}                                             
                                </td>
                            </tr>                            
                            
                        </table>
                    </td>
                </tr>
            </table>
        </tr>
</table>