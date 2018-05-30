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
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Schedule Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?component=schedule&page_tpl=search" name="schedule_search" id="schedule_search">
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Search{/t}</b>
                                                                   <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">
                                                                        <option value="schedule_id"{if $search_category == 'schedule_id'} selected{/if}>{t}Schedule ID{/t}</option>
                                                                        <option value="workorder_id"{if $search_category == 'workorder_id'} selected{/if}>{t}Work Order ID{/t}</option>
                                                                        <option value="employee_display_name"{if $search_category == 'employee_display_name'} selected{/if}>{t}Employee{/t}</option>
                                                                        <option value="customer_display_name"{if $search_category == 'customer_display_name'} selected{/if}>{t}Customer{/t}</option>                                                                        
                                                                        <option value="start_time"{if $search_category == 'start_time'} selected{/if}>{t}Start Time{/t}</option>
                                                                        <option value="end_time"{if $search_category == 'end_time'} selected{/if}>{t}End Time{/t}</option>
                                                                        <option value="notes"{if $search_category == 'notes'} selected{/if}>{t}Notes{/t}</option>
                                                                    </select>
                                                                   <br />
                                                                   <b>{t}for{/t}</b>
                                                                   <br />
                                                                   <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" onkeydown="return onlySearch(event);">
                                                                   <input name="submit" class="olotd4" value="{t}Search{/t}" type="submit" />
                                                                   <input class="olotd4" value="{t}Reset{/t}" onclick="window.location.href='index.php?component=schedule&page_tpl=search';" type="button">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
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
                                                            
                                                            <!-- Left Buttons -->                                                            
                                                            <td>  
                                                                {if $previous_page_no} 
                                                                    <a href="index.php?component=schedule&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                    <a href="index.php?component=schedule&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$previous_page_no}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                                {/if}
                                                            </td>
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?component=schedule&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}{if $filter_status}&filter_status={$filter_status}{/if}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?component=schedule&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}{if $filter_status}&filter_status={$filter_status}{/if}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                {if $next_page_no}
                                                                    <a href="index.php?component=schedule&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$next_page_no}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                    <a href="index.php?component=schedule&page_tpl=search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                        
                                        <!-- Results Block -->
                                        <tr>
                                            <td valign="top" colspan="2">
                                                {include file='schedule/blocks/display_schedules_block.tpl' display_scheduless=$display_schedules block_title=''}
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