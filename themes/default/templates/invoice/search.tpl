<!-- search.tpl -->
<div>{t}Coming Soon{/t}</div>
{*<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Expense Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EXPENSE_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EXPENSE_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?page=expense:search" name="expense_search" id="expense_search">
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Search{/t}</b>
                                                                   <br />
                                                                    <select class="olotd5" id="search_category" name="search_category">
                                                                        <option value="id"{if $search_category == 'id'} selected{/if}>{t}Expense ID{/t}</option>
                                                                        <option value="payee"{if $search_category == 'payee'} selected{/if}>{t}Payee{/t}</option>
                                                                        <option value="date"{if $search_category == 'date'} selected{/if}>{t}Date{/t}</option>
                                                                        <option value="type"{if $search_category == 'type'} selected{/if}>{t}Type{/t}</option>
                                                                        <option value="payment_method"{if $search_category == 'payment_method'} selected{/if}>{t}Payment Method{/t}</option>
                                                                        <option value="net_amount"{if $search_category == 'net_amount'} selected{/if}>{t}Net Amount{/t}</option>
                                                                        <option value="tax_rate"{if $search_category == 'tax_rate'} selected{/if}>{t}Tax Rate{/t}</option>
                                                                        <option value="tax"{if $search_category == 'tax'} selected{/if}>{t}Tax Amount{/t}</option>
                                                                        <option value="total"{if $search_category == 'total'} selected{/if}>{t}Gross Amount{/t}</option>
                                                                        <option value="notes"{if $search_category == 'notes'} selected{/if}>{t}Notes{/t}</option>
                                                                        <option value="items"{if $search_category == 'items'} selected{/if}>{t}Items{/t}</option>
                                                                    </select>
                                                                   <br />
                                                                   <b>{t}for{/t}</b>
                                                                   <br />
                                                                   <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumericAndDate(event);">
                                                                   <input name="submit" class="olotd4" value="{t}Search{/t}" type="submit" />
                                                                   <input class="olotd4" value="{t}reset{/t}" onclick="window.location.href='index.php?page=expense:search';" type="button">
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
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=expense:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
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
                                                                <p style="text-align: center;">{$total_results} {t}records found.{/t}</p>
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
                                                        <td class="olohead">{t}Expense ID{/t}</td>
                                                        <td class="olohead">{t}Payee{/t}</td>
                                                        <td class="olohead">{t}Date{/t}</td>
                                                        <td class="olohead">{t}Type{/t}</td>
                                                        <td class="olohead">{t}Payment Method{/t}</td>
                                                        <td class="olohead">{t}Net Amount{/t}</td>
                                                        <td class="olohead">{t}Tax Rate{/t}</td>
                                                        <td class="olohead">{t}Tax Amount{/t}</td>
                                                        <td class="olohead">{t}Gross Amount{/t}</td>
                                                        <td class="olohead">{t}Notes{/t}</td>
                                                        <td class="olohead">{t}Items{/t}</td>
                                                        <td class="olohead">{t}Action{/t}</td>
                                                    </tr>
                                                    {section name=i loop=$search_result}
                                                        <!-- This allows double clicking on a row and opens the corresponding expense view details -->
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=expense:details&expense_id={$search_result[i].EXPENSE_ID}';" class="row1">
                                                            <td class="olotd4" nowrap><a href="index.php?page=expense:details&expense_id={$search_result[i].EXPENSE_ID}">{$search_result[i].EXPENSE_ID}</a></td>
                                                            <td class="olotd4" nowrap>{$search_result[i].EXPENSE_PAYEE}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].EXPENSE_DATE|date_format:$date_format}</td>                                                                
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].EXPENSE_TYPE ==1}{t}EXPENSE_TYPE_1{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==2}{t}EXPENSE_TYPE_2{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==3}{t}EXPENSE_TYPE_3{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==4}{t}EXPENSE_TYPE_4{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==5}{t}EXPENSE_TYPE_5{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==6}{t}EXPENSE_TYPE_6{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==7}{t}EXPENSE_TYPE_7{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==8}{t}EXPENSE_TYPE_8{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==9}{t}EXPENSE_TYPE_9{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==10}{t}EXPENSE_TYPE_10{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==11}{t}EXPENSE_TYPE_11{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==12}{t}EXPENSE_TYPE_12{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==13}{t}EXPENSE_TYPE_13{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==14}{t}EXPENSE_TYPE_14{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==15}{t}EXPENSE_TYPE_15{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==16}{t}EXPENSE_TYPE_16{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==17}{t}EXPENSE_TYPE_17{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==18}{t}EXPENSE_TYPE_18{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==19}{t}EXPENSE_TYPE_19{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==20}{t}EXPENSE_TYPE_20{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==21}{t}EXPENSE_TYPE_21{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==22}{t}EXPENSE_TYPE_22{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==23}{t}EXPENSE_TYPE_23{/t}{/if}
                                                                {if $search_result[i].EXPENSE_TYPE ==24}{t}EXPENSE_TYPE_24{/t}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==1}{t}EXPENSE_PAYMENT_METHOD_1{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==2}{t}EXPENSE_PAYMENT_METHOD_2{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==3}{t}EXPENSE_PAYMENT_METHOD_3{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==4}{t}EXPENSE_PAYMENT_METHOD_4{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==5}{t}EXPENSE_PAYMENT_METHOD_5{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==6}{t}EXPENSE_PAYMENT_METHOD_6{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==7}{t}EXPENSE_PAYMENT_METHOD_7{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==8}{t}EXPENSE_PAYMENT_METHOD_8{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==9}{t}EXPENSE_PAYMENT_METHOD_9{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==10}{t}EXPENSE_PAYMENT_METHOD_10{/t}{/if}
                                                                {if $search_result[i].EXPENSE_PAYMENT_METHOD ==11}{t}EXPENSE_PAYMENT_METHOD_11{/t}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>{$currency_sym} {$search_result[i].EXPENSE_NET_AMOUNT}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].EXPENSE_TAX_RATE} %</td>
                                                            <td class="olotd4" nowrap>{$currency_sym} {$search_result[i].EXPENSE_TAX_AMOUNT}</td>
                                                            <td class="olotd4" nowrap>{$currency_sym} {$search_result[i].EXPENSE_GROSS_AMOUNT}</td>
                                                            <td class="olotd4" nowrap>
                                                                {if !$search_result[i].EXPENSE_NOTES == ''}
                                                                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Notes{/t}</strong></div><hr><div>{$search_result[i].notes}</div>');" onMouseOut="hideddrivetip();">
                                                                {/if}
                                                            </td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Items{/t}</strong></div><hr><div>{$search_result[i].items}</div>');" onMouseOut="hideddrivetip();"></td>
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=expense:details&expense_id={$search_result[i].EXPENSE_ID}">
                                                                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}View Expense Details{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=expense:edit&expense_id={$search_result[i].EXPENSE_ID}">
                                                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit Expense Details{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=expense:delete&expense_id={$search_result[i].EXPENSE_ID}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Expense Record? This will permanently remove the record from the database.{/t}');">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Expense Record{/t}</b>');" onMouseOut="hideddrivetip();">
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
</table>*}