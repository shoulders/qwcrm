<!-- closed.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_closed_title} - {$total_results} {$translate_workorder_records_found}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt=""  onMouseOver="ddrivetip('<b>{$translate_workorder_closed_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_closed_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
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
                                            <td valign="top"></td>
                                            <td valign="top" nowrap align="right">
                                                <form id="1" action="">
                                                    <a href="?page=workorder:closed&submit=submit&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" alt="" border="0"></a>&nbsp;
                                                    {if $previous != ''}
                                                        <a href="?page=workorder:closed&submit=submit&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" alt="" border="0"></a>&nbsp;
                                                    {/if}
                                                    <select id="changeThisPage" onChange="changePage();">
                                                        {section name=page loop=$total_pages start=1}
                                                            <option value="?page=workorder:closed&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                {$translate_workorder_page} {$smarty.section.page.index} {$translate_workorder_of} {$total_pages} 
                                                            </option>
                                                        {/section}
                                                        <option value="?page=workorder:closed&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                            {$translate_workorder_page} {$total_pages} {$translate_workorder_of} {$total_pages}
                                                        </option>
                                                    </select>
                                                    {if $next != ''}
                                                        <a href="?page=workorder:closed&submit=submit&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" alt="" border="0"></a>
                                                    {/if}
                                                    <a href="?page=workorder:closed&submit=submit&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" alt="" border="0"></a>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead"><b>{$translate_workorder_id}</b></td>
                                                        <td class="olohead"><b>{$translate_workorder_opened}</b></td>
                                                        <td class="olohead"><b>{$translate_workorder_closed}</b></td>
                                                        <td class="olohead"><b>{$translate_workorder_customer}</b></td>
                                                        <td class="olohead"><b>{$translate_workorder_scope}</b></td>
                                                        <td class="olohead"><b>{$translate_workorder_status}</b></td>
                                                        <td class="olohead"><b>{$translate_workorder_tech}</b></td>
                                                    </tr>
                                                    {foreach from=$single_workorder item=single_workorder}
                                                        {if $single_workorder.WORK_ORDER_ID  != ""}
                                                            <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:details&workorder_id={$single_workorder.WORK_ORDER_ID}&customer_id={$single_workorder.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$single_workorder.WORK_ORDER_ID}';" class="row1">
                                                                <td class="olotd4"><a href="?page=workorder:details&workorder_id={$single_workorder.WORK_ORDER_ID}&customer_id={$single_workorder.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$single_workorder.WORK_ORDER_ID}">{$single_workorder.WORK_ORDER_ID}</a></td>
                                                                <td class="olotd4"> {$single_workorder.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
                                                                <td class="olotd4">{$single_workorder.WORK_ORDER_CLOSE_DATE|date_format:"$date_format"}</td>
                                                                <td class="olotd4" nowrap>
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<b><center>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_phone}: </b>{$single_workorder.CUSTOMER_PHONE}<br> <b>{$translate_workorder_fax}: </b>{$single_workorder.CUSTOMER_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$single_workorder.CUSTOMER_MOBILE_PHONE}<br><b>{$translate_workorder_address}:</b><br>{$single_workorder.CUSTOMER_ADDRESS}<br>{$single_workorder.CUSTOMER_CITY}, {$single_workorder.CUSTOMER_STATE}<br>{$single_workorder.CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();">                                                                         
                                                                    <a class="link1" href="?page=customer:customer_details&customer_id={$single_workorder.CUSTOMER_ID}&page_title={$single_workorder.CUSTOMER_DISPLAY_NAME}">{$single_workorder.CUSTOMER_DISPLAY_NAME}</a>
                                                                </td>
                                                                <td class="olotd4" nowrap>{$single_workorder.WORK_ORDER_SCOPE}</td>
                                                                <td class="olotd4" align="center">
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '1'}{$translate_workorder_created}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '2'}{$translate_workorder_assigned}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '3'}{$translate_workorder_waiting_for_parts}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '6'}{$translate_workorder_closed}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '7'}{$translate_workorder_waiting_for_payment}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '8'}{$translate_workorder_payment_made}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '9'}{$translate_workorder_pending}{/if}
                                                                    {if $single_workorder.WORK_ORDER_STATUS == '10'}{$translate_workorder_open}{/if}
                                                                </td>  
                                                                <td class="olotd4" nowrap>
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_fax}: </b>{$single_workorder.EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$single_workorder.EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$single_workorder.EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                                                                         
                                                                    <a class="link1" href="?page=employee:employee_details&employee_id={$single_workorder.EMPLOYEE_ID}&page_title={$single_workorder.EMPLOYEE_DISPLAY_NAME}">{$single_workorder.EMPLOYEE_DISPLAY_NAME}</a>
                                                                </td>
                                                            </tr>
                                                        {else}
                                                            <tr>
                                                                <td colspan="6" class="error">{$translate_workorder_msg_there_are_no_closed_work_orders}</td>
                                                            </tr>
                                                        {/if}
                                                    {/foreach}
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