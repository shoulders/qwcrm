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
                                                <form action="index.php?page=user:search" method="post" name="user_search" id="user_search">
                                                    <div>
                                                        <table border="0">
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Display Name{/t}</b><br>
                                                                    <input name="search_term" value="{$search_term}" class="olotd4" size="20" type="text" maxlength="20" onkeydown="return onlySearch(event);">                                                            
                                                                    <input name="search_category" value="{$search_category}" type="hidden" />
                                                                    <input name="submit" class="olotd4" value="{t}Search{/t}" type="submit" />
                                                                    <input type="button" class="olotd4" value="{t}Reset{/t}" onclick="window.location.href='index.php?page=user:search';">
                                                                </td>
                                                            </tr>
                                                            <tr>                                                                
                                                                <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By User Type{/t}</b><br>
                                                                    <select class="olotd5" id="search_type" name="search_type">
                                                                        <option value=""{if $search_type == ''} selected{/if}>{t}None{/t}</option>                                                                        
                                                                        <option value="employees"{if $search_type == 'employees'} selected{/if}>{t}Employees{/t}</option>
                                                                        <option value="customers"{if $search_type == 'customers'} selected{/if}>{t}Customers{/t}</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <b>{t}Filter By Active Status{/t}</b><br>
                                                                    <select class="olotd5" id="search_active" name="search_active">
                                                                        <option value=""{if $search_active == ''} selected{/if}>{t}None{/t}</option> 
                                                                        <option value="0"{if $search_active == '0'} selected{/if}>{t}Blocked{/t}</option>
                                                                        <option value="1"{if $search_active == '1'} selected{/if}>{t}Active{/t}</option>
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
                                                                <a href="index.php?page=user:search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=user:search&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=user:search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=user:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=user:search&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=user:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                                {include file='user/blocks/display_users_block.tpl' display_users=$display_users block_title=''}
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