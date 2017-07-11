<!-- search.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}User Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<strong>{t escape=tooltip}USER_SEARCH_HELP_TITLE{/t}</strong><hr>{t escape=tooltip}USER_SEARCH_HELP_CONTENT{/t}');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                       
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            
                                            <!-- Search Box -->
                                            <td>                                                
                                                <form method="post" action="index.php?page=user:search">                                                
                                                    <table border="0">
                                                        <tr>
                                                            <td align="right" valign="top"><b>{t}Display Name{/t}</b></td>
                                                            <td valign="top" align="left"><input name="search_term" value="{$search_term}" class="olotd4" size="20" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top"></td>
                                                            <td valign="top" align="left">
                                                                <input class="olotd4" name="submit" value="submit" type="submit" />
                                                                <input class="olotd4" type="button" value="{t}Reset{/t}" onclick="window.location.href='index.php?page=user:search';">
                                                            </td>
                                                        </tr>
                                                    </table>
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
                                                <table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
                                                    <tr>
                                                        <td class="olohead">{t}ID{/t}</td>
                                                        <td class="olohead">{t}Display Name{/t}</td>
                                                        <td class="olohead">{t}First Name{/t}</td>
                                                        <td class="olohead">{t}Last Name{/t}</td>
                                                        <td class="olohead">{t}Work Phone{/t}</td>
                                                        <td class="olohead">{t}Type{/t}</td>
                                                        <td class="olohead">{t}Email{/t}</td>
                                                        <td class="olohead">{t}Action{/t}</td>
                                                    </tr>
                                                    {section name=i loop=$search_result}
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=user:details&user_id={$search_result[i].user_id}';" class="row1">
                                                            <td class="olotd4"><a href="index.php?page=user:details&user_id={$search_result[i].user_id}">{$search_result[i].user_id}</a></td>
                                                            <td class="olotd4">
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('{$search_result[i].address}<br>{$search_result[i].city}, {$search_result[i].state}  {$search_result[i].zip}');" onMouseOut="hideddrivetip();">
                                                                {$search_result[i].display_name}
                                                            </td>
                                                            <td class="olotd4">{$search_result[i].first_name}</td>
                                                            <td class="olotd4">{$search_result[i].last_name}</td>
                                                            <td class="olotd4">
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b>{t}Home{/t} </b>{$search_result[i].home_phone}<br><b>{t}Mobile{/t} </b>{$search_result[i].work_mobile_phone}');" onMouseOut="hideddrivetip();">
                                                                {$search_result[i].work_phone}
                                                            </td>
                                                            <td class="olotd4">{$search_result[i].usergroup}</td>
                                                            <td class="olotd4"><a href="mailto: {$search_result[i].email}"><font class="blueLink">{$search_result[i].email}</font></a></td>
                                                            <td class="olotd4"><a href="index.php?page=user:details&user_id={$search_result[i].user_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Users Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;<a href="index.php?page=user:edit&user_id={$search_result[i].user_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a></td>                                                        
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