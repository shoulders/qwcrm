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
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CORE_BASIC_STATS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CORE_BASIC_STATS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">                            
                            
                            <!-- Work Order Stats -->
                            <tr>
                                <td>
                                    <b>{t}Overall Work Order Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Open{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_2{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_3{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_4{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_5{/t}</b></td>
                                            <td class="row2"><b>{t}Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$overall_workorders_open_count}</td>
                                            <td>{$overall_workorders_assigned_count}</td>
                                            <td>{$overall_workorders_waiting_for_parts_count}</td>
                                            <td>{$overall_workorders_on_hold_count}</td>
                                            <td>{$overall_workorders_management_count}</td> 
                                            <td>{$overall_workorders_total_closed_count}</td>                                            
                                        </tr>
                                    </table>                                 
                                </td>
                            </tr>
                                                        
                            <!-- Currently Logged In Employee Stats -->
                            <tr>
                                <td>
                                    <b>{t}Work Order Stats{/t} ({$login_display_name})</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Open{/t}</b></td>
                                            <td class="row2"><b>{t}Assigned{/t}</b></td>
                                            <td class="row2"><b>{t}Waiting For Parts{/t}</b></td>
                                            <td class="row2"><b>{t}On Hold{/t}</b></td>
                                            <td class="row2"><b>{t}Management{/t}</b></td>
                                            <td class="row2"><b>{t}Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$employee_workorders_open_count}</td>
                                            <td>{$employee_workorders_assigned_count}</td>
                                            <td>{$employee_workorders_waiting_for_parts_count}</td>
                                            <td>{$employee_workorders_on_hold_count}</td>
                                            <td>{$employee_workorders_management_count}</td> 
                                            <td>{$employee_workorders_total_closed_count}</td>                                            
                                        </tr>
                                    </table>                                     
                                </td>
                            </tr>
                            
                            <!-- Invoice Stats --> 
                            <tr>
                                <td>
                                    <b>{t}Invoices{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Unpaid{/t}</b></td>
                                            <td class="row2"><b>{t}Partially Paid{/t}</b></td>
                                            <td class="row2"><b>{t}Paid{/t}</b></td>
                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                            <td class="row2"><b>{t}Invoiced Total{/t}</b></td>                                            
                                            <td class="row2"><b>{t}Received Monies Total{/t}</b></td>
                                            <td class="row2"><b>{t}Outstanding Balance{/t}</b></td>                                            
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$overall_invoices_unpaid_count}</td>
                                            <td>{$overall_invoices_partially_paid_count}</td>
                                            <td>{$overall_invoices_paid_count}</td>
                                            <td>{$overall_invoices_count}</td>
                                            <td><font color="green">{$currency_sym}{$overall_invoiced_total|string_format:"%.2f"}</font></td>
                                            <td><font color="green">{$currency_sym}{$overall_received_monies|string_format:"%.2f"}</font></td>
                                            <td><font color="cc0000">{$currency_sym}{$overall_outstanding_balance|string_format:"%.2f"}</font></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Customer Stats -->
                            <tr>
                                <td>
                                    <b>{t}Customers{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}New This Month{/t}</b></td>
                                            <td class="row2"><b>{t}New This Year{/t}</b></td>
                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$customer_month_count}</td>
                                            <td>{$customer_year_count}</td>
                                            <td>{$customer_total_count}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
        </tr>
</table>