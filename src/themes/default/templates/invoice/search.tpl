<!-- search.tpl -->
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
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Invoice Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}INVOICE_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}INVOICE_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            
                                            <!-- Category Search -->
                                            <td valign="top">
                                                <form method="post" action="index.php?component=invoice&page_tpl=search" name="invoice_search" id="invoice_search" class="remove-empty-values">
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Search{/t}</b>
                                                                   <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">                                                                        
                                                                        <option value="invoice_id"{if $search_category == 'invoice_id'} selected{/if}>{t}Invoice ID{/t}</option>
                                                                        <option value="workorder_id"{if $search_category == 'workorder_id'} selected{/if}>{t}Work Order ID{/t}</option>
                                                                        <option value="client_display_name"{if $search_category == 'client_display_name'} selected{/if}>{t}Client{/t}</option>
                                                                        <option value="employee_display_name"{if $search_category == 'employee_display_name'} selected{/if}>{t}Employee{/t}</option>                                                                                                                                                                                                                     
                                                                        <option disabled>----------</option>   
                                                                        <option value="unit_gross"{if $search_category == 'unit_gross'} selected{/if}>{t}Gross{/t}</option>
                                                                        <option disabled>----------</option>                                                                        
                                                                        <option value="invoice_items"{if $search_category == 'invoice_items'} selected{/if}>{t}Items{/t}</option>                                                                                                                                           
                                                                    </select>
                                                                   <br />
                                                                   <b>{t}for{/t}</b>
                                                                   <br />
                                                                   <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" onkeydown="return onlySearch(event);">
                                                                   <button type="submit" name="submit" value="search">{t}Search{/t}</button>
                                                                   <button type="button" class="olotd4" onclick="window.location.href='index.php?component=invoice&page_tpl=search';">{t}Reset{/t}</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Status{/t}</b><br>
                                                                    <select class="olotd5" id="filter_status" name="filter_status">
                                                                        <option value=""{if !$filter_status} selected{/if}>{t}None{/t}</option>
                                                                        <option disabled>----------</option>
                                                                        <option value="open"{if $filter_status == 'open'} selected{/if}>{t}Open{/t}</option> 
                                                                        <option value="closed"{if $filter_status == 'closed'} selected{/if}>{t}Closed{/t}</option>
                                                                        <option disabled>----------</option>
                                                                        {section name=s loop=$invoice_statuses}    
                                                                            <option value="{$invoice_statuses[s].status_key}"{if $filter_status == $invoice_statuses[s].status_key} selected{/if}>{t}{$invoice_statuses[s].display_name}{/t}</option>        
                                                                        {/section} 
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </form>                                                
                                            </td>

                                            <!-- Navigation -->
                                            <td valign="top" nowrap>
                                                <form id="navigation">                                                    
                                                    <table>
                                                        <tr>
                                                            
                                                            <!-- Left Side Buttons -->                                                            
                                                            <td>  
                                                                {if $display_invoices.previous_page_no && $display_invoices.records} 
                                                                    <a href="index.php?component=invoice&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no=1{if $filter_status}&filter_status={$filter_status}{/if}"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                    <a href="index.php?component=invoice&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_invoices.previous_page_no}{if $filter_status}&filter_status={$filter_status}{/if}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                                {/if}
                                                            </td>
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$display_invoices.total_pages start=1}
                                                                        <option value="index.php?component=invoice&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}{if $filter_status}&filter_status={$filter_status}{/if}" {if $display_invoices.page_no == $smarty.section.page.index } selected{/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$display_invoices.total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?component=invoice&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_invoices.total_pages}{if $filter_status}&filter_status={$filter_status}{/if}" {if $display_invoices.page_no == $display_invoices.total_pages} selected{/if}>
                                                                        {t}Page{/t} {$display_invoices.total_pages} {t}of{/t} {$display_invoices.total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                {if $display_invoices.next_page_no && $display_invoices.records}
                                                                    <a href="index.php?component=invoice&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_invoices.next_page_no}{if $filter_status}&filter_status={$filter_status}{/if}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                    <a href="index.php?component=invoice&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_invoices.total_pages}{if $filter_status}&filter_status={$filter_status}{/if}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                                {/if}
                                                            </td>                                                                                             
                                                    
                                                        </tr>
                                                        <tr>

                                                            <!-- Page Number Display -->
                                                            <td></td>
                                                            <td>
                                                                <p style="text-align: center;">{$display_invoices.total_results} {t}records found.{/t}</p>
                                                            </td>
                                                            
                                                        </tr>                                                    
                                                    </table>                                                    
                                                </form>                                                
                                            </td>
                                            
                                        </tr>
                                        
                                        <!-- Results Block -->
                                        <tr>
                                            <td valign="top" colspan="2">
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$display_invoices block_title=''}
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