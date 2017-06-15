<!-- closed.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Closed Work Orders{/t} - {$total_results} {t}records found.{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_CLOSED_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_CLOSED_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                            
                                            <!-- Navigation -->
                                            <td valign="top" nowrap align="right">
                                                <form id="navigation">                                                    
                                                    <table>
                                                        <tr>
                                                            
                                                            <!-- Left buttons -->
                                                            <td>                                                                
                                                                <a href="index.php?page=workorder:closed&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=workorder:closed&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=workorder:closed&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=workorder:closed&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=workorder:closed&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=workorder:closed&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                                <table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead"><b>{t}Workorder ID{/t}</b></td>
                                                        <td class="olohead"><b>{t}Opened{/t}</b></td>
                                                        <td class="olohead"><b>{t}Closed{/t}</b></td>
                                                        <td class="olohead"><b>{t}Customer{/t}</b></td>
                                                        <td class="olohead"><b>{t}Scope{/t}</b></td>
                                                        <td class="olohead"><b>{t}Status{/t}</b></td>
                                                        <td class="olohead"><b>{t}Technician{/t}</b></td>
                                                    </tr>
                                                    {section name=i loop=$workorders}                                                    
                                                        {if $workorders[i].WORK_ORDER_ID != ''}
                                                            <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$workorders[i].WORK_ORDER_ID}&customer_id={$workorders[i].CUSTOMER_ID}';" class="row1">
                                                                <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$workorders[i].WORK_ORDER_ID}&customer_id={$workorders[i].CUSTOMER_ID}">{$workorders[i].WORK_ORDER_ID}</a></td>
                                                                <td class="olotd4"> {$workorders[i].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
                                                                <td class="olotd4">{$workorders[i].WORK_ORDER_CLOSE_DATE|date_format:$date_format}</td>
                                                                <td class="olotd4" nowrap>
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$workorders[i].CUSTOMER_PHONE}<br> <b>{t}Fax{/t}: </b>{$workorders[i].CUSTOMER_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$workorders[i].CUSTOMER_MOBILE_PHONE}<br><b>{t}Address{/t}: </b><br>{$workorders[i].CUSTOMER_ADDRESS}<br>{$workorders[i].CUSTOMER_CITY}, {$workorders[i].CUSTOMER_STATE}<br>{$workorders[i].CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();">                                                                         
                                                                    <a class="link1" href="index.php?page=customer:details&customer_id={$workorders[i].CUSTOMER_ID}">{$workorders[i].CUSTOMER_DISPLAY_NAME}</a>
                                                                </td>
                                                                <td class="olotd4" nowrap>{$workorders[i].WORK_ORDER_SCOPE}</td>
                                                                <td class="olotd4" align="center">
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
                                                                </td>  
                                                                <td class="olotd4" nowrap>
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact Info{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$workorders[i].EMPLOYEE_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$workorders[i].EMPLOYEE_MOBILE_PHONE}<br><b>{t}Home{/t}: </b>{$workorders[i].EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                                                                         
                                                                    <a class="link1" href="index.php?page=employee:details&employee_id={$workorders[i].EMPLOYEE_ID}">{$workorders[i].EMPLOYEE_DISPLAY_NAME}</a>
                                                                </td>
                                                            </tr>
                                                        {else}
                                                            <tr>
                                                                <td colspan="6" class="error">{t}There are no closed work orders.{/t}</td>
                                                            </tr>
                                                        {/if}
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