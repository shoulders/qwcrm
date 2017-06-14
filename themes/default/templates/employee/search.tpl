<!-- search.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Employee Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EMPLOYEE_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EMPLOYEE_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?page=employee:search">                                                
                                                    <table border="0">
                                                        <tr>
                                                            <td colspan="2"><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
                                                        </tr>                                                     
                                                        <tr>
                                                            <td align="right" valign="top"><b>{t}Display Name{/t}</b></td>
                                                            <td valign="top" align="left"><input name="search_term" value="{$search_term}" class="olotd4" size="20" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top"></td>
                                                            <td valign="top" align="left">
                                                                <input class="olotd4" name="submit" value="submit" type="submit" />
                                                                <input class="olotd4" type="button" value="{t}Reset{/t}" onclick="window.location.href='index.php?page=employee:search';">
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
                                                                <a href="index.php?page=employee:search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=employee:search&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=employee:search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=employee:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=employee:search&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=employee:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=employee:details&employee_id={$search_result[i].EMPLOYEE_ID}';" class="row1">
                                                            <td class="olotd4"><a href="index.php?page=employee:details&employee_id={$search_result[i].EMPLOYEE_ID}">{$search_result[i].EMPLOYEE_ID}</a></td>
                                                            <td class="olotd4">
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('{$search_result[i].EMPLOYEE_ADDRESS}<br>{$search_result[i].EMPLOYEE_CITY}, {$search_result[i].EMPLOYEE_SATE}  {$search_result[i].EMPLOYEE_ZIP}');" onMouseOut="hideddrivetip();">
                                                                {$search_result[i].EMPLOYEE_DISPLAY_NAME}
                                                            </td>
                                                            <td class="olotd4">{$search_result[i].EMPLOYEE_FIRST_NAME}</td>
                                                            <td class="olotd4">{$search_result[i].EMPLOYEE_LAST_NAME}</td>
                                                            <td class="olotd4">
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b>{t}Home{/t} </b>{$search_result[i].EMPLOYEE_HOME_PHONE}<br><b>{t}Mobile{/t} </b>{$search_result[i].EMPLOYEE_MOBILE_PHONE}');" onMouseOut="hideddrivetip();">
                                                                {$search_result[i].EMPLOYEE_WORK_PHONE}
                                                            </td>
                                                            <td class="olotd4">{$search_result[i].TYPE_NAME}</td>
                                                            <td class="olotd4"><a href="mailto: {$search_result[i].EMPLOYEE_EMAIL}"><font class="blueLink">{$search_result[i].EMPLOYEE_EMAIL}</font></a></td>
                                                            <td class="olotd4"><a href="index.php?page=employee:details&employee_id={$search_result[i].EMPLOYEE_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View Employees Details{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;<a href="index.php?page=employee:edit&employee_id={$search_result[i].EMPLOYEE_ID}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a></td>                                                        
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