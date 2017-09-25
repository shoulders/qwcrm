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
                                    <b>{t}Global{/t} - {t}Current Work Order Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Open{/t}</b></td>
                                            <td class="row2"><b>{t}Assigned{/t}</b></td>
                                            <td class="row2"><b>{t}Waiting for Parts{/t}</b></td>
                                            <td class="row2"><b>{t}Scheduled{/t}</b></td>
                                            <td class="row2"><b>{t}With Client{/t}</b></td>
                                            <td class="row2"><b>{t}On Hold{/t}</b></td>
                                            <td class="row2"><b>{t}Management{/t}</b></td>                                            
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$global_workorders_open_count}</td>
                                            <td>{$global_workorders_assigned_count}</td>
                                            <td>{$global_workorders_waiting_for_parts_count}</td>
                                            <td>{$global_workorders_scheduled_count}</td>
                                            <td>{$global_workorders_with_client_count}</td>
                                            <td>{$global_workorders_on_hold_count}</td>
                                            <td>{$global_workorders_management_count}</td>                                                                                     
                                        </tr>
                                    </table>                                 
                                </td>
                            </tr>
                            
                            <!-- Global - Overall Work Order Stats -->
                            <tr>
                                <td>
                                    <b>{t}Global{/t} - {t}Overall Work Order Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Opened{/t}</b></td>                                            
                                            <td class="row2"><b>{t}Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$global_workorders_opened_count}</td>                                             
                                            <td>{$global_workorders_closed_count}</td>                                            
                                        </tr>
                                    </table>                                 
                                </td>
                            </tr>
                                                        
                            <!-- Logged In Employee - Current Work Order Stats -->
                            <tr>
                                <td>
                                    <b>{t}Employee{/t} ({$login_display_name}) - {t}Current Work Order Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Open{/t}</b></td>
                                            <td class="row2"><b>{t}Assigned{/t}</b></td>
                                            <td class="row2"><b>{t}Waiting for Parts{/t}</b></td>
                                            <td class="row2"><b>{t}Scheduled{/t}</b></td>
                                            <td class="row2"><b>{t}With Client{/t}</b></td>
                                            <td class="row2"><b>{t}On Hold{/t}</b></td>
                                            <td class="row2"><b>{t}Management{/t}</b></td>  
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$employee_workorders_open_count}</td>
                                            <td>{$employee_workorders_assigned_count}</td>
                                            <td>{$employee_workorders_waiting_for_parts_count}</td>
                                            <td>{$employee_workorders_scheduled_count}</td>
                                            <td>{$employee_workorders_with_client_count}</td> 
                                            <td>{$employee_workorders_on_hold_count}</td>
                                            <td>{$employee_workorders_management_count}</td>                                            
                                        </tr>
                                    </table>                                     
                                </td>
                            </tr>
                            
                            <!-- Logged In Employee - Overall Work Order Stats -->
                            <tr>
                                <td>
                                    <b>{t}Employee{/t} ({$login_display_name}) - {t}Overall Work Order Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Opened{/t}</b></td>                                            
                                            <td class="row2"><b>{t}Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$employee_workorders_opened_count}</td>                                             
                                            <td>{$employee_workorders_closed_count}</td>                                            
                                        </tr>
                                    </table>                                 
                                </td>
                            </tr>                            
                            
                            <!-- Invoice Stats --> 
                            <tr>
                                <td>
                                    <b>{t}Global{/t} - {t}Current Invoice Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Unpaid{/t}</b></td>
                                            <td class="row2"><b>{t}Partially Paid{/t}</b></td>
                                            <td class="row2"><b>{t}Paid{/t}</b></td>
                                            <td class="row2"><b>{t}Total{/t}</b></td>                                                                                      
                                            <td class="row2"><b>{t}Received Monies Total{/t}</b></td>
                                            <td class="row2"><b>{t}Outstanding Balance{/t}</b></td>                                            
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$global_invoices_unpaid_count}</td>
                                            <td>{$global_invoices_partially_paid_count}</td>
                                            <td>{$global_invoices_paid_count}</td>                                            
                                            <td><font color="green">{$currency_sym}{$global_invoiced_total|string_format:"%.2f"}</font></td>
                                            <td><font color="green">{$currency_sym}{$global_received_monies|string_format:"%.2f"}</font></td>
                                            <td><font color="cc0000">{$currency_sym}{$global_outstanding_balance|string_format:"%.2f"}</font></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <b>{t}Global{/t} - {t}Overall Invoice Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Opened{/t}</b></td>
                                            <td class="row2"><b>{t}Closed{/t}</b></td>                                                                                        
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$global_invoices_opened_count}</td>
                                            <td>{$global_invoices_closed_count}</td>                                            
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