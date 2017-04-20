<!-- search.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{$translate_expense_search_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_expense_search_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_expense_search_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();"></a>                            
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
                                                <form method="post" action="index.php?page=expense:search" name="expense_search" id="expense_search">
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{$translate_expense_search}</b>
                                                                   <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">
                                                                        <option value="id"{if $search_category == 'id'} selected{/if}>{$translate_expense_id}</option>
                                                                        <option value="payee"{if $search_category == 'payee'} selected{/if}>{$translate_expense_payee}</option>
                                                                        <option value="date"{if $search_category == 'date'} selected{/if}>{$translate_expense_date}</option>
                                                                        <option value="type"{if $search_category == 'type'} selected{/if}>{$translate_expense_type}</option>
                                                                        <option value="payment_method"{if $search_category == 'payment_method'} selected{/if}>{$translate_expense_payment_method}</option>
                                                                        <option value="net_amount"{if $search_category == 'net_amount'} selected{/if}>{$translate_expense_net_amount}</option>
                                                                        <option value="tax_rate"{if $search_category == 'tax_rate'} selected{/if}>{$translate_expense_tax_rate}</option>
                                                                        <option value="tax"{if $search_category == 'tax'} selected{/if}>{$translate_expense_tax_amount}</option>
                                                                        <option value="total"{if $search_category == 'total'} selected{/if}>{$translate_expense_gross_amount}</option>
                                                                        <option value="notes"{if $search_category == 'notes'} selected{/if}>{$translate_expense_notes}</option>
                                                                        <option value="items"{if $search_category == 'items'} selected{/if}>{$translate_expense_items}</option>
                                                                    </select>
                                                                   <br />
                                                                   <b>{$translate_expense_for}</b>
                                                                   <br />
                                                                   <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumericAndDate(event);">
                                                                   <input name="submit" class="olotd4" value="{$translate_expense_search_button}" type="submit" />
                                                                   <input class="olotd4" value="{$translate_expense_reset_button}" onclick="window.location.href='index.php?page=expense:search';" type="button">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><font color="red">{$translate_expense_search_criteria_warning}</font></td>
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
                                                                <a href="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {$translate_workorder_page} {$smarty.section.page.index} {$translate_workorder_of} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {$translate_workorder_page} {$total_pages} {$translate_workorder_of} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
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
                                            <!-- Records Table -->
                                            <td valign="top" colspan="2">                                                
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">{$translate_expense_id}</td>
                                                        <td class="olohead">{$translate_expense_payee}</td>
                                                        <td class="olohead">{$translate_expense_date}</td>
                                                        <td class="olohead">{$translate_expense_type}</td>
                                                        <td class="olohead">{$translate_expense_payment_method}</td>
                                                        <td class="olohead">{$translate_expense_net_amount}</td>
                                                        <td class="olohead">{$translate_expense_tax_rate}</td>
                                                        <td class="olohead">{$translate_expense_tax_amount}</td>
                                                        <td class="olohead">{$translate_expense_gross_amount}</td>
                                                        <td class="olohead">{$translate_expense_notes}</td>
                                                        <td class="olohead">{$translate_expense_items}</td>
                                                        <td class="olohead">{$translate_action}</td>
                                                    </tr>
                                                    {section name=i loop=$search_result}
                                                        <!-- This allows double clicking on a row and opens the corresponding expense view details -->
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=expense:details&expense_id={$search_result[i].EXPENSE_ID}&page_title={$translate_expense_details_title}';" class="row1">
                                                            <td class="olotd4" nowrap><a href="index.php?page=expense:details&expense_id={$search_result[i].EXPENSE_ID}&page_title={$translate_expense_details_title}">{$search_result[i].EXPENSE_ID}</a></td>
                                                            <td class="olotd4" nowrap>{$search_result[i].EXPENSE_PAYEE}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].EXPENSE_DATE|date_format:$date_format}</td>                                                                
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].EXPENSE_TYPE ==1}{$translate_expense_type_1}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==2}{$translate_expense_type_2}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==3}{$translate_expense_type_3}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==4}{$translate_expense_type_4}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==5}{$translate_expense_type_5}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==6}{$translate_expense_type_6}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==7}{$translate_expense_type_7}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==8}{$translate_expense_type_8}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==9}{$translate_expense_type_9}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==10}{$translate_expense_type_10}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==11}{$translate_expense_type_11}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==12}{$translate_expense_type_12}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==13}{$translate_expense_type_13}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==14}{$translate_expense_type_14}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==15}{$translate_expense_type_15}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==16}{$translate_expense_type_16}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==17}{$translate_expense_type_17}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==18}{$translate_expense_type_18}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==19}{$translate_expense_type_19}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==20}{$translate_expense_type_20}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==21}{$translate_expense_type_21}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==1}{$translate_expense_payment_method_1}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==2}{$translate_expense_payment_method_2}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==3}{$translate_expense_payment_method_3}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==4}{$translate_expense_payment_method_4}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==5}{$translate_expense_payment_method_5}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==6}{$translate_expense_payment_method_6}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==7}{$translate_expense_payment_method_7}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==8}{$translate_expense_payment_method_8}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==9}{$translate_expense_payment_method_9}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==10}{$translate_expense_payment_method_10}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==11}{$translate_expense_payment_method_11}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>{$currency_sym} {$search_result[i].EXPENSE_NET_AMOUNT}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].EXPENSE_TAX_RATE} %</td>
                                                            <td class="olotd4" nowrap>{$currency_sym} {$search_result[i].EXPENSE_TAX_AMOUNT}</td>
                                                            <td class="olotd4" nowrap>{$currency_sym} {$search_result[i].EXPENSE_GROSS_AMOUNT}</td>
                                                            <td class="olotd4" nowrap>
                                                                {if !$search_result[i].EXPENSE_NOTES == ''}
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_expense_notes}</b><hr><p>{$search_result[i].EXPENSE_NOTES|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                                                                {/if}
                                                            </td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_expense_items}</b><hr><p>{$search_result[i].EXPENSE_ITEMS|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();"></td>
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=expense:details&expense_id={$search_result[i].EXPENSE_ID}&page_title={$translate_expense_details_title}">
                                                                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_expense_search_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="?page=expense:edit&expense_id={$search_result[i].EXPENSE_ID}&page_title={$translate_expense_edit_title}">
                                                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{$translate_expense_search_edit_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="?page=expense:delete&expense_id={$search_result[i].EXPENSE_ID}" onclick="return confirmDelete('{$translate_expense_delete_mes_confirmation}');">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{$translate_expense_search_delete_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
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