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
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Payments Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}PAYMENT_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}PAYMENT_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?component=payment&page_tpl=search" name="payment_search" id="payment_search" class="remove-empty-values">
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Search{/t}</b>
                                                                   <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">
                                                                        <option value="payment_id"{if $search_category == 'payment_id'} selected{/if}>{t}Payment ID{/t}</option>
                                                                        <option value="employee_display_name"{if $search_category == 'employee_display_name'} selected{/if}>{t}Employee{/t}</option>
                                                                        <option value="client_display_name"{if $search_category == 'client_display_name'} selected{/if}>{t}Client{/t}</option>                                                                        
                                                                        <option value="invoice_id"{if $search_category == 'invoice_id'} selected{/if}>{t}Invoice ID{/t}</option>
                                                                        <option value="amount"{if $search_category == 'amount'} selected{/if}>{t}Amount{/t}</option>
                                                                        <option value="additional_info"{if $search_category == 'additional_info'} selected{/if}>{t}Additional Info{/t}</option>
                                                                        <option value="note"{if $search_category == 'note'} selected{/if}>{t}Note{/t}</option> 
                                                                    </select>
                                                                    <br />
                                                                    <b>{t}for{/t}</b>
                                                                    <br />
                                                                    <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" onkeydown="return onlySearch(event);">
                                                                    <button type="submit" name="submit" value="search">{t}Search{/t}</button>
                                                                    <button type="button" class="olotd4" onclick="window.location.href='index.php?component=payment&page_tpl=search';">{t}Reset{/t}</button>
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
                                                                        <option value="received"{if $filter_type == 'monies_received'} selected{/if}>{t}Monies Received{/t}</option> 
                                                                        <option value="sent"{if $filter_type == 'monies_sent'} selected{/if}>{t}Monies Sent{/t}</option>
                                                                        <option disabled>----------</option>
                                                                        {section name=m loop=$payment_types}    
                                                                            <option value="{$payment_types[m].type_key}"{if $filter_type == $payment_types[m].type_key} selected{/if}>{t}{$payment_types[m].display_name}{/t}</option>        
                                                                        {/section}
                                                                    </select>
                                                                </td>
                                                            <tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Method{/t}</b><br>
                                                                    <select class="olotd5" id="filter_method" name="filter_method">
                                                                        <option value=""{if !$filter_method} selected{/if}>{t}None{/t}</option>
                                                                        {section name=m loop=$payment_methods}    
                                                                            <option value="{$payment_methods[m].method_key}"{if $filter_method == $payment_methods[m].method_key} selected{/if}>{t}{$payment_methods[m].display_name}{/t}</option>        
                                                                        {/section}                                                                        
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Direction{/t}</b><br>
                                                                    <select class="olotd5" id="filter_direction" name="filter_direction">
                                                                        <option value=""{if !$filter_direction} selected{/if}>{t}None{/t}</option>                                                                        
                                                                        {section name=d loop=$payment_directions}    
                                                                            <option value="{$payment_directions[d].key}"{if $filter_status == $payment_directions[d].key} selected{/if}>{t}{$payment_directions[d].display_name}{/t}</option>        
                                                                        {/section} 
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Status{/t}</b><br>
                                                                    <select class="olotd5" id="filter_status" name="filter_status">
                                                                        <option value=""{if !$filter_status} selected{/if}>{t}None{/t}</option>                                                                        
                                                                        {section name=s loop=$payment_statuses}    
                                                                            <option value="{$payment_statuses[s].status_key}"{if $filter_status == $payment_statuses[s].status_key} selected{/if}>{t}{$payment_statuses[s].display_name}{/t}</option>        
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
                                                                {if $display_payments.previous_page_no && $display_payments.records} 
                                                                    <a href="index.php?component=payment&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no=1{if $filter_status}&filter_status={$filter_status}{/if}{if $filter_type}&filter_type={$filter_type}{/if}{if $filter_method}&filter_method={$filter_method}{/if}"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                    <a href="index.php?component=payment&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_payments.previous_page_no}{if $filter_status}&filter_status={$filter_status}{/if}{if $filter_type}&filter_type={$filter_type}{/if}{if $filter_method}&filter_method={$filter_method}{/if}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                                {/if}
                                                            </td>
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$display_payments.total_pages start=1}
                                                                        <option value="index.php?component=payment&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}{if $filter_type}&filter_type={$filter_type}{/if}{if $filter_method}&filter_method={$filter_method}{/if}" {if $display_payments.page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$display_payments.total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?component=payment&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_payments.total_pages}{if $filter_type}&filter_type={$filter_type}{/if}{if $filter_method}&filter_method={$filter_method}{/if}" {if $display_payments.page_no == $display_payments.total_pages} selected {/if}>
                                                                        {t}Page{/t} {$display_payments.total_pages} {t}of{/t} {$display_payments.total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                {if $display_payments.next_page_no && $display_payments.records}
                                                                    <a href="index.php?component=payment&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_payments.next_page_no}{if $filter_status}&filter_status={$filter_status}{/if}{if $filter_type}&filter_type={$filter_type}{/if}{if $filter_method}&filter_method={$filter_method}{/if}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                    <a href="index.php?component=payment&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$display_payments.total_pages}{if $filter_status}&filter_status={$filter_status}{/if}{if $filter_type}&filter_type={$filter_type}{/if}{if $filter_method}&filter_method={$filter_method}{/if}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                                {/if}
                                                            </td>                                                                                             
                                                    
                                                        </tr>
                                                        <tr>

                                                            <!-- Page Number Display -->
                                                            <td></td>
                                                            <td>
                                                                <p style="text-align: center;">{$display_payments.total_results} {t}records found.{/t}</p>
                                                            </td>
                                                            
                                                        </tr>                                                    
                                                    </table>                                                    
                                                </form>                                                
                                            </td>
                                            
                                        </tr>
                                        
                                        <!-- Results Block -->
                                        <tr>
                                            <td valign="top" colspan="2">
                                                {include file='payment/blocks/display_payments_block.tpl' display_payments=$display_payments block_title=''}
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