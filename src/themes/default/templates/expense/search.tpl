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
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Expense Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}EXPENSE_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}EXPENSE_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?component=expense&page_tpl=search" name="expense_search" id="expense_search" class="remove-empty-values">
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Search{/t}</b>
                                                                   <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">
                                                                        <option value="payee"{if $search_category == 'payee'} selected{/if}>{t}Payee{/t}</option>
                                                                        <option value="expense_id"{if $search_category == 'expense_id'} selected{/if}>{t}Expense ID{/t}</option>                                                                        
                                                                        <option value="unit_gross"{if $search_category == 'unit_gross'} selected{/if}>{t}Gross{/t}</option>
                                                                        <option disabled>----------</option>   
                                                                        <option value="exepense_items"{if $search_category == 'expense_items'} selected{/if}>{t}Items{/t}</option>
                                                                        <option disabled>----------</option>   
                                                                        <option value="note"{if $search_category == 'note'} selected{/if}>{t}Note{/t}</option>                                                                        
                                                                    </select>
                                                                   <br />
                                                                   <b>{t}for{/t}</b>
                                                                   <br />
                                                                   <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" onkeydown="return onlySearch(event);">
                                                                   <button type="submit" name="submit" value="submit">{t}Search{/t}</button>
                                                                   <button type="button" class="olotd4" onclick="window.location.href='index.php?component=expense&page_tpl=search';">{t}Reset{/t}</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Type{/t}</b><br>
                                                                    <select class="olotd5" id="filter_type" name="filter_type">
                                                                        <option value=""{if !$filter_type} selected{/if}>{t}None{/t}</option>
                                                                        <option disabled>----------</option>                                                                        
                                                                        {section name=t loop=$expense_types}    
                                                                            <option value="{$expense_types[t].type_key}"{if $filter_type == $expense_types[t].type_key} selected{/if}>{t}{$expense_types[t].display_name}{/t}</option>        
                                                                        {/section}
                                                                    </select>
                                                                </td>
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
                                                                        {section name=s loop=$expense_statuses}    
                                                                            <option value="{$expense_statuses[s].status_key}"{if $filter_status == $expense_statuses[s].status_key} selected{/if}>{t}{$expense_statuses[s].display_name}{/t}</option>        
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
                                                                {if $display_expenses.previous_page_no && $display_expenses.records}
                                                                    <a href="index.php?component=expense&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no=1{if $filter_type}&filter_type={$filter_type}{/if}"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                    <a href="index.php?component=expense&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_expenses.previous_page_no}{if $filter_type}&filter_type={$filter_type}{/if}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                                {/if}
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$display_expenses.total_pages start=1}
                                                                        <option value="index.php?component=expense&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $display_expenses.page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$display_expenses.total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?component=expense&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_expenses.total_pages}" {if $display_expenses.page_no == $display_expenses.total_pages} selected {/if}>
                                                                        {t}Page{/t} {$display_expenses.total_pages} {t}of{/t} {$display_expenses.total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                {if $display_expenses.next_page_no && $display_expenses.records}
                                                                    <a href="index.php?component=expense&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_expenses.next_page_no}{if $filter_type}&filter_type={$filter_type}{/if}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                    <a href="index.php?component=expense&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_expenses.total_pages}{if $filter_type}&filter_type={$filter_type}{/if}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                                {/if}
                                                            </td>                                                                                             
                                                    
                                                        </tr>
                                                        <tr>

                                                            <!-- Page Number Display -->
                                                            <td></td>
                                                            <td>
                                                                <p style="text-align: center;">{$display_expenses.total_results} {t}records found.{/t}</p>
                                                            </td>
                                                            
                                                        </tr>                                                    
                                                    </table>                                                    
                                                </form>                                                
                                            </td>
                                            
                                        </tr>
                                        
                                        <!-- Records Table -->
                                        <tr>                                            
                                            <td valign="top" colspan="2">                                                
                                                {include file='expense/blocks/display_expenses_block.tpl' display_vouchers=$display_expenses.records block_title=''}
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