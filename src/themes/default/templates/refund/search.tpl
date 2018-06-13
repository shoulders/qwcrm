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
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Search Refunds{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REFUND_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REFUND_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?component=refund&page_tpl=search" name="refund_search" id="refund_search" autocomplete="off">
                                                    <div>                                                       
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Refund Search{/t}</b>
                                                                    <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">
                                                                        <option value="refund_id"{if $search_category == 'refund_id'} selected{/if}>{t}Refund ID{/t}</option>
                                                                        <option value="invoice_id"{if $search_category == 'invoice_id'} selected{/if}>{t}Invoice ID{/t}</option>
                                                                        <option value="payee"{if $search_category == 'payee'} selected{/if}>{t}Payee{/t}</option>
                                                                        <option value="date"{if $search_category == 'date'} selected{/if}>{t}Date{/t}</option>
                                                                        <option value="type"{if $search_category == 'type'} selected{/if}>{t}Type{/t}</option>
                                                                        <option value="payment_method"{if $search_category == 'payment_method'} selected{/if}>{t}Payment Method{/t}</option>
                                                                        <option value="net_amount"{if $search_category == 'net_amount'} selected{/if}>{t}Net Amount{/t}</option>
                                                                        <option value="vat_rate"{if $search_category == 'vat_rate'} selected{/if}>{t}VAT{/t} {t}Rate{/t}</option>
                                                                        <option value="vat_amount"{if $search_category == 'vat_amount'} selected{/if}>{t}VAT{/t} {t}Amount{/t}</option>
                                                                        <option value="gross_amount"{if $search_category == 'gross_amount'} selected{/if}>{t}Gross{/t} ({t}Total{/t})</option>                                                                      
                                                                        <option value="items"{if $search_category == 'items'} selected{/if}>{t}Items{/t}</option>
                                                                        <option value="note"{if $search_category == 'note'} selected{/if}>{t}Note{/t}</option>
                                                                    </select>
                                                                   <br />
                                                                   <b>{t}for{/t}</b>
                                                                   <br />
                                                                   <input class="olotd4" name="search_term" value="{$search_term}" type="text" maxlength="20" onkeydown="return onlySearch(event);" />
                                                                   <button type="submit" name="submit" value="search">{t}Search{/t}</button>
                                                                   <button type="button" class="olotd4" onclick="window.location.href='index.php?component=refund&page_tpl=search';">{t}Reset{/t}</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Type{/t}</b><br>
                                                                    <select class="olotd5" id="filter_type" name="filter_type">
                                                                        <option value=""{if $filter_type == ''} selected{/if}>{t}None{/t}</option>
                                                                        <option disabled>----------</option>                                                                        
                                                                        {section name=t loop=$refund_types}    
                                                                            <option value="{$refund_types[t].refund_type_id}"{if $filter_type == $refund_types[t].refund_type_id} selected{/if}>{t}{$refund_types[t].display_name}{/t}</option>        
                                                                        {/section}
                                                                    </select>
                                                                </td>
                                                            </tr> 
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Method{/t}</b><br>
                                                                    <select class="olotd5" id="filter_payment_method" name="filter_payment_method">
                                                                        <option value=""{if $filter_payment_method == ''} selected{/if}>{t}None{/t}</option>
                                                                        <option disabled>----------</option>                                                                        
                                                                        {section name=t loop=$payment_methods}    
                                                                            <option value="{$payment_methods[t].manual_method_id}"{if $filter_method == $payment_methods[t].manual_method_id} selected{/if}>{t}{$payment_methods[t].display_name}{/t}</option>        
                                                                        {/section}
                                                                    </select>
                                                                </td>
                                                            </tr>                                                         
                                                        </table>
                                                    </div>
                                                </form>
                                            </td>                                                              
                                                                            
                                            <!-- Navigation -->
                                            <td valign="top" nowrap align="right">
                                                <form id="navigation">                                                    
                                                    <table>
                                                        <tr>
                                                            
                                                            <!-- Left Buttons -->
                                                            <td>    
                                                                {if $previous_page_no}
                                                                    <a href="index.php?component=refund&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                    <a href="index.php?component=refund&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$previous_page_no}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                                {/if}
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?component=refund&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?component=refund&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                {if $next_page_no}
                                                                    <a href="index.php?component=refund&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$next_page_no}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                    <a href="index.php?component=refund&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                                {/if}
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
                                        
                                        <!-- Records Table -->
                                        <tr>                                            
                                            <td valign="top" colspan="2">                                                
                                                {include file='refund/blocks/display_refunds_block.tpl' display_giftcerts=$display_refunds block_title=''}
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