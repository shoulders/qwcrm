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
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Customer Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>                            
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CUSTOMER_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CUSTOMER_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?page=customer:search" method="post" name="customer_search" id="customer_search">                                                
                                                    <div>                                                        
                                                        <table border="0">
                                                           <tr>
                                                                <td>
                                                                    <b>{t}Display Name{/t}</b><br>
                                                                    <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);">
                                                                    <input name="search_category" value="{$search_category}" type="hidden" />
                                                                    <input name="submit" class="olotd4" value="{t}Search{/t}" type="submit" />
                                                                    <input type="button" class="olotd4" value="{t}Reset{/t}" onclick="window.location.href='index.php?page=customer:search';">
                                                                </td>
                                                            </tr> 
                                                            <tr>
                                                                <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Status{/t}</b><br>
                                                                    <select class="olotd5" id="status" name="status">
                                                                        <option value=""{if $status == ''} selected{/if}>{t}None{/t}</option>                                                                        
                                                                        <option value="1"{if $status == '1'} selected{/if}>{t}Active{/t}</option>
                                                                        <option value="0"{if $status == '0'} selected{/if}>{t}Blocked{/t}</option>
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
                                                            
                                                            <!-- Left buttons -->
                                                            <td>                                                                
                                                                <a href="index.php?page=customer:search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=customer:search&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=customer:search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=customer:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=customer:search&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=customer:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                                        <td class="olohead">ID</td>
                                                        <td class="olohead">{t}Display Name{/t}</td>
                                                        <td class="olohead">{t}First Name{/t}</td>
                                                        <td class="olohead">{t}Last Name{/t}</td>
                                                        <td class="olohead">{t}Phone{/t}</td>
                                                        <td class="olohead">{t}Type{/t}</td>
                                                        <td class="olohead">{t}Email{/t}</td>
                                                        <td class="olohead">{t}Action{/t}</td>
                                                    </tr>
                                                    {section name=i loop=$search_result}
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=customer:details&customer_id={$search_result[i].customer_id}';" class="row1">
                                                            <td class="olotd4" nowrap><a href="index.php?page=customer:details&customer_id={$search_result[i].customer_id}">{$search_result[i].customer_id}</a></td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('{$search_result[i].address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$search_result[i].city}<br>{$search_result[i].state}<br>{$search_result[i].zip}<br>{$search_result[i].country}');" onMouseOut="hideddrivetip();">&nbsp;{$search_result[i].display_name}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].first_name}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].last_name}</td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{t}Mobile{/t}: </b>{$search_result[i].mobile_phone}<br><b>{t}Fax{/t}:</b>{$search_result[i].fax}');" onMouseOut="hideddrivetip();">{$search_result[i].primary_phone}</td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].type ==1}{t}CUSTOMER_TYPE_1{/t}{/if}
                                                                {if $search_result[i].type ==2}{t}CUSTOMER_TYPE_2{/t}{/if}
                                                                {if $search_result[i].type ==3}{t}CUSTOMER_TYPE_3{/t}{/if}
                                                                {if $search_result[i].type ==4}{t}CUSTOMER_TYPE_4{/t}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap><a href="mailto:{$search_result[i].email}"><font class="blueLink">{$search_result[i].email}</font></a></td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=customer:details&customer_id={$search_result[i].customer_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}View Customer Details{/t}');" onMouseOut="hideddrivetip()"></a>&nbsp;
                                                                <a href="index.php?page=workorder:new&customer_id={$search_result[i].customer_id}"><img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Work Order{/t}');" onMouseOut="hideddrivetip();" alt=""></a>&nbsp;
                                                                <a href="index.php?page=invoice:edit&invoice_type=invoice-only&workorder_id=0&customer_id={$search_result[i].customer_id}"><img src="{$theme_images_dir}icons/16x16/small_new_invoice_only.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Invoice Only{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
                                                                <a href="index.php?page=user:new&customer_id={$search_result[i].customer_id}"><img src="{$theme_images_dir}icons/16x16/small_new_customer.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Customer Login{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
                                                            </td>
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
        </td>
    </tr>
</table>