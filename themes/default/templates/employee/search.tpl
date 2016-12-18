<!-- search.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_employee_search}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<b>Employee Search</b><hr><p>You can search by the employees full display name or just their first name. If you wish to see all the employees for just one letter like A enter the letter a only.</p> <p>To find employees whos name starts with Ja enter just ja. The system will intelegently look for the corect employee that matches.</p>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                       
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td>
                                                {literal}
                                                <form method="get" action="?page=employee:search">
                                                {/literal}
                                                    <table border="0">
                                                        <tr>
                                                            <td colspan="2"><font color="red">{$translate_employee_display_name_criteria}</font></td>
                                                        </tr>                                                     
                                                        <tr>
                                                            <td align="right" valign="top"><b>{$translate_employee_display_name}</b></td>
                                                            <td valign="top" align="left"><input name="name" class="olotd4" type="text" maxlength="50" onkeypress="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top"><b></b></td>
                                                            <td valign="top" align="left"><input class="olotd4" name="submit" value="Search" type="submit" /></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </td>
                                            <td valign="top">
                                                <form id="1">
                                                    <a href="?page=employee%3Amain&name={$name}&submit=submit&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0"></a>&nbsp;
                                                    {if $previous != ''}<a href="?page=employee%3Amain&name={$name}&submit=submit&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0"></a>&nbsp;{/if}
                                                    <select name="page_no" onChange="changePage();">
                                                        {section name=page loop=$total_pages start=1}
                                                            <option value="?page=employee%3Amain&name={$name}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>{$translate_employee_page} {$smarty.section.page.index} {$translate_employee_of} {$total_pages}</option>
                                                        {/section}
                                                            <option value="?page=employee%3Amain&name={$name}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>{$translate_employee_page} {$total_pages} {$translate_employee_of} {$total_pages}</option>
                                                    </select>
                                                    {if $next != ''}<a href="?page=employee%3Amain&name={$name}&submit=submit&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0"></a>{/if}
                                                    <a href="?page=employee%3Amain&name={$name}&submit=submit&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0"></a>
                                                    <br>
                                                    {$total_results} {$translate_employee_records_found}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="2">
                                                {foreach  from=$alpha item=alpha}
                                                    &nbsp;<a href="?page=employee%3Amain&name={$alpha}&submit=submit">{$alpha}</a>&nbsp;
                                                {/foreach}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
                                                    <tr>
                                                        <td class="olohead">{$translate_employee_id}</td>
                                                        <td class="olohead">{$translate_employee_display}</td>
                                                        <td class="olohead">{$translate_employee_first}</td>
                                                        <td class="olohead">{$translate_employee_last}</td>
                                                        <td class="olohead">{$translate_employee_work_phone}</td>
                                                        <td class="olohead">{$translate_employee_type}</td>
                                                        <td class="olohead">{$translate_employee_email}</td>
                                                        <td class="olohead">{$translate_employee_action}</td>
                                                    </tr>
                                                    {section name=i loop=$employee_search_result}
                                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=employee:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}';" class="row1">
                                                            <td class="olotd4"><a href="?page=employee:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}">{$employee_search_result[i].EMPLOYEE_ID}</a></td>
                                                            <td class="olotd4">
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('{$employee_search_result[i].EMPLOYEE_ADDRESS}<br>{$employee_search_result[i].EMPLOYEE_CITY}, {$employee_search_result[i].EMPLOYEE_SATE}  {$employee_search_result[i].EMPLOYEE_ZIP}')" onMouseOut="hideddrivetip();">
                                                                {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}
                                                            </td>
                                                            <td class="olotd4">{$employee_search_result[i].EMPLOYEE_FIRST_NAME}</td>
                                                            <td class="olotd4">{$employee_search_result[i].EMPLOYEE_LAST_NAME}</td>
                                                            <td class="olotd4">
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b>{$translate_employee_home} </b>{$employee_search_result[i].EMPLOYEE_HOME_PHONE}<br><b>{$translate_employee_mobile} </b>{$employee_search_result[i].EMPLOYEE_MOBILE_PHONE}');" onMouseOut="hideddrivetip();">
                                                                {$employee_search_result[i].EMPLOYEE_WORK_PHONE}
                                                            </td>
                                                            <td class="olotd4">{$employee_search_result[i].TYPE_NAME}</td>
                                                            <td class="olotd4"><a href="mailto: {$employee_search_result[i].EMPLOYEE_EMAIL}"><font class="blueLink">{$employee_search_result[i].EMPLOYEE_EMAIL}</font></a></td>
                                                            <td class="olotd4"><a href="?page=employee:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('View Employees Details')" onMouseOut="hideddrivetip()"></a>&nbsp;<a href="?page=employee:edit&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_edit} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('Edit')" onMouseOut="hideddrivetip();"></a></td>                                                        
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