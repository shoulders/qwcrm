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
            <table width="750" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}User Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}USER_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}USER_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?page=giftcert:search" method="post" name="user_search" id="user_search">
                                                    <div>
                                                        <table border="0">
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Gift Certificate Code{/t}</b><br>
                                                                    <input name="search_term" value="{$search_term}" class="olotd4" size="20" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);">                                                            
                                                                    <input name="search_category" value="{$search_category}" type="hidden" />
                                                                    <input name="submit" class="olotd4" value="{t}Search{/t}" type="submit" />
                                                                    <input type="button" class="olotd4" value="{t}Reset{/t}" onclick="window.location.href='index.php?page=giftcert:search';">
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
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Redemption Status{/t}</b><br>
                                                                    <select class="olotd5" id="status" name="is_redeemed">
                                                                        <option value=""{if $is_redeemed == ''} selected{/if}>{t}None{/t}</option>                                                                        
                                                                        <option value="1"{if $is_redeemed == '1'} selected{/if}>{t}Redeemed{/t}</option>
                                                                        <option value="0"{if $is_redeemed == '0'} selected{/if}>{t}Not Redeemed{/t}</option>
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
                                                                <a href="index.php?page=giftcert:search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=giftcert:search&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=giftcert:search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=giftcert:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=giftcert:search&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=giftcert:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                        
                                        <!-- Search Results Table -->
                                        <tr>
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
                                                    <tr>
                                                        <td class="olohead">{t}ID{/t}</td>
                                                        <td class="olohead">{t}Code{/t}</td>
                                                        <td class="olohead">{t}Customer{/t}</td>
                                                        <td class="olohead">{t}Expires{/t}</td>
                                                        <td class="olohead">{t}Date Redeemed{/t}</td>
                                                        <td class="olohead">{t}Status{/t}</td>
                                                        <td class="olohead">{t}Amount{/t}</td>
                                                        <td class="olohead">{t}Notes{/t}</td> 
                                                        <td class="olohead">{t}Action{/t}</td>
                                                    </tr>
                                                    {section name=i loop=$search_result}
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=giftcert:details&giftcert_id={$search_result[i].user_id}';" class="row1">
                                                            <td class="olotd4"><a href="index.php?page=giftcert:details&giftcert_id={$search_result[i].giftcert_id}">{$search_result[i].giftcert_id}</a></td>
                                                            <td class="olotd4">{$search_result[i].giftcert_code}</td>
                                                            <td class="olotd4"><a href="index.php?page=customer:details&customer_id={$search_result[i].customer_id}">{$search_result[i].customer_display_name}</a></td>
                                                            <td class="olotd4">{$search_result[i].date_expires|date_format:$date_format}</td>
                                                            <td class="olotd4">
                                                                {if !$search_result[i].date_redeemed == ''}
                                                                    {$search_result[i].date_redeemed|date_format:$date_format}
                                                                {/if}
                                                            </td>
                                                            <td class="olotd4">
                                                                {if $search_result[i].active == '1'}{t}Active{/t}{/if}
                                                                {if $search_result[i].active == '0'}{t}Blocked{/t}{/if}
                                                            </td> 
                                                            <td class="olotd4">{$currency_sym} {$search_result[i].amount}</td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].notes != ''}
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Notes{/t}</strong></div><hr><div>{$search_result[i].notes}</div>');" onMouseOut="hideddrivetip();">
                                                                {/if}
                                                            </td>
                                                            <td class="olotd4">
                                                                <a href="index.php?page=giftcert:details&giftcert_id={$search_result[i].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                                                                <a href="index.php?page=giftcert:edit&giftcert_id={$search_result[i].giftcert_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                                                                <a href="index.php?page=giftcert:print&giftcert_id={$search_result[i].giftcert_id}&print_content=gift_certificate&print_type=print_html&theme=print">
                                                                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Print the Gift Certificate{/t}');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=giftcert:delete&giftcert_id={$search_result[i].giftcert_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Gift Certificate?{/t}');">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Gift Certificate{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
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