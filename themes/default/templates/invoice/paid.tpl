<!-- paid.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_invoice_paid} - {$total_results} {$translate_invoice_records}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"><img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0"></td>
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
                                                                <a href="index.php?page=invoice:paid&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=invoice:paid&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=invoice:paid&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {$translate_workorder_page} {$smarty.section.page.index} {$translate_workorder_of} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=invoice:paid&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {$translate_workorder_page} {$total_pages} {$translate_workorder_of} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=invoice:paid&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=invoice:paid&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                            </td>                                                                                             
                                                    
                                                        </tr>
                                                        <tr>

                                                            <!-- Page Number Display -->
                                                            <td></td>
                                                            <td>
                                                                <p style="text-align: center;">{$total_results} {$translate_records_found}.</p>
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
                                                        <td class="olohead">{$translate_invoice_id}</td>
                                                        <td class="olohead">{$translate_invoice_date}</td>
                                                        <td class="olohead">{$translate_invoice_due}</td>
                                                        <td class="olohead">{$translate_invoice_customer}</td>
                                                        <td class="olohead">{$translate_invoice_work_order}</td>
                                                        <td class="olohead">{$translate_invoice_employee}</td>
                                                        <td class="olohead">{$translate_invoice_sub_total}</td>
                                                        <td class="olohead">{$translate_invoice_discount}</td>                                                        
                                                        <td class="olohead">{$translate_invoice_tax}</td>                                                
                                                        <td class="olohead">{$translate_invoice_amount}</td>
                                                    </tr>
                                                    {section name=q loop=$invoice}                                            
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=invoice:details&invoice_id={$invoice[q].INVOICE_ID}';" class="row1">
                                                            <td class="olotd4" nowrap><a href="?page=invoice:details&invoice_id={$invoice[q].INVOICE_ID}">{$invoice[q].INVOICE_ID}</a></td>
                                                            <td class="olotd4" nowrap>{$invoice[q].DATE|date_format:$date_format}</td>
                                                            <td class="olotd4" nowrap>{$invoice[q].DUE_DATE|date_format:$date_format}</td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_invoice_phone} </b>{$invoice[q].CUSTOMER_PHONE}<br><b>Work: </b>{$invoice[q].CUSTOMER_WORK_PHONE}<br><b>Moile: </b>{$invoice[q].CUSTOMER_MOBILE_PHONE}<br><br>{$invoice[q].CUSTOMER_ADDRESS}<br>{$invoice[q].CUSTOMER_CITY}, {$invoice[q].CUSTOMER_STATE}<br>{$invoice[q].CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();"><a href="?page=customer:customer_details&customer_id={$invoice[q].CUSTOMER_ID}&page_title={$invoice[q].CUSTOMER_DISPLAY_NAME}">{$invoice[q].CUSTOMER_DISPLAY_NAME}</a></td>
                                                            <td class="olotd4" nowrap><a href="?page=workorder:details&workorder_id={$invoice[q].WORKORDER_ID}&workorder_id={$workorder_id}&page_title={$translate_invoice_workorder_id}{$invoice[q].WORKORDER_ID}">{$invoice[q].WORKORDER_ID}</a></td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<b>Work: </b>{$invoice[q].EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$invoice[q].EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$invoice[q].EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();"><a href="?page=employee:details&employee_id={$invoice[q].EMPLOYEE_ID}&page_title={$invoice[q].EMPLOYEE_DISPLAY_NAME}">{$invoice[q].EMPLOYEE_DISPLAY_NAME}</a></td>
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].SUB_TOTAL}</td>
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].DISCOUNT}</td>                                                            
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].TAX}</td>
                                                            <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].TOTAL}</td>
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

