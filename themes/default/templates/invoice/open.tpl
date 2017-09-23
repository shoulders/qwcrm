<!-- open.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Open Invoices{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_OPEN_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_OPEN_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                        
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            
                                            <!-- Navigation -->
                                            <td valign="top" nowrap align="right">
                                                <form id="navigation">                                                    
                                                    <table>
                                                        <tr>
                                                            
                                                            <!-- Left buttons -->
                                                            <td>                                                                
                                                                <a href="index.php?page=invoice:open&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=invoice:open&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=invoice:open&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=invoice:open&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=invoice:open&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=invoice:open&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                            </td>                                                                                             
                                                    
                                                        </tr>
                                                        <tr>

                                                            <!-- Page Number Display -->
                                                            <td></td>
                                                            <td>
                                                                <p style="text-align: center;">{$total_results} {t}records found.{/t}</p>
                                                            </td>
                                                            
                                                        </tr>                                                    
                                                    </table>                                                    
                                                </form>                                                
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead" nowarp>{t}Invoice ID{/t}</td>
                                                        <td class="olohead" nowarp>{t}Date{/t}</td>
                                                        <td class="olohead" nowarp>{t}Due Date{/t}</td>
                                                        <td class="olohead" nowarp>{t}Customer{/t}</td>
                                                        <td class="olohead" nowarp>{t}Work Order{/t}</td>
                                                        <td class="olohead" nowarp>{t}Employee{/t}</td>
                                                        <td class="olohead" nowarp>{t}Status{/t}</td>
                                                        <td class="olohead" nowarp>{t}Sub Total{/t}</td>                                                        
                                                        <td class="olohead" nowarp>{t}Discount{/t}</td>
                                                        <td class="olohead" nowarp>{t}Net{/t}</td>
                                                        <td class="olohead" nowarp>{t}VAT/Tax{/t}</td> 
                                                        <td class="olohead" nowarp>{t}Gross{/t}</td>                                                        
                                                    </tr>
                                                    {section name=q loop=$invoices}
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=invoice:edit&invoice_id={$invoices[q].invoice_id}';" class="row1">
                                                            <td class="olotd4" nowrap><a href="index.php?page=invoice:edit&invoice_id={$invoices[q].invoice_id}">{$invoices[q].invoice_id}</a></td>
                                                            <td class="olotd4" nowrap>{$invoices[q].date|date_format:$date_format}</td>
                                                            <td class="olotd4" nowrap>{$invoices[q].due_date|date_format:$date_format}</td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Contact{/t}: </b>{$invoices[q].customer_first_name} {$invoices[q].customer_last_name}<br><b>{t}Phone{/t}: </b>{$invoices[q].customer_phone}<br><b>{t}Mobile{/t}: </b>{$invoices[q].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$invoices[q].customer_fax}');" onMouseOut="hideddrivetip();"><a href="index.php?page=customer:details&customer_id={$invoices[q].customer_id}"> {$invoices[q].customer_display_name}</a></td>
                                                            <td class="olotd4" nowrap><a href="index.php?page=workorder:details&workorder_id={$invoices[q].workorder_id}">{$invoices[q].workorder_id}</a></td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$invoices[q].employee_work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$invoices[q].employee_work_mobile_phone}<br><b>{t}Personal{/t}: </b>{$invoices[q].employee_home_mobile_phone}');" onMouseOut="hideddrivetip();"><a  href="index.php?page=user:details&user_id={$invoices[q].employee_id}"> {$invoices[q].employee_display_name}</td>
                                                            <td class="olotd4" nowrap>
                                                                {section name=s loop=$invoice_statuses}    
                                                                    {if $invoices[q].status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}        
                                                                {/section} 
                                                            </td>
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoices[q].sub_total}</td> 
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoices[q].discount_amount}</td>
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoices[q].net_amount}</td> 
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoices[q].tax_amount}</td>                                                           
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoices[q].gross_amount}</td>                                                            
                                                        </tr>
                                                    {/section}
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
        </tr>
    </td>
</table>