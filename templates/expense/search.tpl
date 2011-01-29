<!-- Expense View and Search TPL -->

{include file="expense/javascripts.js"}

<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
                                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{$translate_expense_search_title}</td>
                                    <td class="menuhead2" width="20%" align="right" valign="middle">
                                        <a><img src="images/icons/16x16/help.gif" border="0" alt=""
                                            onMouseOver="ddrivetip('<b>{$translate_expense_search_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_expense_search_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                            onMouseOut="hideddrivetip()"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="menutd2" colspan="2">
                                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                            <td class="menutd">

                                                                    <!-- Content -->

                                                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                                        <tr>

                                                                        <!-- Category Search -->

                                                                            <td valign="top">
                                                                                    <form action="index.php?page=expense:search&page_title={$translate_expense_search_title}" method="post" name="expense_search" id="expense_search" autocomplete="off" >
                                                                                    <div>
                                                                                    <input name="page" type="hidden" value="expense:search" />
                                                                                    <table border="0">
                                                                                        <tr>
                                                                                            <td align="left" valign="top"><b>{$translate_expense_search}</b>
                                                                                               <br />
                                                                                                <select class="olotd5" id="expense_search_category" name="expense_search_category">
                                                                                                    <option value="ID">{$translate_expense_id}</option>
                                                                                                    <option value="PAYEE">{$translate_expense_payee}</option>
                                                                                                    <option value="DATE">{$translate_expense_date}</option>
                                                                                                    <option value="TYPE">{$translate_expense_type}</option>
                                                                                                    <option value="PAYMENT_METHOD">{$translate_expense_payment_method}</option>
                                                                                                    <option value="NET_AMOUNT">{$translate_expense_net_amount}</option>
                                                                                                    <option value="TAX_RATE">{$translate_expense_tax_rate}</option>
                                                                                                    <option value="TAX_AMOUNT">{$translate_expense_tax_amount}</option>
                                                                                                    <option value="GROSS_AMOUNT">{$translate_expense_gross_amount}</option>
                                                                                                    <option value="NOTES">{$translate_expense_notes}</option>
                                                                                                    <option value="ITEMS">{$translate_expense_items}</option>
                                                                                                </select>
                                                                                               <br />
                                                                                               <b>{$translate_expense_for}</b>
                                                                                               <br />
                                                                                               <input class="olotd4" name="expense_search_term" type="text" value="{$expense_search_term}" onkeypress="return OnlyAlphaNumeric();" />
                                                                                               <input class="olotd4" name="submit" value="{$translate_expense_search_button}" type="submit" />
                                                                                               <input class="olotd4" type="button" value="{$translate_expense_reset_button}" onclick="window.location.href='index.php?page=expense%3Asearch&page_title={$translate_expense_search_title}'">                                                                                       </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td><font color="RED">{$translate_expense_search_criteria_warning}</font></td>
                                                                                            </tr>
                                                                                    </table>
                                                                                    </div>
                                                                                    </form>

                                                                                    <!-- This script sets the dropdown to the correct item -->
                                                                                    <script type="text/javascript">dropdown_select_view_category("{$expense_search_category}");</script>
                                                                            </td>
                                                                            
                                                                            <!-- end of Category Search -->

                                                                            <!-- Navigation Section  -->

                                                                            <td valign="top" nowrap>
                                                                            <form id="1">

                                                                                    <!-- Left buttons -->
                                                                                    <a href="?page=expense%3Asearch&expense_search_category={$expense_search_category}&expense_search_term={$expense_search_term}&submit=submit&page_no=1&page_title={$translate_expense_search_title}"><img src="images/rewnd_24.gif" border="0" alt=""></a>&nbsp;
                                                                                    {if $previous != ''}
                                                                                    <a href="?page=expense%3Asearch&expense_search_category={$expense_search_category}&expense_search_term={$expense_search_term}&submit=submit&page_no={$previous}&page_title={$translate_expense_search_title}"><img src="images/back_24.gif" border="0" alt=""></a>&nbsp;
                                                                                    {/if}
                                                                                    <!-- end of Left Side Buttons -->

                                                                                    <!-- Right Side Buttons -->
                                                                                    {if $next != ''}
                                                                                    <a href="?page=expense%3Asearch&expense_search_category={$expense_search_category}&expense_search_term={$expense_search_term}&submit=submit&page_no={$next}&page_title={$translate_expense_search_title}"><img src="images/forwd_24.gif" border="0" alt=""></a>
                                                                                    {/if}
                                                                                    <a href="?page=expense%3Asearch&expense_search_category={$expense_search_category}&expense_search_term={$expense_search_term}&submit=submit&page_no={$total_pages}&page_title={$translate_expense_search_title}"><img src="images/fastf_24.gif" border="0" alt=""></a>
                                                                                    <!-- end of Right Side Buttons -->

                                                                                    <!-- Page Number Display -->
                                                                                    <br>
                                                                                    {$translate_page} {$page_no} {$translate_of} {$total_pages}
                                                                                    <br />
                                                                                    {$total_results} {$translate_records_found}.
                                                                                    <!-- end of Page Number Display -->

                                                                                    </form>

                                                                            <!-- end of Navigation Section -->

                                                                                    <!-- Goto Page Form -->
                                                                                    {literal}
                                                                                    <form  method="POST" name="goto_page" id="goto_page" autocomplete="off"  onsubmit="try { var myValidator = validate_expense_goto_page; } catch(e) { return true; } return myValidator(this);">
                                                                                    {/literal}
                                                                                    <input class="olotd5" size="10" id="goto_page_no" name="goto_page_no" type="text" onkeypress="return onlyNumbers();" />
                                                                                    <input class="olotd5" name="submit" value="{$translate_expense_search_goto_page_button}" type="submit" />
                                                                                    </form>
                                                                                    <!-- End of Goto Page Form -->

                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td valign="top" colspan="2">

                                                                                <!-- Records Table -->

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
                                                                                        {section name=i loop=$expense_search_result}

                                                                                        <!-- This allows double clicking on a row and opens the corresponding expense view details -->
                                                                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=expense:expense_details&expenseID={$expense_search_result[i].EXPENSE_ID}&page_title={$translate_expense_details_title}';" class="row1">

                                                                                            <!-- Expense ID Column -->
                                                                                            <td class="olotd4" nowrap><a href="index.php?page=expense:expense_details&expenseID={$expense_search_result[i].EXPENSE_ID}&page_title={$translate_expense_details_title}">{$expense_search_result[i].EXPENSE_ID}</a></td>

                                                                                            <!-- Payee Column -->
                                                                                            <td class="olotd4" nowrap>{$expense_search_result[i].EXPENSE_PAYEE}</td>

                                                                                            <!-- Date Column -->
                                                                                            <td class="olotd4" nowrap>{$expense_search_result[i].EXPENSE_DATE|date_format:$date_format}</td>

                                                                                            <!-- Expense Type Column -->
                                                                                            <td class="olotd4" nowrap>

                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==1}
                                                                                                                {$translate_expense_type_1}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==2}
                                                                                                                {$translate_expense_type_2}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==3}
                                                                                                                {$translate_expense_type_3}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==4}
                                                                                                                {$translate_expense_type_4}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==5}
                                                                                                                {$translate_expense_type_5}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==6}
                                                                                                                {$translate_expense_type_6}
                                                                                                        {/if}
                                                                                                         {if $expense_search_result[i].EXPENSE_TYPE ==7}
                                                                                                                {$translate_expense_type_7}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==8}
                                                                                                                {$translate_expense_type_8}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==9}
                                                                                                                {$translate_expense_type_9}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==10}
                                                                                                                {$translate_expense_type_10}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==11}
                                                                                                                {$translate_expense_type_11}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==12}
                                                                                                                {$translate_expense_type_12}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==13}
                                                                                                                {$translate_expense_type_13}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==14}
                                                                                                                {$translate_expense_type_14}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==15}
                                                                                                                {$translate_expense_type_15}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==16}
                                                                                                                {$translate_expense_type_16}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==17}
                                                                                                                {$translate_expense_type_17}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==18}
                                                                                                                {$translate_expense_type_18}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==19}
                                                                                                                {$translate_expense_type_19}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==20}
                                                                                                                {$translate_expense_type_20}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_TYPE ==21}
                                                                                                                {$translate_expense_type_21}
                                                                                                        {/if}
                                                                                            </td>

                                                                                            <!-- Payment Method -->
                                                                                            <td class="olotd4" nowrap>

                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==1}
                                                                                                                {$translate_expense_payment_method_1}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==2}
                                                                                                                {$translate_expense_payment_method_2}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==3}
                                                                                                                {$translate_expense_payment_method_3}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==4}
                                                                                                                {$translate_expense_payment_method_4}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==5}
                                                                                                                {$translate_expense_payment_method_5}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==6}
                                                                                                                {$translate_expense_payment_method_6}
                                                                                                        {/if}
                                                                                                         {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==7}
                                                                                                                {$translate_expense_payment_method_7}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==8}
                                                                                                                {$translate_expense_payment_method_8}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==9}
                                                                                                                {$translate_expense_payment_method_9}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==10}
                                                                                                                {$translate_expense_payment_method_10}
                                                                                                        {/if}
                                                                                                        {if $expense_search_result[i].EXPENSE_PAYMENT_METHOD ==11}
                                                                                                                {$translate_expense_payment_method_11}
                                                                                                        {/if}
                                                                                            </td>

                                                                                            <!-- Net Amount Column -->
                                                                                            <td class="olotd4" nowrap>{$currency_sym} {$expense_search_result[i].EXPENSE_NET_AMOUNT}</td>

                                                                                            <!-- Tax Rate Column -->
                                                                                            <td class="olotd4" nowrap>{$expense_search_result[i].EXPENSE_TAX_RATE} %</td>

                                                                                            <!-- Tax Amount Column -->
                                                                                            <td class="olotd4" nowrap>{$currency_sym} {$expense_search_result[i].EXPENSE_TAX_AMOUNT}</td>

                                                                                            <!-- Gross Amount Column -->

                                                                                            <td class="olotd4" nowrap>{$currency_sym} {$expense_search_result[i].EXPENSE_GROSS_AMOUNT}</td>

                                                                                            <!-- Notes Column -->
                                                                                            <td class="olotd4" nowrap>{if !$expense_search_result[i].EXPENSE_NOTES == ""}
                                                                                                <img src="images/icons/16x16/view.gif" border="0" alt=""
                                                                                                    onMouseOver="ddrivetip('<b>{$translate_expense_notes}</b><hr><p>{$expense_search_result[i].EXPENSE_NOTES|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                                                                                    onMouseOut="hideddrivetip()">{/if}</td>

                                                                                            <!-- Items Column  -->
                                                                                            <td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" border="0" alt=""
                                                                                                    onMouseOver="ddrivetip('<b>{$translate_expense_items}</b><hr><p>{$expense_search_result[i].EXPENSE_ITEMS|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                                                                                    onMouseOut="hideddrivetip()"></td>

                                                                                            <!-- Action Column -->
                                                                                            <td class="olotd4" nowrap>
                                                                                                <a href="index.php?page=expense:expense_details&expenseID={$expense_search_result[i].EXPENSE_ID}&page_title={$translate_expense_details_title}">
                                                                                                    <img src="images/icons/16x16/viewmag.gif" alt="" border="0"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_expense_search_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>

                                                                                                <a href="?page=expense:edit&expenseID={$expense_search_result[i].EXPENSE_ID}&page_title={$translate_expense_edit_title}">
                                                                                                    <img src="images/icons/16x16/small_edit.gif" alt=""  border="0"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_expense_search_edit_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>

                                                                                                <a href="?page=expense:search&amp;page_title={$translate_expense_search_title}" onclick="confirmDelete({$expense_search_result[i].EXPENSE_ID});">
                                                                                                    <img src="images/icons/delete.gif" alt="" border="0" height="14" width="14"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_expense_search_delete_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>																										 

                                                                                        </tr>
                                                                                        {/section}
                                                                                    </table>

                                                                                    <!-- end of Records Table -->

                                                                                   </td>
                                                                                </tr>
                                                                             </table>

                                                                             <!-- end of Content -->

                                                                            </td>
                                                                    </tr>
                                                            </table>
                                                    </td>
                                            </tr>
                                    </table>
                            </td>
                    </tr>
            </table>
